<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bookly') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css'])
</head>

<body>
    <div class="flex bg-white p-4">
        <a href="/">
            <img src="{{ asset('img/bookly.png') }}" alt="Logo" style="width: 250px">
        </a>
    </div>

    <main>
        {{ $slot }}
    </main>

    @vite(['resources/js/app.js'])
</body>

</html>