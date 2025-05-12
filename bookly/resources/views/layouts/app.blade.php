<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilos -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles') <!-- Aquí se cargarán los estilos del componente -->
</head>

<body class="font-sans antialiased">
    <!-- Barra superior fija -->
    <header class="d-flex justify-content-between align-items-center" style="padding: 20px;">
        <!-- Logo (izquierda) -->
        <a href="{{ route('perfil') }}" class="flex items-center">
            <img src="{{ asset('img/elementos/bookly.png') }}" alt="Logo" class="h-10" style="width: 200px;">
        </a>

        <!-- Componente del menú hamburguesa -->
        <x-hamburguer-menu />
    </header>

    <!-- Contenido principal (con margen para la barra superior) -->
    <main style="padding-top: 80px;"> <!-- Añade padding para no solapar con el header -->
        @yield('content')
    </main>

    @stack('scripts') <!-- Aquí se cargarán los scripts del componente -->
</body>
</html>