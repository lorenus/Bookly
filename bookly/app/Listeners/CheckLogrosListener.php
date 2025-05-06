<?php

namespace App\Listeners;

use App\Events\LibroLeido;
use App\Services\LogroService;

class CheckLogrosListener
{
    public function handle($event)
    {
        $user = $event->user;
        
        if ($event instanceof LibroLeido) {
            LogroService::checkLogros($user, 'libros_leidos');
            
            if ($event->libro->esClasico()) {
                LogroService::checkLogros($user, 'clasicos_leidos');
            }
        }
    }
}
