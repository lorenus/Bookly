<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-ssl', function() {
    $response = Http::insecure()->get('https://www.googleapis.com/books/v1/volumes?q=test&key=' . env('GOOGLE_BOOKS_API_KEY'));
    return $response->json();
});