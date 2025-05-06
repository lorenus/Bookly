<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logro extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'requisito', 'tipo'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('progreso', 'completado', 'completado_en');
    }
}
