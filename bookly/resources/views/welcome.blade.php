<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Bookly</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="{{ asset('css/welcome.css') }}" rel="stylesheet">
</head>

<body class="position-relative" style="overflow-x: hidden;">

    <div class="position-absolute top-0 start-0">
        <img src="{{ asset('img/paper_rosa.png') }}" alt="Decoración" class="img-fluid">
    </div>
    <div class="position-absolute top-0 end-0">
        <img src="{{ asset('img/estrella_tela.png') }}" alt="Decoración" class="img-fluid">
    </div>

    <div class="container d-flex flex-column justify-content-center align-items-center position-relative">

        <div class="d-flex justify-content-center w-70 mb-4 z-1">
            <img src="{{ asset('img/bookly.png') }}" alt="Logo" class="img-fluid">
        </div>

        <div class="position-absolute fondo-boton">
            <img src="{{ asset('img/fondo.png') }}" alt="Fondo">
        </div>

        <div class="d-flex justify-content-center z-1 mb-5">
            @if (Auth::check())
            <a href="{{ route('perfil') }}" class="d-flex justify-content-center">
                <img src="{{ asset('img/boton_entrar.png') }}" alt="Entrar" class="img-fluid">
            </a>
            @else
            <a href="{{ route('login') }}" class="d-flex justify-content-center">
                <img src="{{ asset('img/boton_entrar.png') }}" alt="Entrar" class="img-fluid">
            </a>
            @endif
        </div>

        <div class="d-flex justify-content-center mt-5 pt-5 z-1">
            <img style="width: 70%;" src="{{ asset('img/que_es.png') }}" alt="Imagen 1" class="img-fluid">
        </div>
        <div class="d-flex justify-content-center z-1" style="width: 80%;">
            <img src="{{ asset('img/flecha 1.png') }}" alt="Imagen 2" class="img-fluid">
        </div>
        <div class="d-flex justify-content-center z-1" style="width: 80%;">
            <img src="{{ asset('img/pegatina_rosa.png') }}" alt="Imagen 2" class="img-fluid">
        </div>
        <div class="d-flex justify-content-center z-1" style="width: 80%;">
            <img src="{{ asset('img/flecha 2.png') }}" alt="Imagen 2" class="img-fluid">
        </div>
        <div class="d-flex justify-content-center z-1" style="width: 80%;">
            <img src="{{ asset('img/corcho.png') }}" alt="Imagen 1" class="img-fluid">
        </div>
        <div class="d-flex justify-content-center z-1" style="width: 80%;">
            <img src="{{ asset('img/flecha 3.png') }}" alt="Imagen 2" class="img-fluid">
        </div>
        <div class="d-flex justify-content-center z-1" style="bottom: 20%; margin-left: -10%; margin-top: -10%;">
            <img src="{{ asset('img/etiqueta.png') }}" alt="Etiqueta" class="img-fluid" style="width: 70%;">
        </div>

        <div class="footer z-1">
            <a href="#">Términos y Condiciones</a>
            <a href="#">Política de Privacidad</a>
            <a href="#">Contacto</a>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>