@extends('layouts.app')
@section('content')
<div class="container-fluid" style="overflow: hidden;">
    <div class="row g-0" style="overflow: hidden;">
        <!-- Columna Corcho -->
        <div class="col-md-6 corcho-container position-relative">
            <div class="welcome-banner d-flex flex-column align-items-center justify-content-center">
                <div style="margin-left: 20%; margin-top: -7%; transform: rotate(-9deg);">
                    <h2 class="m-0">¡Hola, <br>{{ Auth::user()->name }}!</h2>
                </div>
            </div>

            <div class="corcho-content h-100 d-flex flex-column pt-5">
                <!-- Primera fila -->
                <div class="row g-0 mb-3 flex-grow-1">
                    <!-- Columna izquierda - Polaroid con foto -->
                    <div class="col-md-6 h-100 pe-2">
                        <div class="polaroid-frame h-100 d-flex flex-column align-items-center justify-content-center p-4">
                            @php
                            $imagenPerfil = Auth::user()->imgPerfil
                            ? asset('storage/' . Auth::user()->imgPerfil)
                            : asset('img/default-profile.jpg');
                            @endphp
                            <img src="{{ Auth::user()->imgPerfil }}" class="polaroid-image" alt="Foto de perfil">
                        </div>
                    </div>


                    <!-- Columna derecha - Postits con logros -->
                    <div class="col-md-6 h-100 ps-2">
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
                                    <p>Aún no has desbloqueado logros</p>
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
        <div class="col-md-6 notebook-container">
            <div class="notebook-content h-100 d-flex flex-column" style="margin-bottom: -10%;">
                <!-- Fila 1: Búsqueda -->
                <div class="notebook-row search-row mb-4">
                    <div class="d-flex align-items-center">
                        <x-input-label for="basic-book-search" value="Buscar libros" class="text-lg font-bold mb-0 mr-3" />
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
                        $isDefaultCover = $portadaUrl === $defaultCover;
                        @endphp
                        <a href="{{ route('libro.show', $libro->google_id ?? $libro->id) }}" class="book-cover">
                            <div class="book-cover-container">
                                <img src="{{ $portadaUrl }}"
                                    alt="{{ $libro->titulo }}"
                                    class="book-cover-image"
                                    @if($isDefaultCover) style="background-image: url('{{ $defaultCover }}');" @endif>
                                @if($isDefaultCover)
                                <div class="book-title-overlay">
                                    {{ Str::limit($libro->titulo, 30) }}
                                </div>
                                @endif
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
                        $isDefaultCover = $portadaUrl === $defaultCover;
                        @endphp
                        <a href="{{ route('libro.show', $libro->google_id ?? $libro->id) }}" class="book-cover">
                            <div class="book-cover-container">
                                <img src="{{ $portadaUrl }}"
                                    alt="{{ $libro->titulo }}"
                                    class="book-cover-image book-cover-fallback"
                                    data-default-cover="{{ $defaultCover }}">
                                @if($isDefaultCover)
                                <div class="book-title-overlay">
                                    {{ Str::limit($libro->titulo, 30) }}
                                </div>
                                @endif
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
                        $isDefaultCover = $portadaUrl === $defaultCover;
                        @endphp
                        <a href="{{ route('libro.show', $libro->google_id ?? $libro->id) }}" class="book-cover">
                            <div class="book-cover-container">
                                <img src="{{ $portadaUrl }}"
                                    alt="{{ $libro->titulo }}"
                                    class="book-cover-image"
                                    onerror="this.onerror=null; this.src='{{ $defaultCover }}'">
                                @if($isDefaultCover)
                                <div class="book-title-overlay">
                                    {{ Str::limit($libro->titulo, 30) }}
                                </div>
                                @endif
                            </div>
                        </a>
                        @endforeach
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

            // Add fallback for book cover images
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.book-cover-fallback').forEach(function(img) {
                    img.addEventListener('error', function() {
                        if (img.src !== img.dataset.defaultCover) {
                            img.src = img.dataset.defaultCover;
                        }
                    });
                });
            });
        </script>
        @endsection
        