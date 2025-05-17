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

<body class="position-relative" style="overflow-x: hidden; max-height: 45vh;">

    <div class="background-stack z-1">
        <div class="background-section">
            <img src="{{ asset('img/home/fondo.png') }}" alt="Fondo principal" class="bg-image">
        </div>
        <div class="background-section">
            <img src="{{ asset('img/home/estrelles_rosa.png') }}" alt="Estrellas rosas" class="bg-image">
        </div>
        <div class="background-section limited-height">
            <img src="{{ asset('img/home/estrelles_blau.png') }}" alt="Estrellas azules" class="bg-image">
        </div>
    </div>

    <div class="position-absolute top-0 start-0 decorativa">
        <img src="{{ asset('img/home/paper_rosa.png') }}" alt="Decoración" class="img-fluid decorativa-img">
    </div>
    <div class="position-absolute top-0 end-0 decorativa">
        <img src="{{ asset('img/home/estrella_tela.png') }}" alt="Decoración" class="img-fluid decorativa-img">
    </div>


    <div class="container d-flex flex-column justify-content-center align-items-center position-relative">

        <div class="d-flex justify-content-center w-70 mb-4 z-1">
            <img src="{{ asset('img/elementos/bookly.png') }}" alt="Logo" class="img-fluid">
        </div>



        <div class="d-flex justify-content-center z-1">
            @if (Auth::check())
            <a href="{{ route('perfil') }}" class="d-flex justify-content-center">
                <img src="{{ asset('img/home/boton_entrar.png') }}" alt="Entrar" class="img-fluid">
            </a>
            @else
            <a href="{{ route('login') }}" class="d-flex justify-content-center">
                <img src="{{ asset('img/home/boton_entrar.png') }}" alt="Entrar" class="img-fluid">
            </a>
            @endif
        </div>

        <div class="d-flex justify-content-center mt-10 pt-5 z-1 custom-spacing">
            <img style="width: 70%;" src="{{ asset('img/home/que_es.png') }}" alt="1" class="img-fluid">
        </div>

        <div class="d-flex justify-content-center z-1" style="width: 80%;">
            <img src="{{ asset('img/home/flecha 1.png') }}" alt="flecha" class="img-fluid">
        </div>

        <div class="d-flex justify-content-center z-1" style="width: 80%;">
            <img src="{{ asset('img/home/pegatina_rosa.png') }}" alt="pegatina rosa" class="img-fluid">
        </div>

        <div class="d-flex justify-content-center z-1" style="width: 80%;">
            <img src="{{ asset('img/home/flecha 2.png') }}" alt="flecha" class="img-fluid">
        </div>

        <div class="d-flex justify-content-center z-1" style="width: 80%;">
            <img src="{{ asset('img/home/corcho.png') }}" alt="corcho" class="img-fluid">
        </div>

        <div class="d-flex justify-content-center z-1" style="width: 80%;">
            <img src="{{ asset('img/home/flecha 3.png') }}" alt="flecha" class="img-fluid">
        </div>

        <div class="justify-content-center z-1" style="bottom: 20%; margin-left: -10%; margin-top: -5%;">
            <img src="{{ asset('img/home/etiqueta.png') }}" alt="Etiqueta" class="img-fluid" style="width: 70%;">
        </div>

        <div class="position-absolute z-1 d-flex flex-column align-items-center" style="right: 2%; bottom: 0;">
            <span class="mb-1 fs-2">¡Súbeme!</span>
            <a href="#">
                <img src="{{ asset('img/home/cohete.png') }}" alt="Volver arriba" class="img-fluid">
            </a>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer-container position-relative mt-5">
        <!-- Contenedor de la imagen de fondo -->
        <div class="footer-bg-container">
            <img src="{{ asset('img/home/fondo_footer.png') }}" alt="Fondo footer" class="footer-bg-img w-100 h-auto">
        </div>

        <!-- Contenido del footer superpuesto -->
        <div class="footer-content position-absolute w-100 h-100 top-0 start-0 d-flex flex-column justify-content-center">
            <div class="container">
                <!-- Copyright centrado -->
                <div class="text-center mb-4">
                    <p class="text-black m-0">© 2025 Bookly</p>
                </div>

                <!-- Columnas de enlaces -->
                <div class="row">
                    <!-- Columna izquierda -->
                    <div class="ps-5 col-md-6">
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-black text-decoration-none">Cookies</a></li>
                            <li><a href="#" class="text-black text-decoration-none">Términos y condiciones</a></li>
                            <li><a href="#" class="text-black text-decoration-none">Política de privacidad</a></li>
                        </ul>
                    </div>

                    <!-- Columna derecha -->
                    <div class="pe-5 col-md-6">
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-black text-decoration-none">Instagram</a></li>
                            <li><a href="#" class="text-black text-decoration-none">Twitter</a></li>
                            <li><a href="#" class="text-black text-decoration-none">YouTube</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
