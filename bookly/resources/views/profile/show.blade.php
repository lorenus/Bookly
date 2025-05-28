@extends('layouts.app')
@section('content')
<div class="container-fluid px-0" style="overflow: hidden; max-width: 1600px; margin: 0 auto;">
    <div class="row g-0" style="overflow: hidden;">
        <!-- Columna Corcho -->
        <div class="col-12 col-lg-6 corcho-container position-relative">
            <div class="welcome-banner d-flex flex-column align-items-center justify-content-center">
                <div class="saludo">
                    <h2 class="m-0">¡Hola, <br>{{ Auth::user()->name }}!</h2>
                </div>
            </div>

            <div class="corcho-content h-100 d-flex flex-column pt-5">
                <!-- Primera fila -->
                <div class="row g-0 mb-3 flex-grow-1">
                    <!-- Columna izquierda - Polaroid con foto -->
                    <div class="columna1 col-md-6 h-100 pe-2">
                        <div class="polaroid-frame h-100 d-flex flex-column align-items-center justify-content-center p-4">
                            @php
                            $imagenPerfil = Auth::user()->imgPerfil
                            ? asset('storage/' . Auth::user()->imgPerfil)
                            : asset('storage/profile-photos/default.jpg');
                            @endphp
                            <img src="{{ Auth::user()->imgPerfil }}" class="polaroid-image" alt="Foto de perfil">
                        </div>
                    </div>


                    <!-- Columna derecha - Postits con logros -->
                    <div class="columna2 col-md-6 h-100 ps-2">
                        <div class="postits-container h-100 position-relative p-3">
                            <div class="postit-large">
                                @if($ultimosLogros->count() > 0)
                                @foreach($ultimosLogros as $index => $logro)
                                <div class="logro-miniatura">
                                    <img src="{{ asset('img/logros/logro'.($logro->id).'.png') }}"
                                        alt="{{ $logro->nombre }}"
                                        title="{{ $logro->nombre }}"
                                        class="img-fluid">
                                </div>
                                @endforeach
                                @else
                                <div class="no-logros">
                                    <p>No tienes logros</p>
                                </div>
                                @endif

                                <a href="{{ route('logros.index') }}" class="postit-small">
                                    Ver mis logros
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Segunda fila - Papel con reto anual -->
                <div class="row g-0 flex-grow-1">
                    <div class="col-12 h-100">
                        <div class="paper-note h-100 p-4 d-flex flex-column">
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
        <div class="col-12 col-lg-6 notebook-container">
            <div class="notebook-content h-100 d-flex flex-column" style="margin-bottom: -10%;">
                <!-- Fila 1: Búsqueda -->
                <div class="notebook-row search-row mb-4">
                    <div class="d-flex align-items-center">
                        <x-input-label for="basic-book-search" value="Buscar libros:" class="buscar font-bold mb-0 mr-3" style="font-size: x-large;" />
                        <div class="flex-grow-1">
                            <input type="text" id="basic-book-search" placeholder="Buscar libros..." class="w-full p-2 border rounded">
                        </div>
                    </div>
                    <div id="basic-results" class="mt-2 hidden"></div>
                </div>

                <!-- Fila 2: Leyendo actualmente -->
                <div class="notebook-row list-row">
                    <div class="list-header d-flex justify-content-between align-items-center">
                        <h3 class="m-0">Leyendo actualmente</h3>
                        <a href="{{ route('listas.show', 'leyendo') }}" class="see-all-link">
                            <img src="{{ asset('img/elementos/flecha1.png') }}" alt="Ver todos" class="see-all-icon">
                        </a>
                    </div>
                    <div class="book-covers d-flex justify-content-between">
                        @foreach($leyendoActual as $libro)
                            @php
                                $portadaUrl = $libro->getPortadaSegura();
                                $defaultCover = asset('img/elementos/portada_default.png');
                                $isDefaultCover = ($portadaUrl === $defaultCover); // Comprueba si es la portada por defecto
                            @endphp
                            <a href="{{ route('libro.show', $libro->google_id ?? $libro->id) }}" class="book-cover">
                                <div class="book-cover-container">
                                    <img src="{{ $portadaUrl }}"
                                        alt="{{ $libro->titulo }}"
                                        class="book-cover-image"
                                        onerror="this.onerror=null; this.src='{{ $defaultCover }}'; this.closest('.book-cover-container').classList.add('default-cover-active');">
                                    <div class="book-title-overlay @if(!$isDefaultCover) d-none @endif"> {{-- Añade d-none si no es default --}}
                                        {{ Str::limit($libro->titulo, 30) }}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Fila 3: Por leer -->
                <div class="notebook-row list-row">
                    <div class="list-header d-flex justify-content-between align-items-center">
                        <h3 class="m-0">Para leer</h3>
                        <a href="{{ route('listas.show', 'porLeer') }}" class="see-all-link">
                            <img src="{{ asset('img/elementos/flecha1.png') }}" alt="Ver todos" class="see-all-icon">
                        </a>
                    </div>
                    <div class="book-covers d-flex justify-content-between">
                        @foreach($paraLeer as $libro)
                            @php
                                $portadaUrl = $libro->getPortadaSegura();
                                $defaultCover = asset('img/elementos/portada_default.png');
                                $isDefaultCover = ($portadaUrl === $defaultCover);
                            @endphp
                            <a href="{{ route('libro.show', $libro->google_id ?? $libro->id) }}" class="book-cover">
                                <div class="book-cover-container">
                                    <img src="{{ $portadaUrl }}"
                                        alt="{{ $libro->titulo }}"
                                        class="book-cover-image"
                                        onerror="this.onerror=null; this.src='{{ $defaultCover }}'; this.closest('.book-cover-container').classList.add('default-cover-active');">
                                    {{-- El overlay solo se muestra si es la portada por defecto --}}
                                    <div class="book-title-overlay @if(!$isDefaultCover) d-none @endif">
                                        {{ Str::limit($libro->titulo, 30) }}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Fila 4: Últimas lecturas -->
                <div class="notebook-row list-row">
                    <div class="list-header d-flex justify-content-between align-items-center">
                        <h3 class="m-0">Últimas lecturas</h3>
                        <a href="{{ route('listas.show', 'leido') }}" class="see-all-link">
                            <img src="{{ asset('img/elementos/flecha1.png') }}" alt="Ver todos" class="see-all-icon">
                        </a>
                    </div>
                    <div class="book-covers d-flex justify-content-between">
                        @foreach($ultimasLecturas as $libro)
                            @php
                                $portadaUrl = $libro->getPortadaSegura();
                                $defaultCover = asset('img/elementos/portada_default.png');
                                $isDefaultCover = ($portadaUrl === $defaultCover);
                            @endphp
                            <a href="{{ route('libro.show', $libro->google_id ?? $libro->id) }}" class="book-cover">
                                <div class="book-cover-container">
                                    <img src="{{ $portadaUrl }}"
                                        alt="{{ $libro->titulo }}"
                                        class="book-cover-image"
                                        onerror="this.onerror=null; this.src='{{ $defaultCover }}'; this.closest('.book-cover-container').classList.add('default-cover-active');">
                                    {{-- El overlay solo se muestra si es la portada por defecto --}}
                                    <div class="book-title-overlay @if(!$isDefaultCover) d-none @endif">
                                        {{ Str::limit($libro->titulo, 30) }}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <script>

            // Variable para guardar el temporizador
            let timer;

            // Escuchar cuando el usuario escribe en el buscador
            document.getElementById('basic-book-search').addEventListener('input', function(e) {
                
                // Limpiar el temporizador anterior para evitar múltiples búsquedas
                clearTimeout(timer);

                // Esperar 300ms antes de hacer la búsqueda (para no buscar con cada letra)
                timer = setTimeout(function() {
                    const query = e.target.value.trim();
                    const resultsDiv = document.getElementById('basic-results');

                    // Si la búsqueda es muy corta, limpiar resultados
                    if (query.length < 2) {
                        resultsDiv.innerHTML = '';
                        resultsDiv.classList.add('hidden');
                        return;
                    }

                    // Hacer la petición al servidor
                    fetch(`/buscar-libros?query=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(books => {
                            // Si no hay libros, mostrar mensaje
                            if (!books || books.length === 0) {
                                resultsDiv.innerHTML = '<p>No se encontraron libros</p>';
                                resultsDiv.classList.remove('hidden');
                                return;
                            }

                            // Crear HTML para los resultados
                            let html = '';
                            for (let i = 0; i < books.length; i++) {
                                html += `
                            <div class="p-2 border-b">
                                <a href="/libros/${books[i].id}" class="text-blue-600">
                                    ${books[i].volumeInfo.title}
                                </a>
                            </div>
                        `;
                            }

                            // Mostrar resultados
                            resultsDiv.innerHTML = html;
                            resultsDiv.classList.remove('hidden');
                        })
                        .catch(function(error) {
                            console.error('Error:', error);
                            resultsDiv.innerHTML = '<p class="text-red-500">Error al buscar</p>';
                            resultsDiv.classList.remove('hidden');
                        });
                }, 300); // Esperar 300ms
            });
            
        </script>
        @endsection