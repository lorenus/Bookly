<?php

namespace Database\Seeders;

use App\Models\Logro;
use Illuminate\Database\Seeder;

class LogrosTableSeeder extends Seeder
{
    public function run()
    {
        $logros = [
            [
                'nombre' => 'Primeros pasos',
                'requisito' => 1,
                'tipo' => 'libros_leidos'
            ],
            [
                'nombre' => 'Lector novato',
                'requisito' => 5,
                'tipo' => 'libros_leidos'
            ],
            [
                'nombre' => 'Lector avanzado',
                'requisito' => 15,
                'tipo' => 'libros_leidos'
            ],
            [
                'nombre' => 'Devorador de libros',
                'requisito' => 30,
                'tipo' => 'libros_leidos'
            ],
            [
                'nombre' => 'Ráfaga lectora',
                'requisito' => 3,
                'tipo' => 'libros_semana'
            ],
            [
                'nombre' => 'Compromiso semanal',
                'requisito' => 4,
                'tipo' => 'semanas_consecutivas'
            ],
            [
                'nombre' => 'Inicio motivado',
                'requisito' => 1,
                'tipo' => 'lectura_enero'
            ],
            [
                'nombre' => 'Reto completado',
                'requisito' => 1,
                'tipo' => 'reto_anual'
            ]
        ];

        foreach ($logros as $logro) {
            Logro::firstOrCreate(
                ['nombre' => $logro['nombre']],
                $logro
            );
        }

        $this->command->info('Logros básicos creados exitosamente!');
    }
}
