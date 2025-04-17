<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Bookly</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    </head>
    <body>
       
    <div class="boton col-12 d-flex justify-content-center">
            @if (Auth::check())
            <a href="{{ route('perfil') }}">
                <p class="hand-cursor">Entrar</p>
            </a>
            @else
            <a href="{{ route('login') }}">
                <p class="hand-cursor">Entrar</p>
            </a>
            @endif
        </div>

        

        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
    </body>
</html>
