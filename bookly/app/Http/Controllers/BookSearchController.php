<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookSearchController extends Controller
{
    public function search(Request $request)
{
    $query = $request->input('query');
    
    // Estructura de búsqueda específica (título, autor o ISBN)
    $searchQuery = "intitle:{$query} OR inauthor:{$query} OR isbn:{$query}";
    
    $response = Http::withoutVerifying()
        ->get('https://www.googleapis.com/books/v1/volumes', [
            'q' => $searchQuery,  // Usamos la query estructurada
            'maxResults' => 3,
            'filter' => 'paid-ebooks',
            'key' => env('GOOGLE_BOOKS_API_KEY')
        ]);

    return response()->json($response->json()['items'] ?? []);
}
}