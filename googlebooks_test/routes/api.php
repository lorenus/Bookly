<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::middleware('api')->group(function () {
    Route::get('/books/search', [BookController::class, 'search']);
});