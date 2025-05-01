<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;

class Notificacion extends Model {
    
    protected $table = 'notificaciones';

    protected $fillable = [
        'emisor_id',
        'receptor_id',
        'tipo',
        'contenido',
        'datos',
        'estado',
    ];

    protected $casts = ['datos' => 'array'];

    public function remitente(): BelongsTo{
        return $this->belongsTo(User::class, 'emisor_id');
    }

    public function destinatario(): BelongsTo{
        return $this->belongsTo(User::class, 'receptor_id');
    }

    public function marcarComoLeida(){
        $this->update(['estado' => 'leida']);
    }

    public function aceptar(){
        $this->update(['estado' => 'aceptada']);
    }

    public function rechazar(){
        $this->update(['estado' => 'rechazada']);
    }

    public static function crearNotificacionRecomendacion($emisorId, $receptorId, $libroId, $libroTitulo){
        return self::create([
            'emisor_id' => $emisorId,
            'receptor_id' => $receptorId,
            'tipo' => 'recomendacion',
            'contenido' => "Te recomiendo el libro: {$libroTitulo}",
            'data' => [
                'libro_id' => $libroId,
                'libro_titulo' => $libroTitulo
            ]
        ]);
    }

    public static function crearNotificacionAmistad($emisorId, $receptorId){
        return self::create([
            'emisor_id' => $emisorId,
            'receptor_id' => $receptorId,
            'tipo' => 'amistad',
            'contenido' => '¡Te ha enviado una solicitud de amistad!',
            'datos' => null,
            'estado' => 'pendiente'
        ]); 
    }

    public static function crearNotificacionPrestamo($emisorId, $receptorId, $libroId, $fechaLimite){
        $libro = Libro::find($libroId);
    
            return self::create([
                'emisor_id' => $emisorId,
                'receptor_id' => $receptorId,
                'tipo' => 'prestamo',
                'contenido' => "Te han prestado el libro: {$libro->title}. Devuélvelo antes del {$fechaLimite}",
                'datos' => [
                    'libro_id' => $libroId,
                    'libro_titulo' => $libro->title,
                    'fecha_limite' => $fechaLimite
                ],
                'estado' => 'pendiente'
            ]);
    }
}
