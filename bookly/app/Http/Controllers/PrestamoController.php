<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Libro;
use App\Models\Amistad;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PrestamoController extends Controller
{
    public function create(Request $request)
    {
        $user = User::with('libros')->find(Auth::id());

        // Obtener libros disponibles para prestar
        $librosDisponibles = $user->libros()
            ->wherePivot('comprado', true)
            ->whereDoesntHave('prestamos', function($query) use ($user) {
                $query->where('devuelto', false)
                      ->where('propietario_id', $user->id);
            })
            ->get();

        // Obtener amigos sin duplicados y con nombre completo
        $amigos = User::where(function($query) use ($user) {
                $query->whereHas('amistadesComoUsuario', function($q) use ($user) {
                    $q->where('amigo_id', $user->id)
                      ->where('estado', 'aceptada');
                })
                ->orWhereHas('amistadesComoAmigo', function($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->where('estado', 'aceptada');
                });
            })
            ->select('id', 'name', 'apellidos')
            ->distinct()
            ->get();

        // Obtener los IDs preseleccionados de la URL (si existen)
        $preselectedLibroId = $request->query('libro_id');
        $preselectedAmigoId = $request->query('amigo_id');

        return view('libros.prestar', [
            'librosDisponibles' => $librosDisponibles,
            'amigos' => $amigos,
            'preselectedLibroId' => $preselectedLibroId,
            'preselectedAmigoId' => $preselectedAmigoId
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'libro_id' => 'required|exists:libros,id',
            'amigo_id' => 'required|exists:users,id',
            'fecha_devolucion' => 'required|date|after:today'
        ]);
    
        $libro = Libro::findOrFail($request->libro_id);
        
        // Verificar propiedad del libro
        if (!$libro->usuarios()->where('user_id', Auth::id())->wherePivot('comprado', true)->exists()) {
            return back()->with('error', 'No puedes prestar un libro que no has comprado');
        }
    
        // Verificar amistad
        $esAmigo = Amistad::where(function($query) use ($request) {
                $query->where('user_id', Auth::id())
                    ->where('amigo_id', $request->amigo_id)
                    ->orWhere('user_id', $request->amigo_id)
                    ->where('amigo_id', Auth::id());
            })
            ->where('estado', 'aceptada')
            ->exists();
    
        if (!$esAmigo) {
            return back()->with('error', 'Solo puedes prestar libros a tus amigos');
        }
    
        // Crear préstamo
        Prestamo::create([
            'libro_id' => $request->libro_id,
            'propietario_id' => Auth::id(),
            'receptor_id' => $request->amigo_id,
            'fecha_limite' => $request->fecha_devolucion,
            'devuelto' => false
        ]);
    
        // Notificar al receptor
        Notificacion::crearNotificacionPrestamo(
            Auth::id(),
            $request->amigo_id,
            $libro,
            $request->fecha_devolucion
        );
    
        return redirect()->route('listas.prestados')
            ->with('success', 'Libro prestado con éxito');
    }

    public function marcarDevuelto(Prestamo $prestamo)
    {
        if ($prestamo->propietario_id != Auth::id()) {
            abort(403, 'No tienes permiso para realizar esta acción');
        }

        $prestamo->update(['devuelto' => true]);

        // Opcional: Notificar al que prestó que se devolvió
        Notificacion::create([
            'emisor_id' => Auth::id(),
            'receptor_id' => $prestamo->propietario_id,
            'tipo' => Notificacion::TIPO_PRESTAMO,
            'contenido' => "Se ha devuelto el libro: {$prestamo->libro->titulo}",
            'estado' => Notificacion::ESTADO_LEIDA,
            'datos' => [
                'libro_id' => $prestamo->libro_id,
                'accion' => 'devuelto'
            ]
        ]);

        return back()->with('success', 'Libro marcado como devuelto');
    }
}
