<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bookly</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/welcome.css') }}" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100">
    <!-- Elementos decorativos fijos -->
    <div class="position-absolute top-0 start-0 decorativa paper-rosa">
        <img src="{{ asset('img/home/paper_rosa.png') }}" alt="Decoración" class="img-fluid">
    </div>
    <div class="position-absolute top-0 end-0 decorativa estrella-tela">
        <img src="{{ asset('img/home/estrella_tela.png') }}" alt="Decoración" class="img-fluid">
    </div>

    <!-- Contenido principal -->
    <main class="flex-shrink-0 position-relative">
        <!-- Fondo principal (detrás del logo y botón) -->
        <div class="background-wrapper fondo">
            <img src="{{ asset('img/home/fondo.png') }}" alt="Fondo principal" class="background-image">
        </div>

        <div class="container position-relative z-4 pt-5" style="margin-top: 10%;">
            <!-- Logo -->
            <div class="text-center mb-4">
                <img src="{{ asset('img/elementos/bookly.png') }}" alt="Logo" class="img-fluid">
            </div>

            <!-- Botón de entrar -->
            <div class="text-center entrar">
                @if (Auth::check())
                    <a href="{{ route('perfil') }}">
                        <img src="{{ asset('img/home/boton_entrar.png') }}" alt="Entrar" class="img-fluid">
                    </a>
                @else
                    <a href="{{ route('login') }}">
                        <img src="{{ asset('img/home/boton_entrar.png') }}" alt="Entrar" class="img-fluid">
                    </a>
                @endif
            </div>
        </div>

        <!-- Secciones de contenido -->
        <div class="container position-relative z-4">
            <div class="text-center my-5 que-es mx-auto" style="width: 70%;">
                <img src="{{ asset('img/home/que_es.png') }}" alt="¿Qué es Bookly?" class="img-fluid">
            </div>

            <div class="text-center my-4 mx-auto" style="width: 80%;">
                <img src="{{ asset('img/home/flecha 1.png') }}" alt="Flecha" class="img-fluid">
            </div>

            <div class="text-center my-4 mx-auto pegatina" style="width: 80%;">
                <img src="{{ asset('img/home/pegatina_rosa.png') }}" alt="Pegatina" class="img-fluid">
            </div>
        </div>

        <!-- Fondo estrellas rosas (detrás de las siguientes secciones) -->
        <div class="background-wrapper rosa">
            <img src="{{ asset('img/home/estrelles_rosa.png') }}" alt="Estrellas rosas" class="background-image">
        </div>

        <div class="container position-relative z-4">
            <div class="text-center my-4 mx-auto" style="width: 80%;">
                <img src="{{ asset('img/home/flecha 2.png') }}" alt="Flecha" class="img-fluid">
            </div>

            <div class="text-center my-4 corcho">
                <img src="{{ asset('img/home/corcho.png') }}" alt="Corcho" class="img-fluid w-100">
            </div>
        </div>

        <!-- Fondo estrellas azules (detrás del corcho y footer) -->
        <div class="background-wrapper blau">
            <img src="{{ asset('img/home/estrelles_blau.png') }}" alt="Estrellas azules" class="background-image">
        </div>

        <div class="container position-relative z-4">
            <div class="text-center my-4 mx-auto" style="width: 80%;">
                <img src="{{ asset('img/home/flecha 3.png') }}" alt="Flecha" class="img-fluid">
            </div>

            <!-- Contenedor de etiqueta y cohete -->
            <div class="d-flex justify-content-between align-items-end mt-4 mb-5 position-relative">
                <div class="etiqueta" style="width: 80%; margin-left: -5%;">
                    <img src="{{ asset('img/home/etiqueta.png') }}" alt="Etiqueta" class="img-fluid">
                </div>
                <div class="cohete-container">
                    <span class="mb-1 fs-4 d-block text-center">¡Súbeme!</span>
                    <a href="#">
                        <img src="{{ asset('img/home/cohete.png') }}" alt="Volver arriba" class="img-fluid">
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="mt-auto position-relative z-3">
        <div class="w-100">
            <img src="{{ asset('img/home/fondo_footer.png') }}" alt="Fondo footer" class="w-100">
        </div>
        
        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center">
            <div class="container">
                <div class="text-center mb-3">
                    <p class="m-0">© 2025 Bookly</p>
                </div>

                <div class="row justify-content-between px-3">
                    <div class="col-md-6 text-start">
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-decoration-none">Cookies</a></li>
                            <li><a href="#" class="text-decoration-none">Términos y condiciones</a></li>
                            <li><a href="#" class="text-decoration-none">Política de privacidad</a></li>
                        </ul>
                    </div>

                    <div class="col-md-6 text-end">
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-decoration-none">Instagram</a></li>
                            <li><a href="#" class="text-decoration-none">Twitter</a></li>
                            <li><a href="#" class="text-decoration-none">YouTube</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>