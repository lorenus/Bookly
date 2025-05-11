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
</head>

<body class="font-sans antialiased">
    <!-- Barra superior fija -->
    <header>
        <!-- Logo (izquierda) -->
        <a href="/" class="flex items-center">
            <img src="{{ asset('img/elementos/bookly.png') }}" alt="Logo" class="h-10" style="width: 250px;">
        </a>
    </header>

    <!-- Contenido principal (con margen para la barra superior) -->
    <main>
        @yield('content')
    </main>
</body>

</html>