<?php

namespace App\Listeners;

use App\Events\LibroLeido;
use App\Models\Logro;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckLogrosListener
{
    public function handle(LibroLeido $event)
{
    Log::info('CheckLogrosListener ejecutado', [
        'user' => $event->user->id,
        'libro' => $event->libro->id
    ]);

    try {
        // 1. Verifica conexión básica
        $logrosCount = Logro::count();
        Log::info("Total logros en BD: $logrosCount");

        // 2. Registra logro básico
        $logro = Logro::where('tipo', 'libros_leidos')->first();
        
        if ($logro) {
            $event->user->logros()->syncWithoutDetaching([
                $logro->id => [
                    'progreso' => 1,
                    'completado' => true,
                    'completado_en' => now()
                ]
            ]);
            Log::info("Logro asignado: $logro->id");
        }

        // 3. Verificación en BD
        $userLogros = DB::table('logro_user')
                      ->where('user_id', $event->user->id)
                      ->get();
        Log::info("Logros del usuario:", $userLogros->toArray());

    } catch (\Exception $e) {
        Log::error("Error en CheckLogrosListener: ".$e->getMessage());
    }
}
}
