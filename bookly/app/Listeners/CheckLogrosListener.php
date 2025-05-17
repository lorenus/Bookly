<?php

namespace App\Listeners;

use App\Events\LibroLeido;
use App\Models\Logro;
use Illuminate\Support\Facades\Log;

class CheckLogrosListener
{
    public function handle(LibroLeido $event)
    {
        $user = $event->user;
        $now = now();

        // Logros por cantidad total
        $totalLibros = $user->librosLeidos()->count();
        $this->checkLogrosCantidad($user, $totalLibros);

        // Logro: Ráfaga Lectora (3 libros en 7 días)
        $librosUltimaSemana = $user->librosLeidos()
            ->where('created_at', '>=', $now->subDays(7))
            ->count();
        if ($librosUltimaSemana >= 3) {
            $this->asignarLogro($user, 'rafaga_lectora');
        }

        // Logro: Compromiso Semanal (1 libro/semana ×4 semanas)
        $this->checkCompromisoSemanal($user, $now);

        // Logro: Inicio Motivado (leer en enero)
        if ($now->month == 1) {
            $this->asignarLogro($user, 'inicio_motivado');
        }

        // Logro: Reto Completado
        if ($user->reto_anual && $totalLibros >= $user->reto_anual) {
            $this->asignarLogro($user, 'reto_completado');
        }
    }

    private function checkLogrosCantidad($user, $cantidad)
    {
        $logros = [
            1 => 'primeros_pasos',
            5 => 'lector_novato',
            15 => 'lector_avanzado',
            30 => 'devorador_libros'
        ];

        foreach ($logros as $limite => $codigo) {
            if ($cantidad >= $limite) {
                $this->asignarLogro($user, $codigo);
            }
        }
    }

    private function checkCompromisoSemanal($user, $now)
    {
        $semanasConsecutivas = 0;
        $fecha = $now->copy();
        
        for ($i = 0; $i < 4; $i++) {
            if ($user->librosLeidos()
                ->whereBetween('created_at', [$fecha->copy()->startOfWeek(), $fecha->copy()->endOfWeek()])
                ->exists()) {
                $semanasConsecutivas++;
            } else {
                break;
            }
            $fecha->subWeek();
        }
        
        if ($semanasConsecutivas >= 4) {
            $this->asignarLogro($user, 'compromiso_semanal');
        }
    }

    private function asignarLogro($user, $codigoLogro)
    {
        try {
            $logro = Logro::where('codigo', $codigoLogro)->first();
            
            if ($logro && !$user->logros()->where('logro_id', $logro->id)->exists()) {
                $user->logros()->attach($logro->id, [
                    'progreso' => 1,
                    'completado' => true,
                    'completado_en' => now()
                ]);
                
                Log::info("Logro asignado: {$logro->nombre} al usuario {$user->id}");
            }
        } catch (\Exception $e) {
            Log::error("Error asignando logro: " . $e->getMessage());
        }
    }
}
