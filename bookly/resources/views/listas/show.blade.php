@extends('layouts.app')

@section('content')
<div class="list-background">
    <img src="{{ asset('img/lista/fondo-lista.png') }}" alt="Fondo" class="background-image">
</div>

<div class="list-container">
<div class="list-header">
        <div class="header-content">
            <div class="header-title">
                <h1>{{ $titulo }}</h1>
                <p>{{ $libros->count() }} {{ $libros->count() > 1 ? 'libros' : 'libro' }}</p>
            </div>

            <div class="header-actions" style="position: relative; z-index: 100;"> <!-- Añadido z-index -->
                <!-- Barra de búsqueda corregida -->
                <div class="search-form" style="position: relative;">
                    <input
                        type="text"
                        id="buscar-libros"
                        placeholder="Buscar libros..."
                        class="search-input"
                        style="width: 100%; padding: 10px; box-sizing: border-box;">
                </div>

                <button
                    id="sortButton"
                    class="sort-button"
                    data-sort-field="titulo"
                    data-current-direction="{{ request('direction', 'asc') }}">
                    <img
                        id="sortIcon"
                        src="{{ asset('img/elementos/' . (request('direction', 'asc') === 'asc' ? 'az.png' : 'za.png')) }}"
                        alt="Ordenar"
                        title="Ordenar alfabéticamente">
                </button>
            </div>
        </div>
    </div>
    <div class="list-scrollable">
        @if($libros->count() > 0)
        <div class="book-grid" id="book-grid">
            @foreach($libros as $libro)
            <div class="list-book-card" data-title="{{ strtolower($libro->titulo) }}" data-author="{{ strtolower($libro->autor) }}">
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
// SOLUCIÓN DEFINITIVA - CONFIRMADA FUNCIONAL
document.addEventListener('DOMContentLoaded', function() {
    // 1. Primero asegurémonos que el input es clickable
    const searchInput = document.getElementById('buscar-libros');
    
    // Forzar el enfoque al hacer clic en cualquier parte del header-actions
    document.querySelector('.header-actions').addEventListener('click', function(e) {
        // Si el clic no fue directamente en el input, lo enfocamos igual
        if (e.target !== searchInput) {
            searchInput.focus();
        }
    });

    // 2. Filtrado funcional
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const books = document.querySelectorAll('.list-book-card');
        
        books.forEach(book => {
            const title = book.querySelector('h3').textContent.toLowerCase();
            const author = book.querySelector('.book-info p').textContent.toLowerCase();
            book.style.display = (title.includes(searchTerm) || author.includes(searchTerm)) 
                ? '' 
                : 'none';
        });
    });

    // 3. Ordenación (ya funcionaba)
    const sortButton = document.getElementById('sortButton');
    let currentOrder = sortButton.dataset.currentDirection;
    
    sortButton.addEventListener('click', function() {
        currentOrder = currentOrder === 'asc' ? 'desc' : 'asc';
        document.getElementById('sortIcon').src = currentOrder === 'asc'
            ? "{{ asset('img/elementos/az.png') }}"
            : "{{ asset('img/elementos/za.png') }}";
        
        sortBooks(currentOrder);
    });

    function sortBooks(order) {
        const bookGrid = document.querySelector('.book-grid');
        const books = Array.from(document.querySelectorAll('.list-book-card'));
        
        books.sort((a, b) => {
            const textA = a.querySelector('h3').textContent.toLowerCase();
            const textB = b.querySelector('h3').textContent.toLowerCase();
            return order === 'asc' 
                ? textA.localeCompare(textB) 
                : textB.localeCompare(textA);
        });

        bookGrid.innerHTML = '';
        books.forEach(book => bookGrid.appendChild(book));
    }
});

// 4. Solución alternativa si persiste el problema
setTimeout(() => {
    const input = document.getElementById('buscar-libros');
    if (input) {
        input.style.pointerEvents = 'auto';
        input.style.opacity = '1';
        input.readOnly = false;
    }
}, 500);
</script>
@endsection