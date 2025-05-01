<?php

use App\Http\Controllers\AmistadController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\ListaController;
use App\Http\Controllers\MensajeController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Perfil
    Route::get('/perfil', [ProfileController::class, 'show'])->name('perfil');
    Route::get('/perfil/editar', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil/actualizar', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::delete('/perfil/eliminar', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Listas
    Route::get('/listas', [ListaController::class, 'index'])->name('listas.index');
    Route::get('/listas/biblioteca', [ListaController::class, 'biblioteca'])->name('listas.biblioteca');
    Route::get('/listas/prestados', [ListaController::class, 'prestados'])->name('listas.prestados');
    Route::get('/listas/{tipoLista}', [ListaController::class, 'show'])->name('listas.show');


    // Social
    Route::get('/amigos', [AmistadController::class, 'index'])->name('amigos');
    Route::post('/amigos', [AmistadController::class, 'store'])->name('amigos.store');
    Route::put('/amigos/{notificacion}', [AmistadController::class, 'update'])->name('amigos.update');
    Route::delete('/amigos/{user}', [AmistadController::class, 'destroy'])->name('amigos.destroy');
    Route::get('/mensajes', [MensajeController::class, 'index'])->name('mensajes');

    // Libros
    Route::get('/libros/{id}', [LibroController::class, 'show'])->name('libro.show');
    Route::get('/logros', [LibroController::class, 'logros'])->name('logros');
    Route::post('/libros/{libro}/comprar', [ListaController::class, 'marcarComoComprado'])->name('libros.comprar');
    
    // Préstamos
    Route::get('/prestamos', [PrestamoController::class, 'create'])->name('prestamos.crear');
    Route::post('/prestamos', [PrestamoController::class, 'store'])->name('prestamos.guardar');
    Route::patch('/prestamos/{prestamo}/devolver', [PrestamoController::class, 'marcarDevuelto'])->name('prestamos.devolver')->middleware('auth');
});

require __DIR__ . '/auth.php';
