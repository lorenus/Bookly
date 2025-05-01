<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

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
        // Configuración para desarrollo local
        if ($this->app->environment('local')) {
            Http::macro('insecure', function() {
                return Http::withoutVerifying();
            });
        } else {
            Http::macro('insecure', function() {
                return Http::this(); // Versión segura para producción
            });
        }
    }
}
