<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
