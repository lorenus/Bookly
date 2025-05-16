<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logro extends Model
{
    protected $fillable = ['codigo', 'nombre', 'descripcion', 'imagen'];
    
    public function users()
    {
        return $this->belongsToMany(User::class, 'logro_user')
                    ->withPivot('progreso', 'completado', 'completado_en')
                    ->withTimestamps();
    }
}