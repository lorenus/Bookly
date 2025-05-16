<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Estilos -->
    @vite(['resources/css/app-v2.css', 'resources/js/app.js'])
    @stack('styles')

    <!-- jQuery (necesario para Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body class="font-sans antialiased">

    <header style="position: fixed; top: 0; left: 0; width: 100%; z-index: 1000;">
        <a href="{{ route('perfil') }}" class="volver-btn" style="position: absolute; top: 20px; left: 40px; z-index: 10;">
            <img src="{{ asset('img/elementos/bookly.png') }}" alt="Volver" style="width: 200px;">
        </a>
        @auth
        <div style="position: absolute; top: 15px; right: 50px; display: flex; align-items: center; gap: 15px;">
            @auth
            @php
            $unreadCount = auth()->user()->notificacionesNoLeidas()->count();
            @endphp
            @if($unreadCount > 0)
            <span class="notification-badge">
                <img src="{{ asset('img/elementos/notificacio.png') }}" alt="Notificaciones" style="width: 25px;">
            </span>
            @endif
            @endauth
            <x-hamburguer-menu :unreadCount="auth()->user()->notificacionesNoLeidas()->count()" />

        </div>
        @endauth
    </header>

    <main style="padding-top: 80px;"> @yield('content')
    </main>


    @stack('scripts')
</body>

</html>