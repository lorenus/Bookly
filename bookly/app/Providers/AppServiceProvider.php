<?php

namespace App\Providers;

use App\Events\LibroLeido;
use App\Listeners\CheckLogrosListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\View\Components\BookCover;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {

        Event::listen(
            LibroLeido::class,
            CheckLogrosListener::class
        );

        // Configuración para desarrollo local
        if ($this->app->environment('local')) {
            Http::macro('insecure', function () {
                return Http::withoutVerifying();
            });
        } else {
            Http::macro('insecure', function () {
                return Http::this(); 
            });
        }
    }
}
