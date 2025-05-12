@extends('layouts.app')

@section('content')
<!-- Fondo de pantalla completa -->
<div class="list-background">
    <img src="{{ asset('img/lista/fondo-lista.png') }}" alt="Fondo" class="background-image">
</div>

<!-- Contenedor principal con scroll -->
<div class="list-container">
    <!-- Cabecera -->
    <div class="list-header">
        <div class="header-content">
            <div class="header-title">
                <h1>{{ $titulo }}</h1>
                <p>{{ $libros->count() }} {{ $libros->count() > 1 ? 'libros' : 'libro' }}</p>
            </div>
            
            <div class="header-actions">
                <!-- Barra de búsqueda modificada para filtrado en tiempo real -->
                <div class="search-form">
                    <input 
                        type="text" 
                        id="buscar-libros"
                        placeholder="Buscar libros..." 
                        class="search-input"
                        x-data
                        x-on:input.debounce.300ms="
                            const search = $event.target.value.toLowerCase();
                            document.querySelectorAll('.list-book-card').forEach(card => {
                                const title = card.querySelector('h3').textContent.toLowerCase();
                                const author = card.querySelector('.book-info p').textContent.toLowerCase();
                                card.style.display = (title.includes(search) || author.includes(search)) ? '' : 'none';
                            });
                        ">
                    <div class="search-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                        </svg>
                    </div>
                </div>
                
                <!-- Botón de ordenación (se mantiene igual) -->
                <div class="sort-container">
                    <button class="sort-button" id="sortButton">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M3.5 2.5a.5.5 0 0 0-1 0v8.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L3.5 11.293V2.5zm3.5 1a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM7.5 6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zm0 3a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1z"/>
                        </svg>
                        Ordenar
                    </button>
                    <div class="sort-options" id="sortOptions">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'titulo', 'direction' => 'asc']) }}">A-Z</a>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'titulo', 'direction' => 'desc']) }}">Z-A</a>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'autor', 'direction' => 'asc']) }}">Autor (A-Z)</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Grid de libros con scroll (se mantiene igual) -->
    <div class="list-scrollable">
        @if($libros->count() > 0)
        <div class="book-grid">
            @foreach($libros as $libro)
            <div class="list-book-card">
                <a href="{{ route('libro.show', $libro->google_id) }}" class="book-link">
                    <div class="list-book-cover">
                        @if($libro->urlPortada)
                        <img src="{{ $libro->urlPortada }}" alt="Portada de {{ $libro->titulo }}" class="book-image">
                        @else
                        <div class="book-placeholder">
                            <svg class="book-icon" viewBox="0 0 24 24">
                                <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        @endif
                        @if($mostrarInfoPrestamo ?? false)
                        <span class="book-badge">Prestado</span>
                        @endif
                    </div>
                    <div class="book-info">
                        <h3>{{ $libro->titulo }}</h3>
                        <p>{{ $libro->autor }}</p>
                    </div>
                </a>

                @if($mostrarInfoPrestamo ?? false)
                @php
                $prestamo = $libro->prestamos->first();
                @endphp
                <div class="loan-info">
                    <p>Prestado a: <span>{{ $prestamo->receptor->name }}</span></p>
                    <p>Hasta: {{ $prestamo->fecha_limite->format('d/m/Y') }}</p>
                    @if($prestamo->estaRetrasado())
                    <p class="loan-late">¡Retrasado!</p>
                    @endif

                    <form action="{{ route('prestamos.devolver', $prestamo->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="return-btn">
                            Marcar devuelto
                        </button>
                    </form>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-message">
            <p>No has prestado ningún libro actualmente</p>
        </div>
        @endif
    </div>
</div>

<script>
// Mostrar/ocultar opciones de ordenación (se mantiene igual)
document.getElementById('sortButton').addEventListener('click', function() {
    document.getElementById('sortOptions').classList.toggle('show');
});

// Cerrar menú al hacer clic fuera (se mantiene igual)
window.addEventListener('click', function(e) {
    if (!e.target.matches('#sortButton') && !e.target.closest('.sort-container')) {
        document.getElementById('sortOptions').classList.remove('show');
    }
});
</script>
@endsection