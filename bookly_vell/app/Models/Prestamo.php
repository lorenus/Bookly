<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;

class Prestamo extends Model
{
    protected $fillable = [
        'libro_id',
        'propietario_id',
        'receptor_id',
        'fecha_limite',
        'devuelto'
    ]; 

    protected $casts = ['fecha_limite' => 'datetime'];

    public static function rules(): array{
        return [
            'libro_id' => 'required|exists:libros,id',
            'borrower_id' => 'required|exists:users,id',
            'fecha_devolucion' => 'required|date|after:today'
        ];
}

    public function libro(): BelongsTo{
        return $this->belongsTo(Libro::class);
    }

    public function prestador(): BelongsTo{
        return $this->belongsTo(User::class, 'propietario_id');
    }

    public function receptor(): BelongsTo{
        return $this->belongsTo(User::class, 'receptor_id');
    }

    public function estaActivo(): bool{
        return $this->devuelto;
    }

    public function estaRetrasado(): bool{
        return $this->fecha_limite->isPast() && $this->devuelto===false;
    }

    public function marcarDevuelto(){
        $this->update(['devuelto' => true ]);
    }

    // public function enviarRecordatorio(){
    //     if ($this->estaRetrasado()) {
    //         $this->prestatario->notify(new PrestamoRetrasado($this));
    //     }
    // }
}
