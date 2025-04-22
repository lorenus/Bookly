<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ListaController extends Controller {
    public function index() {
        return view('listas.index', [
            'listas' => [
                'leyendo' => 'Leyendo Actualmente',
                'leido' => 'Mis Últimas Lecturas',
                'favoritos' => 'Mis Favoritos'
            ]
        ]);
    }

    public function show($tipoLista) {
        $user = User::with('libros')->find(Auth::id());
        $estados = [
            'leyendo' => 'leyendo',
            'leido' => 'leido',
            'favoritos' => 'favoritos',
            'porLeer' => 'porLeer',
        ];

        if (!array_key_exists($tipoLista, $estados)) {
            abort(404);
        }

        $libros = $user->libros()
            ->when($tipoLista === 'favoritos', function ($query) {
                return $query->where('libros_usuario.valoracion', 5);
            }, function ($query) use ($tipoLista, $estados) {
                return $query->where('libros_usuario.estado', $estados[$tipoLista]);
            })
            ->withPivot(['estado', 'valoracion', 'comprado'])
            ->get();

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

    public function biblioteca() {
        $user = User::with('libros')->find(Auth::id());

        $libros = $user->libros()
            ->where('libros_usuario.comprado', true)
            ->withPivot(['estado', 'valoracion', 'comprado'])
            ->get();

        return view('listas.show', [
            'libros' => $libros,
            'titulo' => 'Mi Biblioteca'
        ]);
    }
}
