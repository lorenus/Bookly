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

            // 1. Buscar en la base de datos local
            $localResults = Libro::where(function ($q) use ($query) {
                $q->where('titulo', 'LIKE', "%{$query}%")
                    ->orWhere('autor', 'LIKE', "%{$query}%")
                    ->orWhere('isbn', $query);
            })
                ->limit(5) // Limitar a 5 para no traer más de lo necesario
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
                })
                ->toArray();

            $results = $localResults;
            $remainingSlots = 5 - count($results);

            Log::info("Resultados locales para: $query", ['count' => count($results)]);

            // 2. Si necesitamos más resultados, buscar en Google Books API
            if ($remainingSlots > 0) {
                $response = Http::withoutVerifying()
                    ->timeout(15)
                    ->get('https://www.googleapis.com/books/v1/volumes', [
                        'q' => $query, // Búsqueda más amplia
                        'maxResults' => $remainingSlots,
                        'key' => env('GOOGLE_BOOKS_API_KEY')
                    ]);

                if ($response->successful()) {
                    $apiItems = $response->json()['items'] ?? [];
                    
                    // Procesar resultados de API para formato consistente
                    $apiResults = array_map(function ($item) {
                        return [
                            'id' => $item['id'] ?? null,
                            'volumeInfo' => [
                                'title' => $item['volumeInfo']['title'] ?? 'Sin título',
                                'authors' => $item['volumeInfo']['authors'] ?? [],
                                'imageLinks' => [
                                    'thumbnail' => $item['volumeInfo']['imageLinks']['thumbnail'] ?? null
                                ]
                            ]
                        ];
                    }, array_slice($apiItems, 0, $remainingSlots));

                    $results = array_merge($results, $apiResults);
                    Log::info("Resultados API para: $query", ['count' => count($apiResults)]);
                } else {
                    Log::error("Error en API Google Books", ['status' => $response->status()]);
                }
            }

            Log::info("Resultados totales para: $query", ['count' => count($results)]);
            return response()->json(array_slice($results, 0, 5)); // Asegurar máximo 5 resultados

        } catch (\Exception $e) {
            Log::error("Error en búsqueda", ['error' => $e->getMessage()]);
            return response()->json([], 500);
        }
    }
}