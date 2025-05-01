<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amistad extends Model{
    
    protected $table = 'amistades';

    protected $fillable = [
        'user_id', 
        'amigo_id', 
        'estado'
    ];

    public function usuario(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function amigo(){
        return $this->belongsTo(User::class, 'amigo_id');
    }
   
}