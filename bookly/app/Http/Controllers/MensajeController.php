<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use App\Models\Amistad;
use App\Models\Libro;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MensajeController extends Controller
{
    public function index()
    {
        $user = User::with('libros')->find(Auth::id());
        
        $notificaciones = $user->notificacionesRecibidas()
            ->with('remitente')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mensajes.index', [
            'notificaciones' => $notificaciones,
        ]);
    }

    public function aceptarNotificacion(Notificacion $notificacion)
{
    $user = Auth::user();

    // Validar que el receptor es el usuario autenticado
    if ($notificacion->receptor_id !== $user->id) {
        abort(403, 'No autorizado');
    }

    switch ($notificacion->tipo) {
        case Notificacion::TIPO_AMISTAD:
            // Crear relación de amistad en ambas direcciones
            Amistad::create([
                'user_id' => $notificacion->emisor_id,
                'amigo_id' => $notificacion->receptor_id,
                'estado' => 'aceptada'
            ]);
            
            Amistad::create([
                'user_id' => $notificacion->receptor_id,
                'amigo_id' => $notificacion->emisor_id,
                'estado' => 'aceptada'
            ]);
            break;

        case Notificacion::TIPO_RECOMENDACION:
            // Añadir libro a la lista "por leer"
            $libroId = $notificacion->datos['libro_id'];
            $user = User::with('libros')->find(Auth::id());
            $user->libros()->attach($libroId, [
                'estado' => 'porLeer',
                'comprado' => false
            ]);
            break;
    }

    $notificacion->marcarComoAceptada();
    return back()->with('success', 'Acción realizada');
}

    public function rechazarNotificacion(Notificacion $notificacion)
    {
        $notificacion->marcarComoRechazada();
        return back()->with('success', 'Solicitud rechazada');
    }
}