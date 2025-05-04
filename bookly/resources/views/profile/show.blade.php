@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Sección de bienvenida y perfil -->
        <div>
            <div>
                <!-- Foto de perfil -->
                <div class="flex-shrink-0">
                    <img src="{{ $user->imgPerfil ? asset('storage/'.$user->imgPerfil).'?v='.request()->get('v', time()) : asset('images/default-user.jpg') }}"
                        class="h-16 w-16 rounded-full object-cover">
                </div>
                <div>
                    <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Hola, {{ Auth::user()->name }}
                    </h1>
                </div>
            </div>
        </div>

        <!-- Reto anual -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">
                📅 Reto Anual de Lectura
            </h2>
            <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4">
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Progreso
                    </span>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ auth()->user()->librosLeidosEsteAnio() }} de {{ auth()->user()->retoAnual}} libros
                    </span>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="mb-8">
                <!-- Barra de búsqueda simplificada -->
                <input
                    type="text"
                    id="basic-book-search"
                    placeholder="Buscar libros..."
                    class="w-full p-2 border rounded">
                <!-- Contenedor de resultados -->
                <div id="basic-results" class="mt-2 hidden"></div>
            </div>

            <!-- Contenido existente -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <ul class="space-y-4">
                        <li class="transition hover:scale-[1.01]">
                            <a href="{{ route('logros') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg class="w-5 h-5 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="font-medium">{{ __('Logros') }}</span>
                            </a>
                        </li>
                        <li class="transition hover:scale-[1.01]">
                            <a href="{{ route('listas.show', 'leyendo') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg class="w-5 h-5 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <span class="font-medium">{{ __('Leyendo Actualmente') }}</span>
                            </a>
                        </li>
                        <li class="transition hover:scale-[1.01]">
                            <a href="{{ route('listas.show', 'leido') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg class="w-5 h-5 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                </svg>
                                <span class="font-medium">{{ __('Mis Últimas Lecturas') }}</span>
                            </a>
                        </li>
                        <li class="transition hover:scale-[1.01]">
                            <a href="{{ route('listas.show', 'favoritos') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg class="w-5 h-5 mr-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                </svg>
                                <span class="font-medium">{{ __('Mis favoritos') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('basic-book-search').addEventListener('input', function(e) {
            const query = e.target.value.trim();
            const resultsDiv = document.getElementById('basic-results');

            if (query.length < 2) {
                resultsDiv.innerHTML = '';
                resultsDiv.classList.add('hidden');
                return;
            }

            // Búsqueda directa sin loader ni efectos
            fetch(`/buscar-libros?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(books => {
                    if (!books || books.length === 0) {
                        resultsDiv.innerHTML = '<p>No se encontraron libros</p>';
                        resultsDiv.classList.remove('hidden');
                        return;
                    }

                    let html = '';
                    books.forEach(book => {
                        html += `
                    <div class="p-2 border-b">
                        <a href="/libros/${book.id}" class="text-blue-600">
                            ${book.volumeInfo.title}
                        </a>
                    </div>
                `;
                    });

                    resultsDiv.innerHTML = html;
                    resultsDiv.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    resultsDiv.innerHTML = '<p class="text-red-500">Error al buscar</p>';
                    resultsDiv.classList.remove('hidden');
                });
        });
    </script>


    @endsection