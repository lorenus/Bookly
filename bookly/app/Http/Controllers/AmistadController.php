<?php

namespace App\Http\Controllers;

use App\Models\Amistad;
use App\Models\Notificacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AmistadController extends Controller
{
    public function index()
{
    $user = Auth::user();
    
    // Obtener IDs de amigos sin duplicados
    $amigosIds = Amistad::where('estado', 'aceptada')
        ->where(function($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhere('amigo_id', $user->id);
        })
        ->get()
        ->map(function($amistad) use ($user) {
            return $amistad->user_id == $user->id
                ? $amistad->amigo_id
                : $amistad->user_id;
        })
        ->unique();

    $amigos = User::whereIn('id', $amigosIds)->get();

    return view('amigos.index', [
        'amigos' => $amigos,
        'usuarios' => User::where('id', '!=', $user->id)->get()
    ]);
}

public function store(Request $request)
{
    $request->validate([
        'amigo_id' => 'required|exists:users,id|not_in:' . Auth::id()
    ]);

    // Verificar si ya existe solicitud pendiente
    $solicitudExistente = Amistad::where(function($query) use ($request) {
            $query->where('user_id', Auth::id())
                  ->where('amigo_id', $request->amigo_id);
        })
        ->orWhere(function($query) use ($request) {
            $query->where('user_id', $request->amigo_id)
                  ->where('amigo_id', Auth::id());
        })
        ->exists();

    if ($solicitudExistente) {
        return back()->with('error', 'Ya existe una solicitud con este usuario');
    }

    // Crear nueva solicitud
    Amistad::create([
        'user_id' => Auth::id(),
        'amigo_id' => $request->amigo_id,
        'estado' => 'pendiente'
    ]);

    // Opcional: Crear también la notificación
    Notificacion::crearSolicitudAmistad(Auth::id(), $request->amigo_id);

    return back()->with('success', 'Solicitud de amistad enviada');
}

    public function destroy(User $user)
    {
        // Verificar que existe una amistad
        $amistadExistente = Amistad::where(function ($query) use ($user) {
            $query->where('user_id', Auth::id())
                ->where('amigo_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('amigo_id', Auth::id());
        })->exists();

        if (!$amistadExistente) {
            return back()->with('error', 'No existe esta amistad');
        }

        // Eliminar amistad
        Amistad::where(function ($query) use ($user) {
            $query->where('user_id', Auth::id())
                ->where('amigo_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('amigo_id', Auth::id());
        })->delete();

        return back()->with('success', 'Amistad eliminada correctamente');
    }

    public function detalleAmigo($amigoId)
{
    $amigo = User::with(['logros' => function($query) {
        $query->orderBy('logro_user.completado_en', 'desc')
              ->take(3);
    }])->findOrFail($amigoId);

    return response()->json([
        'id' => $amigo->id,
        'name' => $amigo->name,
        'apellidos' => $amigo->apellidos,
        'imgPerfil' => $amigo->imgPerfil,
        'retoAnual' => $amigo->reto_anual,
        'librosLeidosAnual' => $amigo->libros_leidos_anual,
        'logros' => $amigo->logros->map(function($logro) {
            return [
                'id' => $logro->id,
                'nombre' => $logro->nombre,
                'imagen' => 'logros/logro'.$logro->id.'.png',
                'fecha' => $logro->pivot->completado_en->format('d/m/Y')
            ];
        })
    ]);
}

public function verificarEmail(Request $request)
{
    $request->validate([
        'email' => 'required|email'
    ]);

    $user = User::where('email', $request->email)->first();
    
    return response()->json([
        'existe' => $user !== null,
        'usuario' => $user ? [
            'id' => $user->id,
            'name' => $user->name
        ] : null
    ]);
}
}
