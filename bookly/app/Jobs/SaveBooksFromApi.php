<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Libro;

class SaveBooksFromApi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $books;

    public function __construct(array $books)
    {
        $this->books = $books;
    }

    public function handle()
    {
        foreach ($this->books as $bookData) {
            // Extraer ISBN si está disponible
            $isbn = $this->extractIsbn($bookData);
            
            Libro::firstOrCreate(
                ['google_id' => $bookData['id']],
                [
                    'titulo' => $bookData['volumeInfo']['title'] ?? 'Sin título',
                    'autor' => isset($bookData['volumeInfo']['authors']) ? 
                              implode(', ', $bookData['volumeInfo']['authors']) : null,
                    'urlPortada' => $bookData['volumeInfo']['imageLinks']['thumbnail'] ?? null,
                    'isbn' => $isbn,
                    'numPaginas' => $bookData['volumeInfo']['pageCount'] ?? null
                ]
            );
        }
    }

    private function extractIsbn($bookData)
    {
        if (isset($bookData['volumeInfo']['industryIdentifiers'])) {
            foreach ($bookData['volumeInfo']['industryIdentifiers'] as $identifier) {
                if ($identifier['type'] === 'ISBN_13' || $identifier['type'] === 'ISBN_10') {
                    return $identifier['identifier'];
                }
            }
        }
        return null;
    }
}