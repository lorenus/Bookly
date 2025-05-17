<?php

namespace App\Models;

use App\Models\Amistad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;

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
        return $this->belongsToMany(Libro::class, 'libros_usuario')
            ->withPivot(['estado', 'comprado', 'valoracion']);
    }

    public function librosLeidosEsteAnio()
    {
        return $this->libros()
            ->wherePivot('estado', 'leido')
            ->whereYear('libros_usuario.updated_at', now()->year)
            ->count();
    }


    public function logros()
    {
        return $this->belongsToMany(Logro::class, 'logro_user')
            ->withPivot('progreso', 'completado', 'completado_en')
            ->withTimestamps();
    }

    public function setImgPerfilAttribute($value)
    {
        Log::debug("Mutador setImgPerfil recibió: " . $value);

        // Limpiar caracteres no ASCII
        $cleanValue = preg_replace('/[^\x20-\x7E]/', '', $value);

        // Caso 1: Es una URL completa
        if (filter_var($cleanValue, FILTER_VALIDATE_URL)) {
            $path = parse_url($cleanValue, PHP_URL_PATH);
            $this->attributes['imgPerfil'] = 'profile-photos/' . basename($path);
        }
        // Caso 2: Contiene 'storage/' pero no es URL
        elseif (str_contains($cleanValue, 'storage/')) {
            $this->attributes['imgPerfil'] = 'profile-photos/' . basename($cleanValue);
        }
        // Caso 3: Ya está en formato correcto
        else {
            $this->attributes['imgPerfil'] = $cleanValue;
        }

        Log::debug("Mutador setImgPerfil guardará: " . $this->attributes['imgPerfil']);
    }

    /**
     * Accessor para generar URL completa al consultar
     */
    public function getImgPerfilAttribute($value)
    {
        // Valor vacío → imagen por defecto
        if (empty($value)) {
            return asset('storage/profile-photos/default.jpg');
        }

        // Si ya es URL completa, dejarla igual
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // Convertir ruta relativa a URL completa
        return asset('storage/' . $value);
    }

    public function testMutator()
    {
        return "¡Este método sí existe!";
    }

    public function notificacionesNoLeidas()
    {
        return $this->hasMany(\App\Models\Notificacion::class, 'receptor_id')
            ->where('leida', false);
    }

    public function tieneLibroEnLista($bookId, $listType)
    {
        return $this->libros()
            ->where('libros.google_id', $bookId)
            ->wherePivot('estado', $listType)
            ->exists();
    }

    public function haLeidoLibro($bookId)
    {
        return $this->libros()
            ->where('libros.google_id', $bookId)
            ->wherePivot('estado', 'leido')
            ->exists();
    }

    public function getValoracionLibro($bookId)
    {
        $libro = $this->libros()
            ->where('libros.google_id', $bookId)
            ->first();

        return $libro ? $libro->pivot->valoracion : null;
    }
}
