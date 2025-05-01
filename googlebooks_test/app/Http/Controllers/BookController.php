<?php
namespace App\Http\Controllers; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookController extends Controller
{
    public function search(Request $request)
    {
        $request->validate(['q' => 'required|string|min:1']);

        $response = Http::get('https://www.googleapis.com/books/v1/volumes', [
            'q' => $request->input('q'),
            'key' => env('GOOGLE_BOOKS_API_KEY'),
            'maxResults' => 10
        ]);

        return $response->json();
    }
}