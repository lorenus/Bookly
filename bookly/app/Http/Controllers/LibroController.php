<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class LibroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $response = Http::withoutVerifying()
            ->get("https://www.googleapis.com/books/v1/volumes/{$id}");

        return view('libros.show', [
            'book' => $response->json()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Libro $libro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Libro $libro)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Libro $libro)
    {
        //
    }

    public function marcarComoComprado(Request $request, $googleId)
{
    try {
        $userId = Auth::id();
        $comprado = $request->has('comprado');

        // Buscar el libro local por google_id
        $libro = Libro::where('google_id', $googleId)->first();

        if (!$libro) {
            // Si no existe, crearlo primero
            $response = Http::get("https://www.googleapis.com/books/v1/volumes/{$googleId}");
            $bookData = $response->json();

            $libro = Libro::create([
                'google_id' => $googleId,
                'titulo' => $bookData['volumeInfo']['title'] ?? 'Sin título',
                'autor' => implode(', ', $bookData['volumeInfo']['authors'] ?? []),
                // ... otros campos necesarios
            ]);
        }

        // Ahora usa el ID local
        DB::table('libros_usuario')->updateOrInsert(
            [
                'user_id' => $userId,
                'libro_id' => $libro->id
            ],
            [
                'comprado' => $comprado,
                'estado' => 'porLeer',
                'updated_at' => now()
            ]
        );

        return back()->with('success', $comprado 
            ? 'Libro marcado como comprado' 
            : 'Libro marcado como no comprado');
    } catch (\Exception $e) {
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
}

public function addToList(Request $request)
{
    $request->validate([
        'libro_id' => 'required|string',
        'titulo' => 'required|string',
        'autor' => 'nullable|string',
        'portada' => 'nullable|url',
        'estado' => 'required|in:leyendo,leido,porLeer,favoritos'
    ]);

    $userId = Auth::id();

    try {
        // Obtener detalles completos del libro de la API si es necesario
        $bookDetails = Http::withoutVerifying()
            ->get("https://www.googleapis.com/books/v1/volumes/{$request->libro_id}")
            ->json();

        // Buscar o crear el libro con todos los campos requeridos
        $libro = Libro::firstOrCreate(
            ['google_id' => $request->libro_id],
            [
                'titulo' => $request->titulo,
                'autor' => $request->autor,
                'sinopsis' => $bookDetails['volumeInfo']['description'] ?? 'Sin sinopsis disponible',
                'urlPortada' => $request->portada ?? asset('images/default-cover.jpg'),
                'isbn' => $this->extractIsbn($bookDetails),
                'numPaginas' => $bookDetails['volumeInfo']['pageCount'] ?? null
            ]
        );

        // Verificar si el libro ya está en la lista del usuario
        $existingRecord = DB::table('libros_usuario')
            ->where('user_id', $userId)
            ->where('libro_id', $libro->id)
            ->first();

        if ($existingRecord) {
            // Actualizar el estado existente
            DB::table('libros_usuario')
                ->where('user_id', $userId)
                ->where('libro_id', $libro->id)
                ->update([
                    'estado' => $request->estado,
                    'valoracion' => $request->estado === 'favoritos' ? 5 : $existingRecord->valoracion,
                    'updated_at' => now()
                ]);
        } else {
            // Crear nuevo registro
            DB::table('libros_usuario')->insert([
                'user_id' => $userId,
                'libro_id' => $libro->id,
                'estado' => $request->estado,
                'valoracion' => $request->estado === 'favoritos' ? 5 : null,
                'comprado' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return back()->with('success', 'Libro añadido a tu lista correctamente');
    } catch (\Exception $e) {
        return back()->with('error', 'Error al añadir el libro a tu lista: ' . $e->getMessage());
    }
}

    // Función auxiliar para extraer ISBN si está en el ID
    private function extractIsbn(array $bookData)
    {
        // Verifica si existen los identificadores industriales
        if (!isset($bookData['volumeInfo']['industryIdentifiers'])) {
            return null;
        }

        // Busca ISBN en los identificadores
        foreach ($bookData['volumeInfo']['industryIdentifiers'] as $identifier) {
            if (
                isset($identifier['type'], $identifier['identifier']) &&
                in_array($identifier['type'], ['ISBN_13', 'ISBN_10'])
            ) {
                return $identifier['identifier'];
            }
        }

        // Intenta extraer ISBN del ID de Google Books como respaldo
        if (isset($bookData['id']) && is_string($bookData['id'])) {
            if (preg_match('/[0-9]{9,13}/', $bookData['id'], $matches)) {
                return $matches[0];
            }
        }

        return null;
    }
}
