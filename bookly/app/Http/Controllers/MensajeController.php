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

        // Marcar como leídas al verlas (opcional)
        $user->notificacionesRecibidas()->where('leida', false)->update(['leida' => true]);

        return view('mensajes.index', [
            'notificaciones' => $notificaciones,
        ]);
    }

    public function aceptarNotificacion(Notificacion $notificacion)
    {
        $user = User::with('libros')->find(Auth::id());

        // Validar que el receptor es el usuario autenticado
        if ($notificacion->receptor_id !== $user->id) {
            abort(403, 'No autorizado');
        }

        switch ($notificacion->tipo) {
            case Notificacion::TIPO_AMISTAD:
                // Crear relación de amistad en ambas direcciones
                Amistad::firstOrCreate([
                    'user_id' => $notificacion->emisor_id,
                    'amigo_id' => $notificacion->receptor_id
                ], ['estado' => 'aceptada']);

                Amistad::firstOrCreate([
                    'user_id' => $notificacion->receptor_id,
                    'amigo_id' => $notificacion->emisor_id
                ], ['estado' => 'aceptada']);
                break;

            case Notificacion::TIPO_RECOMENDACION:
                // Añadir libro a la lista "por leer" si no existe ya
                $libroId = $notificacion->datos['libro_id'];

                // Verificar si el libro existe en la base de datos primero
                $libro = Libro::find($libroId);

                if (!$libro) {
                    // Si el libro no existe, crearlo con los datos de la notificación
                    $libro = Libro::create([
                        'id' => $libroId,
                        'titulo' => $notificacion->datos['titulo'],
                        'urlPortada' => $notificacion->datos['portada'] ?? null,
                        // Añade otros campos necesarios
                    ]);
                }

                // Usar syncWithoutDetaching para evitar duplicados
                $user->libros()->syncWithoutDetaching([
                    $libroId => [
                        'estado' => 'porLeer',
                        'comprado' => false,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                ]);
                break;
        }

        $notificacion->marcarComoAceptada();
        return back()->with('success', 'Libro añadido a tu lista de "Por leer"');
    }

    public function rechazarNotificacion(Notificacion $notificacion)
    {
        $notificacion->marcarComoRechazada();
        return back()->with('success', 'Solicitud rechazada');
    }

    public function eliminarNotificacion(Notificacion $notificacion)
    {
        // Verificar que el usuario autenticado es el receptor
        if ($notificacion->receptor_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        $notificacion->delete();

        return back()->with('success', 'Notificación eliminada');
    }
}
