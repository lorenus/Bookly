<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookSearchController;

Route::get('/search-books', [BookSearchController::class, 'search']);