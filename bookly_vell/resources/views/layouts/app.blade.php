<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="font-sans antialiased">

    <!-- Contenedor Alpine -->
    <div x-data="{ open: false }">
        <!-- Botón hamburguesa -->
        <button @click="open = true"
                class="fixed top-4 right-4 z-50 p-2 rounded-md focus:outline-none hover:bg-gray-200 dark:hover:bg-gray-700 transition"
                aria-label="Abrir menú">
            <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Componente del menú -->
        <x-menu />

        <!-- Contenido principal -->
        <div class="min-h-screen">
            <nav class="bg-white p-4">
                <div class="flex justify-between items-center">
                    <div class="text-xl font-semibold">Bookly</div>
                </div>
            </nav>

            @isset($header)
                <header class="bg-white">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>
