<?php

use App\Http\Controllers\LibroController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ListaController;
use App\Http\Controllers\AmistadController;
use App\Http\Controllers\MensajeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // // Dashboard
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');

    // Perfil
    Route::get('/perfil', [ProfileController::class, 'show'])->name('perfil');
    Route::get('/perfil/editar', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil/actualizar', [ProfileController::class, 'update'])->name('profile.update');

    // Listas
    Route::get('/listas', [ListaController::class, 'index'])->name('listas.index');
    Route::get('/listas/{tipoLista}', [ListaController::class, 'show'])->name('listas.show');

    // Social
    Route::get('/amigos', [AmistadController::class, 'index'])->name('amigos');
    Route::get('/mensajes', [MensajeController::class, 'index'])->name('mensajes');

    // Libros
    Route::get('/libro/{id}', [LibroController::class, 'show'])->name('libro.show');
    Route::get('/prestar', [LibroController::class, 'prestar'])->name('prestar');
    Route::get('/logros', [LibroController::class, 'logros'])->name('logros');
});

require __DIR__.'/auth.php';