<?php

namespace App\Listeners;

use App\Events\LibroLeido;
use App\Models\Logro;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CheckLogrosListener
{
       private const LOGRO_IDS = [
        'primeros_pasos' => 4,
        'lector_novato' => 5,
        'lector_avanzado' => 6,
        'devorador_libros' => 7,
        'rafaga_lectora' => 8,
        'compromiso_semanal' => 9,
        'inicio_motivado' => 10,
        'reto_completado' => 11,
    ];

    public function handle(LibroLeido $event)
    {
        $user = $event->user;
        $now = now();

        Log::info("CheckLogrosListener: Evento LibroLeido para usuario ID: {$user->id}");

        // Logros por cantidad total (anuales)
        $totalLibrosEsteAnio = $user->librosLeidosEsteAnio();
        Log::info("CheckLogrosListener: Total libros leídos ESTE AÑO por usuario {$user->id}: {$totalLibrosEsteAnio}");
        $this->checkLogrosCantidad($user, $totalLibrosEsteAnio);

        // Logro: Ráfaga Lectora (3 libros en 7 días)
        $librosUltimaSemana = $user->librosLeidos()
            ->wherePivot('updated_at', '>=', $now->copy()->subDays(7))
            ->count();
        Log::info("CheckLogrosListener: Libros leídos en última semana por usuario {$user->id}: {$librosUltimaSemana}");
        if ($librosUltimaSemana >= 3) {
            $this->asignarLogro($user, self::LOGRO_IDS['rafaga_lectora']);
        }

        // Logro: Compromiso Semanal (1 libro/semana ×4 semanas)
        $this->checkCompromisoSemanal($user, $now);

        // Logro: Inicio Motivado (leer en enero)
        if ($now->month == 1) {
            $this->asignarLogro($user, self::LOGRO_IDS['inicio_motivado']);
        }

        // Logro: Reto Completado
        if ($user->retoAnual && $totalLibrosEsteAnio >= $user->retoAnual) {
            $this->asignarLogro($user, self::LOGRO_IDS['reto_completado']);
        }
    }

    private function checkLogrosCantidad($user, $cantidad)
    {
        $logrosMap = [
            1 => 'primeros_pasos',
            5 => 'lector_novato',
            15 => 'lector_avanzado',
            30 => 'devorador_libros'
        ];

        foreach ($logrosMap as $limite => $conceptualName) {
            if ($cantidad >= $limite) {
                $logroId = self::LOGRO_IDS[$conceptualName] ?? null;
                if ($logroId) {
                    $this->asignarLogro($user, $logroId);
                } else {
                    Log::warning("checkLogrosCantidad: ID de logro no encontrado para el nombre conceptual: {$conceptualName}");
                }
            }
        }
    }

    private function checkCompromisoSemanal($user, $now)
    {
        $semanasConsecutivas = 0;
        $fecha = $now->copy();

        for ($i = 0; $i < 4; $i++) {
             if ($user->librosLeidos()
                ->whereBetween('libros_usuario.updated_at', [$fecha->copy()->startOfWeek(Carbon::MONDAY), $fecha->copy()->endOfWeek(Carbon::SUNDAY)])
                ->exists()) {
                $semanasConsecutivas++;
            } else {
                break;
            }
            $fecha->subWeek();
        }

        if ($semanasConsecutivas >= 4) {
            $this->asignarLogro($user, self::LOGRO_IDS['compromiso_semanal']);
        }
    }

    private function asignarLogro($user, $logroId)
    {
        try {
            $logro = Logro::find($logroId);

            if (!$logro) {
                Log::warning("AsignarLogro: Logro con ID '{$logroId}' no encontrado en la base de datos.");
                return;
            }

            if (!$user->logros()->where('logro_id', $logro->id)->exists()) {
                $user->logros()->attach($logro->id, [
                    'progreso' => 1,
                    'completado' => true,
                    'completado_en' => now()
                ]);

                Log::info("Logro asignado: {$logro->nombre} (ID: {$logro->id}) al usuario {$user->id}");
            } else {
                Log::info("Logro {$logro->nombre} (ID: {$logro->id}) ya asignado a usuario {$user->id}. No se reasigna.");
            }
        } catch (\Exception $e) {
            Log::error("Error asignando logro (por ID): " . $e->getMessage() . " en " . $e->getFile() . " linea " . $e->getLine());
        }
    }
}