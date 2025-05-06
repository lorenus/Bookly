<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LogroUserSeeder extends Seeder
{
    public function run()
    {
        // Primero vaciamos la tabla por si acaso
        DB::table('logro_user')->truncate();

        // Datos de ejemplo para relaciones usuario-logro
        $relaciones = [
            [
                'user_id' => 1,  // ID del usuario
                'logro_id' => 1, // ID del logro "Primeros pasos"
                'progreso' => 1,
                'completado' => true,
                'completado_en' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 1,
                'logro_id' => 2, // ID del logro "Lector ávido"
                'progreso' => 3,
                'completado' => false,
                'completado_en' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Añade más relaciones según necesites
        ];

        // Insertamos los datos
        DB::table('logro_user')->insert($relaciones);

        // Mensaje de confirmación
        $this->command->info('Datos de logro_user creados exitosamente!');
    }
}