<?php

use App\Http\Controllers\LibroController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ListaController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

 // Perfil de usuario
Route::middleware('auth')->group(function () {
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Listas de libros
Route::middleware('auth')->group(function () {
    Route::get('/listas/{tipoLista}', [ListaController::class, 'show'])->name('listas.show');
});

// Libro
Route::middleware('auth')->group(function () {
    Route::get('/libro', [LibroController::class, 'index'])->name('libros.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/prestar', [LibroController::class, 'prestar'])->name('prestar');
});

Route::middleware('auth')->group(function () {
    Route::get('/logros', [LibroController::class, 'logros'])->name('logros');
});
require __DIR__.'/auth.php';
