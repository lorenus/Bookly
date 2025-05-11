<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class Libro extends Model
{
    protected $fillable = [
        'google_id',
        'titulo',
        'autor',
        'sinopsis',
        'urlPortada',
        'isbn',
        'numPaginas'
    ];

    public static function rules()
    {
        return [
            'isbn' => 'required|unique:libros,isbn',
            'title' => 'required|string|max:255',
        ];
    }

    public static function buscarEnCache($query)
    {
        return self::where('titulo', 'LIKE', "%{$query}%")
            ->orWhere('autor', 'LIKE', "%{$query}%")
            ->orWhere('isbn', $query)
            ->get();
    }

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'libros_usuario')
            ->withPivot('estado', 'comprado', 'valoracion')
            ->withTimestamps();
    }

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class);
    }

    public function getValoracionMedia()
    {
        return $this->usuarios()->avg('valoracion');
    }

    public function estaEnListaDeUsuario($idUsuario)
    {
        return $this->usuarios()->where('user_id', $idUsuario)->exists();
    }

    public function esClasico()
    {
        // Libros publicados antes de 1900)
        if (isset($this->anyo_publicacion)) {
            return $this->anyo_publicacion < 1900;
        }
    }

    public function getPortadaSegura()
    {
        // 1. Si tiene URL de Google/Amazon
        if (!empty($this->urlPortada) && filter_var($this->urlPortada, FILTER_VALIDATE_URL)) {
            return $this->urlPortada; // Usa la URL directamente
        }

        // 2. Si tiene google_id pero no urlPortada
        if ($this->google_id) {
            return "https://covers.openlibrary.org/b/olid/{$this->google_id}-M.jpg";
        }

        // 3. Fallback a imagen local por defecto
        return asset('img/elementos/portada_default.png');
    }
}
