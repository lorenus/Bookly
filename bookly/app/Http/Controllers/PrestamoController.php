<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Libro;
use App\Models\Amistad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PrestamoController extends Controller
{
    /**
     * Muestra el formulario para crear un nuevo préstamo.
     */
    public function create()
{
    $user = User::with('libros')->find(Auth::id());

    $librosDisponibles = $user->libros()
        ->wherePivot('comprado', true)
        ->whereDoesntHave('prestamos', function($query) use ($user) {
            $query->where('devuelto', false)
                  ->where('propietario_id', $user->id); 
        })
        ->get();

    // Obtener amigos del usuario actual
    $amigos = Amistad::where(function($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhere('amigo_id', $user->id);
        })
        ->where('estado', 'aceptada')
        ->with(['usuario', 'amigo'])
        ->get()
        ->map(function($amistad) use ($user) {
            return $amistad->user_id == $user->id 
                ? $amistad->amigo 
                : $amistad->usuario;
        });

    return view('libros.prestar', [
        'librosDisponibles' => $librosDisponibles,
        'amigos' => $amigos
    ]);
}
    /**
     * Almacena un nuevo préstamo.
     */
    public function store(Request $request)
    {
        $request->validate([
            'libro_id' => 'required|exists:libros,id',
            'amigo_id' => 'required|exists:users,id',
            'fecha_devolucion' => 'required|date|after:today'
        ]);
    
        // Verificar que el libro pertenece al usuario
        $libro = Libro::findOrFail($request->libro_id);
        if (!$libro->usuarios()->where('user_id', Auth::id())->wherePivot('comprado', true)->exists()) {
            return back()->with('error', 'No puedes prestar un libro que no has comprado');
        }
    
        // Verificar que es amigo del usuario
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
    
        // Crear el préstamo
        Prestamo::create([
            'libro_id' => $request->libro_id,
            'propietario_id' => Auth::id(), 
            'receptor_id' => $request->amigo_id, 
            'fecha_limite' => $request->fecha_devolucion, 
            'devuelto' => false
        ]);
    
        return redirect()->route('listas.prestados')
            ->with('success', 'Libro prestado con éxito');
    }

    /**
     * Marca un libro como devuelto.
     */
    public function marcarDevuelto(Prestamo $prestamo)
{
    
    if ($prestamo->propietario_id != Auth::id()) {
        abort(403, 'No tienes permiso para realizar esta acción. Usuario: '.Auth::id().', Dueño: '.$prestamo->propietario_id);
    }

    $prestamo->update(['devuelto' => true]);

    return back()->with('success', 'Libro marcado como devuelto');
}
}