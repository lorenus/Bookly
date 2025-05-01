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

    public function marcarComoComprado($libroId)
    {
        try {
            $userId = Auth::id();

            $exists = DB::table('libros_usuario')
                ->where('user_id', $userId)
                ->where('libro_id', $libroId)
                ->exists();

            if ($exists) {
                DB::table('libros_usuario')
                    ->where('user_id', $userId)
                    ->where('libro_id', $libroId)
                    ->update(['comprado' => true]);
            } else {
                DB::table('libros_usuario')->insert([
                    'user_id' => $userId,
                    'libro_id' => $libroId,
                    'comprado' => true,
                    'estado' => 'porLeer',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            return back()->with('success', 'Libro añadido a tu biblioteca');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
