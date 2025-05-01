<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Libro;
use Illuminate\Support\Facades\Log;

class BookSearchController extends Controller
{
    public function search(Request $request)
    {
        try {
            $query = $request->input('query');
            
            if (empty($query)) {
                return response()->json([]);
            }

            // 1. Primero busca en la base de datos local
            $localResults = Libro::where(function($q) use ($query) {
                    $q->where('titulo', 'LIKE', "%{$query}%")
                      ->orWhere('autor', 'LIKE', "%{$query}%")
                      ->orWhere('isbn', $query);
                })
                ->limit(5)
                ->get()
                ->map(function ($libro) {
                    return [
                        'id' => $libro->google_id,
                        'volumeInfo' => [
                            'title' => $libro->titulo,
                            'authors' => $libro->autor ? explode(', ', $libro->autor) : [],
                            'imageLinks' => [
                                'thumbnail' => $libro->urlPortada
                            ]
                        ]
                    ];
                });

            // Si hay resultados locales, devolverlos
            if ($localResults->isNotEmpty()) {
                Log::info("Resultados locales encontrados para: $query");
                return response()->json($localResults);
            }

            // 2. Si no hay resultados locales, buscar en Google Books API
            $response = Http::withoutVerifying()
                ->timeout(15)
                ->get('https://www.googleapis.com/books/v1/volumes', [
                    'q' => "intitle:{$query} OR inauthor:{$query} OR isbn:{$query}",
                    'maxResults' => 5,
                    'key' => env('GOOGLE_BOOKS_API_KEY')
                ]);

            if ($response->successful()) {
                $items = $response->json()['items'] ?? [];
                Log::info("Resultados API para: $query", ['count' => count($items)]);
                return response()->json($items);
            }

            Log::error("Error en API Google Books", ['status' => $response->status()]);
            return response()->json([]);

        } catch (\Exception $e) {
            Log::error("Error en búsqueda", ['error' => $e->getMessage()]);
            return response()->json([], 500);
        }
    }
}
