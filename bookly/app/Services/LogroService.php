<?php
namespace App\Services;

use App\Models\User;
use App\Models\Logro;

class LogroService
{
    public static function checkLogros(User $user, string $tipo, int $incremento = 1)
    {
        $logros = Logro::where('tipo', $tipo)->get();

        foreach ($logros as $logro) {
            $pivot = $user->logros()->where('logro_id', $logro->id)->first();
            
            if ($pivot && $pivot->pivot->completado) continue;

            $progreso = ($pivot->pivot->progreso ?? 0) + $incremento;
            $completado = $progreso >= $logro->requisito;

            $user->logros()->syncWithoutDetaching([
                $logro->id => [
                    'progreso' => $progreso,
                    'completado' => $completado,
                    'completado_en' => $completado ? now() : null
                ]
            ]);

            if ($completado) {
                self::dispatchNotification($user, $logro);
            }
        }
    }

    protected static function dispatchNotification(User $user, Logro $logro)
    {
        // Aquí puedes implementar notificaciones
        // Ej: enviar email o notificación en la app
    }
}