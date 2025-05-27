<?php

namespace App\Http\Controllers;

use App\Models\Amistad;
use App\Models\Libro;
use App\Models\Notificacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Events\LibroLeido;


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
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($googleId)
    {
        try {
            
            $response = Http::withoutVerifying()
                ->get("https://www.googleapis.com/books/v1/volumes/{$googleId}");
            $bookDetails = $response->json();

            if (empty($bookDetails)) {
                abort(404, 'Libro no encontrado en la API de Google Books.');
            }
            
            $libroDB = Libro::where('google_id', $googleId)->first();

            return view('libros.show', [
                'book' => $bookDetails,
                'libro' => $libroDB
            ]);

        } catch (\Exception $e) {
            abort(500, 'Hubo un problema al cargar los detalles del libro.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        //
    }

    public function marcarComoComprado(Request $request, $googleId)
    {
        try {
            $user = User::with('libros')->find(Auth::id());
            $comprado = $request->has('comprado');

            // Obtener datos del libro de Google Books
            $bookDetails = Http::withoutVerifying()
                ->get("https://www.googleapis.com/books/v1/volumes/{$googleId}")
                ->json();

            // Crear o actualizar el libro en la base de datos
            $libro = Libro::firstOrCreate(
                ['google_id' => $googleId],
                [
                    'titulo' => $bookDetails['volumeInfo']['title'] ?? 'Sin título',
                    'autor' => isset($bookDetails['volumeInfo']['authors']) ? implode(', ', $bookDetails['volumeInfo']['authors']) : 'Autor desconocido',
                    'sinopsis' => $bookDetails['volumeInfo']['description'] ?? 'Sin sinopsis disponible',
                    'urlPortada' => $bookDetails['volumeInfo']['imageLinks']['thumbnail'] ?? asset('images/default-cover.jpg'),
                    'isbn' => $this->extractIsbn($bookDetails),
                    'numPaginas' => $bookDetails['volumeInfo']['pageCount'] ?? null
                ]
            );

            $user->libros()->syncWithoutDetaching([
                $libro->id => [
                    'comprado' => $comprado,
                    'updated_at' => now()
                ]
            ]);

            return back()->with('success', $comprado
                ? 'Libro marcado como "Lo tengo"'
                : 'Libro marcado como "No lo tengo"');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el estado del libro: ' . $e->getMessage());
        }
    }

    public function addToList(Request $request)
    {
        $user = User::with('libros')->find(Auth::id());

        $request->validate([
            'libro_id' => 'required|string',
            'titulo' => 'required|string',
            'autor' => 'nullable|string',
            'portada' => 'nullable|url',
            'estado' => 'required|in:leyendo,leido,porLeer,favoritos'
        ]);

        try {
            $bookDetails = Http::withoutVerifying()
                ->get("https://www.googleapis.com/books/v1/volumes/{$request->libro_id}")
                ->json();

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

            // *** ESTE ES EL ÚNICO BLOQUE QUE DEBE GESTIONAR LA TABLA PIVOTE ***
            // Recuperar la valoración existente si el libro ya está en la lista del usuario
            $existingPivot = $user->libros()->wherePivot('libro_id', $libro->id)->first()->pivot ?? null;
            $valoracionToSave = $existingPivot ? $existingPivot->valoracion : null; // Valoración por defecto: la que ya tenía o null

            // Si el estado es 'favoritos', forzar valoración a 5
            if ($request->estado === 'favoritos') {
                $valoracionToSave = 5;
            }
            // Si el libro ya estaba en 'leido' y ahora se cambia a otra cosa que no es 'favoritos',
            // la valoración se mantiene si no se sobrescribe.
            // Si quieres que se borre si ya no es 'leido' o 'favoritos', la lógica sería diferente.
            // Para este caso, mantenemos la que tenía a menos que se marque como 'favoritos'.

            $user->libros()->syncWithoutDetaching([
                $libro->id => [
                    'estado' => $request->estado,
                    'valoracion' => $valoracionToSave,
                    // No necesitas 'created_at' ni 'updated_at' aquí, Eloquent los gestiona automáticamente
                ]
            ]);
            // ***************************************************************

            // Disparar evento SIEMPRE DESPUÉS de que la base de datos se haya actualizado correctamente.
            if ($request->estado === 'leido') {
                Log::info("DEBUG: Disparando evento LibroLeido para usuario ID: {$user->id} y libro ID: {$libro->id}");
                event(new LibroLeido($user, $libro));
                Log::info("DEBUG: Evento LibroLeido disparado.");
            }

            return back()->with('success', 'Libro añadido a tu lista correctamente');
        } catch (\Exception $e) {
            Log::error("Error en addToList: " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());
            return back()->with('error', 'Error al añadir el libro a tu lista: ' . $e->getMessage());
        }
    }

    // Función auxiliar para extraer ISBN si está en el ID
    private function extractIsbn(array $bookData)
    {
        $isbn = null;

        // Verifica si existen los identificadores industriales
        if (isset($bookData['volumeInfo']['industryIdentifiers'])) {
            // Busca ISBN en los identificadores
            foreach ($bookData['volumeInfo']['industryIdentifiers'] as $identifier) {
                if (
                    isset($identifier['type'], $identifier['identifier']) &&
                    in_array($identifier['type'], ['ISBN_13', 'ISBN_10'])
                ) {
                    $isbn = $identifier['identifier'];
                    break;
                }
            }
        }

        // Intenta extraer ISBN del ID de Google Books como respaldo si no se encontró antes
        if ($isbn === null && isset($bookData['id']) && is_string($bookData['id']) && preg_match('/\d{9,13}/', $bookData['id'], $matches)) {
            $isbn = $matches[0];
        }

        return $isbn;
    }

    public function recomendarLibro(Request $request)
    {
        $request->validate([
            'libro_id' => 'required|string',
            'titulo' => 'required|string',
            'amigo_id' => 'required|exists:users,id',
            'portada' => 'nullable|url',
            'mensaje' => 'nullable|string'
        ]);

        // Verificar que son amigos
        $sonAmigos = Amistad::where(function ($query) use ($request) {
            $query->where('user_id', Auth::id())
                ->where('amigo_id', $request->amigo_id)
                ->where('estado', 'aceptada');
        })
            ->orWhere(function ($query) use ($request) {
                $query->where('user_id', $request->amigo_id)
                    ->where('amigo_id', Auth::id())
                    ->where('estado', 'aceptada');
            })
            ->exists();

        if (!$sonAmigos) {
            return back()->with('error', 'Solo puedes recomendar libros a tus amigos');
        }

        // Crear o actualizar el libro en la base de datos
        $libro = Libro::firstOrCreate(
            ['google_id' => $request->libro_id],
            [
                'titulo' => $request->titulo,
                'urlPortada' => $request->portada ?? asset('images/default-cover.jpg')
            ]
        );

        // Mensaje por defecto si no se especifica
        $mensajeContenido = $request->mensaje ?? 'Te recomiendo este libro: ' . $request->titulo;

        // Crear notificación con el campo contenido
        Notificacion::create([
            'emisor_id' => Auth::id(),
            'receptor_id' => $request->amigo_id,
            'tipo' => Notificacion::TIPO_RECOMENDACION,
            'contenido' => $mensajeContenido, // Campo requerido
            'datos' => [
                'libro_id' => $libro->id,
                'titulo' => $request->titulo,
                'portada' => $request->portada,
                'mensaje' => $request->mensaje // Opcional para uso interno
            ],
            'estado' => 'pendiente'
        ]);

        return back()->with('success', 'Libro recomendado a tu amigo');
    }

    public function rate($libroId, Request $request)
    {
        $user = User::with('libros')->find(Auth::id());
        $libro = Libro::where('google_id', $libroId)->firstOrFail();

        $user->libros()->syncWithoutDetaching([
            $libro->id => [
                'valoracion' => $request->rating,
                'estado' => 'leido' // Asegurarse que está marcado como leído
            ]
        ]);

        return back()->with('success', 'Valoración guardada correctamente');
    }
}
