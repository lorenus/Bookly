@extends('layouts.app')

@section('content')
<!-- Botón de volver -->
<a href="{{ route('perfil') }}" class="volver-btn" style="position: fixed;top: 100px;left: 40px;">
    <img src="{{ asset('img/elementos/volver.png') }}" alt="Volver">
</a>

<div class="libreta-container">
    <!-- Fondo de libreta -->
    <div class="libreta-background"></div>

    <!-- Contenido de la libreta -->
    <div class="libreta-content">
        <!-- Página izquierda -->
        <div class="libreta-page left-page">
            <!-- Portada del libro -->
            <div class="portada-container">
                @if(isset($book['volumeInfo']['imageLinks']['thumbnail']))
                <img src="{{ str_replace('http://', 'https://', $book['volumeInfo']['imageLinks']['thumbnail']) }}"
                    alt="Portada de {{ $book['volumeInfo']['title'] }}"
                    class="portada-img">
                @else
                <div class="portada-placeholder">
                    <span>Sin portada</span>
                </div>
                @endif
            </div>

            <!-- Valoración promedio -->
            <div class="rating-container">
                <div class="stars">
                    @php
                    $rating = $book['volumeInfo']['averageRating'] ?? 0;
                    $fullStars = floor($rating);
                    $hasHalfStar = $rating - $fullStars >= 0.5;
                    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                    @endphp

                    @for($i = 0; $i < $fullStars; $i++)
                        <span class="star full">★</span>
                        @endfor

                        @if($hasHalfStar)
                        <span class="star half">★</span>
                        @endif

                        @for($i = 0; $i < $emptyStars; $i++)
                            <span class="star empty">★</span>
                            @endfor
                </div>
                <div class="rating-text">
                    {{ number_format($rating, 2) }} según {{ $book['volumeInfo']['ratingsCount'] ?? 0 }} reseñas
                </div>
            </div>

            <!-- Datos del libro -->
            <div class="book-details mt-3">
                <h1 class="book-title">{{ $book['volumeInfo']['title'] ?? 'Título desconocido' }}</h1>

                @if(isset($book['volumeInfo']['authors']))
                <p class="book-author">Autor/a: {{ implode(', ', $book['volumeInfo']['authors']) }}</p>
                @endif

                @if(isset($book['volumeInfo']['publishedDate']))
                <p class="book-date">Publicación: {{ \Carbon\Carbon::parse($book['volumeInfo']['publishedDate'])->format('d/m/Y') }}</p>
                @endif

                @if(isset($book['volumeInfo']['publisher']))
                <p class="book-publisher">Editorial: {{ $book['volumeInfo']['publisher'] }}</p>
                @endif
            </div>
            <!-- Gestión del libro -->
            <div class="book-management">
                <!-- Primera fila: Añadir a lista + Checkbox -->
                <div class="row mb-3">
                    <!-- Columna para Añadir a lista -->
                    <div class="col-md-6 col-s-12">
                        <form action="{{ route('libros.add-to-list') }}" method="POST">
                            @csrf
                            <input type="hidden" name="libro_id" value="{{ $book['id'] }}">
                            <input type="hidden" name="titulo" value="{{ $book['volumeInfo']['title'] ?? '' }}">
                            <input type="hidden" name="autor" value="{{ implode(', ', $book['volumeInfo']['authors'] ?? []) }}">
                            <input type="hidden" name="portada" value="{{ $book['volumeInfo']['imageLinks']['thumbnail'] ?? '' }}">

                            <div class="form-group">
                                <label for="estado">Añadir a lista:</label>
                                <select name="estado" id="estado" class="form-control" onchange="this.form.submit()">
                                    <option value="">Seleccionar...</option>
                                    <option value="leyendo" {{ Auth::user()->tieneLibroEnLista($book['id'], 'leyendo') ? 'selected' : '' }}>Leyendo</option>
                                    <option value="leido" {{ Auth::user()->tieneLibroEnLista($book['id'], 'leido') ? 'selected' : '' }}>Leído</option>
                                    <option value="porLeer" {{ Auth::user()->tieneLibroEnLista($book['id'], 'porLeer') ? 'selected' : '' }}>Por leer</option>
                                    <option value="favoritos" {{ Auth::user()->tieneLibroEnLista($book['id'], 'favoritos') ? 'selected' : '' }}>Favoritos</option>
                                </select>
                            </div>
                        </form>
                    </div>

                    <!-- Columna para Checkbox "Lo tengo" -->
                    <div class="col-md-6 col-s-12 mt-4 d-flex align-items-center">
                        <form action="{{ route('libros.comprar', $book['id']) }}" method="POST" class="w-100">
                            @csrf
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="comprado" id="comprado"
                                    {{ Auth::user()->libros()->where('google_id', $book['id'])->wherePivot('comprado', true)->exists() ? 'checked' : '' }}
                                    onchange="this.form.submit()">
                                <label class="form-check-label" for="comprado">
                                    ¡Lo tengo!
                                </label>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Segunda fila: Recomendar + Prestar -->
                <div class="row">
                    <!-- Columna para Recomendar -->
                    <div class="col-md-6 col-sm-6 mb-2">
                        <div class="form-group">
                            <label>Recomendar a:</label>
                            <select class="form-control select2-recomendar">
                                <option></option>
                                @foreach(Auth::user()->amigos as $amigo)
                                <option value="{{ $amigo->id }}">{{ $amigo->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Columna para Prestar -->
                    <div class="col-md-6 col-sm-6 mb-2">
                        <div class="form-group">
                            <label>Prestar a:</label>
                            <select class="form-control select2-prestar">
                                <option></option>
                                @foreach(Auth::user()->amigos as $amigo)
                                <option value="{{ $amigo->id }}">{{ $amigo->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Página derecha -->
        <div class="libreta-page right-page">
            <h2 class="sinopsis-title">Sinopsis</h2>
            <div class="sinopsis-content">
                {{ $book['volumeInfo']['description'] ?? 'No hay sinopsis disponible.' }}
            </div>

            <!-- Sección para valoración del usuario (si ha leído el libro) -->
            @if(Auth::user()->haLeidoLibro($book['id']))
            <div class="user-rating">
                <h3>Tu valoración</h3>
                <form action="{{ route('libros.rate', $book['id']) }}" method="POST">
                    @csrf
                    <div class="rating-stars">
                        @for($i = 5; $i >= 1; $i--)
                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}"
                            {{ Auth::user()->getValoracionLibro($book['id']) == $i ? 'checked' : '' }}>
                        <label for="star{{ $i }}">★</label>
                        @endfor
                    </div>
                    <button type="submit" class="btn-rate">Guardar valoración</button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2-recomendar').select2({
            placeholder: "Buscar amigo...",
            allowClear: true
        });

        $('.select2-prestar').select2({
            placeholder: "Buscar amigo...",
            allowClear: true
        });
    });
</script>
@endpush
@endsection