<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
{
    // 1. Crear usuario de prueba
    $user = \App\Models\User::create([
        'name' => 'Usuario Prueba',
        'email' => 'test@logros.com',
        'password' => bcrypt('password')
    ]);

    // 2. Crear logros de prueba
    $logros = [
        [
            'nombre' => 'Primer libro leído',
            'descripcion' => 'Completar tu primer libro',
            'tipo' => 'libros_leidos',
            'requisito' => 1
        ],
        [
            'nombre' => 'Lector principiante',
            'descripcion' => 'Leer 3 libros',
            'tipo' => 'libros_leidos',
            'requisito' => 3
        ]
    ];

    foreach ($logros as $logro) {
        \App\Models\Logro::create($logro);
    }

    // 3. Simular acción para desbloquear logros
    event(new \App\Events\LibroLeido($user, \App\Models\Libro::firstOrCreate([
        'titulo' => 'Libro de Prueba'
    ])));
}
}
