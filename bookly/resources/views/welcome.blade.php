<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Bookly</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilo adicional para el fondo */
        .fondo-boton {
            left: 0;
            width: 100vw;
            /* Ocupa todo el ancho de la pantalla */
            max-width: 100%;
        }
    </style>
</head>

<body class="position-relative" style="overflow-x: hidden;"> <!-- Eliminamos el container del body -->

    <!-- Decoraciones esquinas -->
    <div class="position-absolute top-0 start-0">
        <img src="{{ asset('img/paper_rosa.png') }}" alt="Decoración" class="img-fluid">
    </div>
    <div class="position-absolute top-0 end-0">
        <img src="{{ asset('img/estrella_tela.png') }}" alt="Decoración" class="img-fluid">
    </div>

    <!-- Contenedor principal -->
    <div class="container d-flex flex-column justify-content-center align-items-center position-relative" style="padding-top: 15%;"> <!-- Añadido padding-top -->

        <!-- Logo -->
        <div class="mb-4 z-1"> <!-- Corregido mtmb-4 a mb-4 -->
            <img src="{{ asset('img/bookly.png') }}" alt="Logo" class="img-fluid">
        </div>

        <!-- Fondo entre logo y botón -->
        <div class="position-absolute fondo-boton" style="top: 15%; z-index: 0; width: 100vw;">
            <img src="{{ asset('img/fondo.png') }}" alt="Fondo">
        </div>

        <!-- Botón Entrar -->
        <div class="boton z-1 mb-5">
            @if (Auth::check())
            <a href="{{ route('perfil') }}">
                <img src="{{ asset('img/boton_entrar.png') }}" alt="Entrar" class="img-fluid">
            </a>
            @else
            <a href="{{ route('login') }}">
                <img src="{{ asset('img/boton_entrar.png') }}" alt="Entrar" class="img-fluid">
            </a>
            @endif
        </div>

        <!-- Qué es -->
        <div class="d-flex justify-content-center mt-5 pt-5 z-1">
            <div>
                <img src="{{ asset('img/que_es.png') }}" alt="Imagen 1" class="img-fluid">
            </div>
        </div>
        <div class="d-flex justify-content-center z-1">
            <div>
                <img src="{{ asset('img/flecha 1.png') }}" alt="Imagen 2" class="img-fluid">
            </div>
        </div>
        <div class="d-flex justify-content-center z-1">
            <div>
                <img src="{{ asset('img/pegatina_rosa.png') }}" alt="Imagen 2" class="img-fluid">
            </div>
        </div>
        <div class="d-flex justify-content-center z-1">
            <div>
                <img src="{{ asset('img/flecha 2.png') }}" alt="Imagen 2" class="img-fluid">
            </div>
        </div>
        <div class="d-flex justify-content-center z-1">
            <div>
                <img src="{{ asset('img/corcho.png') }}" alt="Imagen 1" class="img-fluid">
            </div>
        </div>
        
    


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>