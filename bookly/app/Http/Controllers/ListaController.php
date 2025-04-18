<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use Illuminate\Support\Facades\Auth;

class ListaController extends Controller
{
    public function index()
    {
        return view('listas.index', [
            'listas' => [
                'leyendo' => 'Leyendo Actualmente',
                'leido' => 'Mis Últimas Lecturas',
                'favoritos' => 'Mis Favoritos'
            ]
        ]);
    }
    
    public function show($tipoLista)
    {
        $estados = [
            'leyendo' => 'leyendo',
            'leido' => 'leido',       
            'favoritos' => 'favoritos',
            'porLeer' => 'porLeer',
        ];

        if (!array_key_exists($tipoLista, $estados)) {
            abort(404);
        }

        $query = Libro::whereHas('usuarios', function($query) use ($tipoLista, $estados) {
            $query->where('user_id', Auth::id());
            
            if ($tipoLista === 'favoritos') {
                $query->where('valoracion', 5);
            } else {
                $query->where('estado', $estados[$tipoLista]);
            }
        });

        $libros = $query->get();

        $titulos = [
            'leyendo' => 'Leyendo actualmente',
            'leido' => 'Mis últimas lecturas',
            'porLeer' => 'Para leer',
            'favoritos' => 'Mis libros favoritos',
        ];

        return view('listas.show', [
            'libros' => $libros,
            'titulo' => $titulos[$tipoLista],
        ]);
    }
}