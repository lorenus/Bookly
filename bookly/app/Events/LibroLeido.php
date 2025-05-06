<?php
namespace App\Events;

use App\Models\User;
use App\Models\Libro;
use Illuminate\Foundation\Events\Dispatchable;

class LibroLeido
{
    use Dispatchable;

    public $user;
    public $libro;

    public function __construct(User $user, Libro $libro)
    {
        $this->user = $user;
        $this->libro = $libro;
    }
}