<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use Illuminate\Support\Facades\Auth;

class ListaController extends Controller
{
    public function show($tipoLista)
    {
        logger("-------------------");
        logger("Solicitud recibida para lista: " . $tipoLista);
        logger("Usuario autenticado: " . (Auth::check() ? Auth::id() : 'NO'));

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