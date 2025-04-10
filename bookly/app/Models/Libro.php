<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $fillable = [
        'isbn',
        'titulo',
        'autor',
        'numPaginas',
        'sinopsis',
        'urlPortada'
    ];

    public static function rules(){
    return [
        'isbn' => 'required|unique:libros,isbn',
        'title' => 'required|string|max:255',
        ];
    } 
    
    public function usuarios(){
        return $this->belongsToMany(User::class, 'libros_usuario')
                    ->withPivot('estado', 'comprado', 'valoracion')
                    ->withTimestamps();
    }

    public function prestamos(){
        return $this->hasMany(Prestamo::class);
    }

    public function getValoracionMedia(){
        return $this->usuarios()->avg('valoracion');
    }

    public function estaEnListaDeUsuario($idUsuario){
        return $this->usuarios()->where('user_id', $idUsuario)->exists();
    }
}