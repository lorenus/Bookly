<?php

namespace App\Models;

use App\Models\Amistad;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'apellidos',
        'email',
        'password',
        'password_verified_at',
        'imgPerfil',
        'retoAnual'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function prestamosComoPropietario()
    {
        return $this->hasMany(Prestamo::class, 'propietario_id');
    }

    public function prestamosComoReceptor()
    {
        return $this->hasMany(Prestamo::class, 'receptor_id');
    }

    public function notificacionesEnviadas()
    {
        return $this->hasMany(Notificacion::class, 'emisor_id');
    }

    public function notificacionesRecibidas()
    {
        return $this->hasMany(Notificacion::class, 'receptor_id');
    }

    public function amistadesComoUsuario()
    {
        return $this->hasMany(Amistad::class, 'user_id');
    }

    public function amistadesComoAmigo()
    {
        return $this->hasMany(Amistad::class, 'amigo_id');
    }

    public function amigos()
    {
        return $this->belongsToMany(User::class, 'amistades', 'user_id', 'amigo_id')
            ->wherePivot('estado', 'aceptada');
    }

    public function libros()
    {
        // Verificación explícita de la relación
        if (!method_exists($this, 'belongsToMany')) {
            throw new \RuntimeException('El método belongsToMany no está disponible');
        }

        $relation = $this->belongsToMany(Libro::class, 'libros_usuario')
            ->withPivot(['estado', 'comprado', 'valoracion'])
            ->withTimestamps();

        if (!$relation instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany) {
            throw new \RuntimeException('La relación no se creó correctamente');
        }

        return $relation;
    }
}
