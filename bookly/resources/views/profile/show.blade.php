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
                    <h1 class="text-xl">
                        Hola, {{ Auth::user()->name }}
                    </h1>
                </div>
            </div>
        </div>

        <!-- Reto anual -->
        <div>
            <h2>
                Reto de Lectura
            </h2>
            <div>
                <div class="flex justify-between mb-2">
                    <span>
                        {{ auth()->user()->librosLeidosEsteAnio() }} de {{ auth()->user()->retoAnual}} libros
                    </span>
                </div>
            </div>
        </div>

        <div>
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
            <div>
                <div>
                    <ul class="space-y-4">
                        <li class="transition hover:scale-[1.01]">
                            <a href="{{ route('logros') }}">
                                Logros
                            </a>
                        </li>
                        <li class="transition hover:scale-[1.01]">
                            <a href="{{ route('listas.show', 'leyendo') }}">
                                Leyendo actualmente
                            </a>
                        </li>
                        <li class="transition hover:scale-[1.01]">
                            <a href="{{ route('listas.show', 'leido') }}">
                                Últimas lecturas
                            </a>
                        </li>
                        <li class="transition hover:scale-[1.01]">
                            <a href="{{ route('listas.show', 'favoritos') }}">
                                Mis favoritos
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