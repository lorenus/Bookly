@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row g-0">
        <!-- Columna Corcho -->
        <!-- Columna Corcho -->
        <div class="col-md-6 corcho-container position-relative"> <!-- Añadido position-relative -->
            <!-- Div superpuesto en borde superior -->
            <div class="welcome-banner d-flex flex-column align-items-center justify-content-center">
                <div style="margin-left: 25%; margin-top: -7%; transform: rotate(-9deg);"> <h2 class="m-0">¡Hola, <br>{{ Auth::user()->name }}!</h2></div>
            </div>

            <div class="corcho-content h-100 d-flex flex-column pt-5"> <!-- Añadido pt-5 para espacio del banner -->
                <!-- Primera fila (dividida en 2 columnas) -->
                <div class="row g-0 mb-3 flex-grow-1">
                    <!-- Columna izquierda - Polaroid con foto -->
                    <div class="col-md-6 h-100 pe-2">
                        <div class="polaroid-frame h-100 d-flex flex-column align-items-center justify-content-center p-4">
                            <img src="{{ Auth::user()->imgPerfil ? Storage::url(Auth::user()->imgPerfil) : '/img/default-profile.png' }}"
                                class="polaroid-image"
                                alt="Foto de perfil">
                            <div class="polaroid-caption mt-2">Mi perfil</div>
                        </div>
                    </div>

                    <!-- Columna derecha - Postits con logros -->
                    <div class="col-md-6 h-100 ps-2">
                        <div class="postits-container h-100 position-relative p-3">
                            <div class="postit-large">
                                <h3>Mis Logros</h3>
                                <ul class="achievements-list">
                                    <li>🥇 Primer libro leído</li>
                                    <li>📚 10 libros completados</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Segunda fila - Papel con reto anual -->
                <div class="row g-0 flex-grow-1">
                    <div class="col-12 h-100">
                        <div class="paper-note h-100 p-4 d-flex flex-column">
                            <h3 class="text-center mb-3">Reto Anual</h3>
                            <div class="progress-container mx-auto">
                                <div class="progress-text text-center mb-2">
                                    {{ auth()->user()->librosLeidosEsteAnio() }} de {{ auth()->user()->retoAnual }} libros leídos
                                </div>

                            </div>
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