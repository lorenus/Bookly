<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class BookSearchController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:3'
        ]);

        $response = Http::get('https://www.googleapis.com/books/v1/volumes', [
            'q' => $request->q,
            'maxResults' => 5,
            'key' => env('GOOGLE_BOOKS_API_KEY')
        ]);

        return $response->json();
    }
}