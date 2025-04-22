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
        // Obtener notificaciones de amistad pendientes
        $solicitudesPendientes = Notificacion::where('receptor_id', Auth::id())
            ->where('tipo', 'amistad')
            ->where('estado', 'pendiente')
            ->with('remitente')
            ->get();

        // Obtener amigos aceptados (desde la tabla amistades)
        $amigosAceptados = Amistad::where(function ($query) {
            $query->where('user_id', Auth::id())
                ->orWhere('amigo_id', Auth::id());
        })
            ->where('estado', 'aceptada')
            ->with(['usuario', 'amigo'])
            ->get()
            ->map(function ($amistad) {
                return $amistad->user_id == Auth::id()
                    ? $amistad->amigo
                    : $amistad->usuario;
            });

        return view('amigos.index', [
            'solicitudesPendientes' => $solicitudesPendientes,
            'amigos' => $amigosAceptados,
            'usuarios' => User::where('id', '!=', Auth::id())->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'amigo_id' => 'required|exists:users,id|not_in:' . Auth::id()
        ]);

        $solicitudExistente = Notificacion::where('emisor_id', Auth::id())
            ->where('receptor_id', $request->amigo_id)
            ->where('tipo', 'amistad')
            ->exists();

        if ($solicitudExistente) {
            return back()->with('error', 'Ya has enviado una solicitud a este usuario');
        }

        Notificacion::crearNotificacionAmistad(Auth::id(), $request->amigo_id);

        return back()->with('success', 'Solicitud de amistad enviada');
    }

    public function update(Request $request, Notificacion $notificacion)
    {
        $request->validate([
            'accion' => 'required|in:aceptar,rechazar'
        ]);

        if ($request->accion == 'aceptar') {
            // Crear la amistad en la tabla amistades
            Amistad::create([
                'user_id' => $notificacion->emisor_id,
                'amigo_id' => $notificacion->receptor_id,
                'estado' => 'aceptada'
            ]);

            $notificacion->aceptar();
            $message = 'Solicitud de amistad aceptada';
        } else {
            $notificacion->rechazar();
            $message = 'Solicitud de amistad rechazada';
        }

        return back()->with('success', $message);
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

        // Eliminar ambas direcciones
        Amistad::where(function ($query) use ($user) {
            $query->where('user_id', Auth::id())
                ->where('amigo_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('amigo_id', Auth::id());
        })->delete();

        return back()->with('success', 'Amistad eliminada correctamente');
    }
}
