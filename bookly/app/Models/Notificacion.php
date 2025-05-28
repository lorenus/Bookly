<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Libro; // Asegúrate de importar el modelo Libro

class Notificacion extends Model
{

    protected $table = 'notificaciones';

    // Tipos de notificación como constantes
    public const TIPO_AMISTAD = 'amistad';
    public const TIPO_PRESTAMO = 'prestamo';
    public const TIPO_RECOMENDACION = 'recomendacion';

    // Estados como constantes
    public const ESTADO_PENDIENTE = 'pendiente';
    public const ESTADO_ACEPTADA = 'aceptada';
    public const ESTADO_RECHAZADA = 'rechazada';
    public const ESTADO_LEIDA = 'leida';
    public const ESTADO_NO_LEIDA = 'no_leida';

    protected $fillable = [
        'emisor_id',
        'receptor_id',
        'tipo',
        'contenido',
        'datos',
        'estado',
    ];

    protected $casts = [
        'datos' => 'array', // Para almacenar JSON (ej: ID del libro, fecha límite)
    ];

    // Relaciones
    public function remitente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'emisor_id');
    }

    public function destinatario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receptor_id');
    }

    // Métodos para cambiar estado
    public function marcarComoAceptada()
    {
        $this->update(['estado' => self::ESTADO_ACEPTADA]);
    }

    public function marcarComoRechazada()
    {
        $this->update(['estado' => self::ESTADO_RECHAZADA]);
    }

    // Factory methods (para crear notificaciones)
    public static function crearSolicitudAmistad(int $emisorId, int $receptorId): self
    {
        return self::create([
            'emisor_id' => $emisorId,
            'receptor_id' => $receptorId,
            'tipo' => self::TIPO_AMISTAD,
            'contenido' => 'Te ha enviado una solicitud de amistad',
            'estado' => self::ESTADO_PENDIENTE,
            'datos' => null,
        ]);
    }

    public static function crearNotificacionPrestamo(int $emisorId, int $receptorId, Libro $libro, string $fechaLimite): self
    {
        $fechaAMostrar = (new \DateTime($fechaLimite))->format('d-m-Y');

        return self::create([
            'emisor_id' => $emisorId,
            'receptor_id' => $receptorId,
            'tipo' => self::TIPO_PRESTAMO,
            'contenido' => "Te han prestado el libro: {$libro->titulo}. Devuélvelo antes del {$fechaAMostrar}",
            'estado' => self::ESTADO_PENDIENTE,
            'datos' => [
                'libro_id' => $libro->id,
                'fecha_limite' => $fechaLimite,
            ],
        ]);
    }

    public static function crearRecomendacionLibro(int $emisorId, int $receptorId, Libro $libro): self
    {
        return self::create([
            'emisor_id' => $emisorId,
            'receptor_id' => $receptorId,
            'tipo' => self::TIPO_RECOMENDACION,
            'contenido' => "Te recomiendo el libro: {$libro->titulo}",
            'estado' => self::ESTADO_PENDIENTE,
            'datos' => [
                'libro_id' => $libro->id,
            ],
        ]);
    }

    public function marcarComoLeida()
    {
        $this->update(['leida' => true]);
    }

    public function scopeNoLeidas($query)
    {
        return $query->where('leida', false);
    }
}
