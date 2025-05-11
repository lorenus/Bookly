@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row g-0"> 
        <!-- Columna Corcho -->
        <div class="col-md-6 corcho-container">
            <div class="corcho-content"> <!-- Contenedor para elementos dentro del corcho -->
                <!-- Foto de perfil -->
                <div class="profile-section">
                    <div class="flex-shrink-0">
                        <!-- <img src="{{ Storage::url($user->imgPerfil) }}?v={{ time() }}"> -->
                    </div>
                    <div>
                        <h1 class="text-xl">
                            Hola, {{ Auth::user()->name }}
                        </h1>
                    </div>
                </div>

                <div class="logros-section">
                    <h2>Mis logros</h2>
                    <a href="{{ route('logros.index') }}">
                </div>

                <a href="{{ route('logros.index') }}">Logros</a>
                <!-- Reto anual -->
                <div class="challenge-section">
                    <h2>Reto de Lectura</h2>
                    <div>
                        <div class="flex justify-between mb-2">
                            <span>
                                {{ auth()->user()->librosLeidosEsteAnio() }} de {{ auth()->user()->retoAnual}} libros
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Columna Libreta -->
        <div class="col-md-6 notebook-container">
            <div class="notebook-content"> <!-- Contenedor para elementos dentro de la libreta -->
                <div class="search-section">
                    <input type="text" id="basic-book-search" placeholder="Buscar libros..." class="w-full p-2 border rounded">
                    <div id="basic-results" class="mt-2 hidden"></div>
                </div>

                <div class="menu-section">
                    <ul>
                        <li><a href="{{ route('listas.show', 'leyendo') }}">Leyendo actualmente</a></li>
                        <li><a href="{{ route('listas.show', 'leido') }}">Últimas lecturas</a></li>
                        <li><a href="{{ route('listas.show', 'favoritos') }}">Mis favoritos</a></li>
                    </ul>
                </div>
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