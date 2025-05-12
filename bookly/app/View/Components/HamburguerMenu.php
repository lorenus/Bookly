<?php

namespace App\View\Components;

use Illuminate\View\Component;

class HamburguerMenu extends Component
{
    public $menuItems;
    
    public function __construct()
    {
        $this->menuItems = [
            ['url' => route('perfil'), 'text' => 'Mi perfil'],
            ['url' => route('listas.index'), 'text' => 'Mis listas'],
            ['url' => route('amigos'), 'text' => 'Mis amigos'],
           // ['url' => route('about'), 'text' => 'Prestar libro'],
            ['url' => route('mensajes.index'), 'text' => 'Mensajes'],
            ['url' => route('profile.edit'), 'text' => 'Editar perfil'],
            ['url' => route('logout'), 'text' => 'Cerrar sesión'],
        ];
    }

    public function render()
    {
        return view('components.hamburguer-menu');
    }
}