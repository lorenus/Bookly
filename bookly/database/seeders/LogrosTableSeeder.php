<?php

namespace Database\Seeders;

use App\Models\Logro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'nombre' => 'Amante de los clásicos',
                'requisito' => 3,
                'tipo' => 'clasicos_leidos'
            ],

        ];

        foreach ($logros as $logro) {
            Logro::firstOrCreate($logro);
        }
    }
}