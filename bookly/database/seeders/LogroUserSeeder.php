<?php

namespace Database\Seeders;

use App\Models\Logro;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LogroUserSeeder extends Seeder
{
    public function run()
    {
        // Obtener IDs de logros por su nombre
        $logrosIds = [
            'primeros_pasos' => Logro::where('nombre', 'Primeros pasos')->first()->id,
            'lector_novato' => Logro::where('nombre', 'Lector novato')->first()->id,
            'lector_avanzado' => Logro::where('nombre', 'Lector avanzado')->first()->id,
            'devorador_libros' => Logro::where('nombre', 'Devorador de libros')->first()->id,
            'rafaga_lectora' => Logro::where('nombre', 'Ráfaga lectora')->first()->id,
            'compromiso_semanal' => Logro::where('nombre', 'Compromiso semanal')->first()->id,
            'inicio_motivado' => Logro::where('nombre', 'Inicio motivado')->first()->id
        ];

        // Verificar que todos los logros existen
        if (count(array_filter($logrosIds)) !== 7) {
            $this->command->error('Primero ejecuta LogrosTableSeeder para crear los logros');
            return;
        }

        // Relaciones de ejemplo (todos menos el reto completado)
        $relaciones = [
            [
                'user_id' => 1,
                'logro_id' => $logrosIds['primeros_pasos'],
                'progreso' => 1,
                'completado' => true,
                'completado_en' => now()->subDays(30),
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30)
            ],
            [
                'user_id' => 1,
                'logro_id' => $logrosIds['lector_novato'],
                'progreso' => 5,
                'completado' => true,
                'completado_en' => now()->subDays(15),
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15)
            ],
            [
                'user_id' => 1,
                'logro_id' => $logrosIds['lector_avanzado'],
                'progreso' => 9,
                'completado' => false,
                'completado_en' => null,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(2)
            ],
            [
                'user_id' => 1,
                'logro_id' => $logrosIds['devorador_libros'],
                'progreso' => 12,
                'completado' => false,
                'completado_en' => null,
                'created_at' => now()->subDays(5),
                'updated_at' => now()
            ],
            [
                'user_id' => 1,
                'logro_id' => $logrosIds['rafaga_lectora'],
                'progreso' => 3,
                'completado' => true,
                'completado_en' => now()->subHours(12),
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subHours(12)
            ],
            [
                'user_id' => 1,
                'logro_id' => $logrosIds['compromiso_semanal'],
                'progreso' => 2,
                'completado' => false,
                'completado_en' => null,
                'created_at' => now()->subDays(14),
                'updated_at' => now()
            ],
            [
                'user_id' => 1,
                'logro_id' => $logrosIds['inicio_motivado'],
                'progreso' => 0,
                'completado' => false,
                'completado_en' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('logro_user')->insert($relaciones);
        $this->command->info('Relaciones usuario-logro (1-7) creadas exitosamente!');
    }
}