@extends('layouts.app')

@section('content')
<!-- Botón de volver -->
<a href="{{ route('perfil') }}" class="position-fixed d-none d-lg-block" style="top: 100px; left: 40px; z-index: 1000;">
    <img src="{{ asset('img/elementos/volver.png') }}" alt="Volver" width="40" class="volver">
</a>

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

            <div class="header-actions" style="position: relative; z-index: 100;">
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
                        @php
                        $portada = $libro->urlPortada ?: '';
                        @endphp

                        @if($portada)
                        <img src="{{ $portada }}"
                            alt="Portada de {{ $libro->titulo }}"
                            class="book-image"
                            onerror="this.style.display='none'">
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
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('buscar-libros');
        document.querySelector('.header-actions').addEventListener('click', function(e) {
            if (e.target !== searchInput) {
                searchInput.focus();
            }
        });

        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const books = document.querySelectorAll('.list-book-card');

            books.forEach(book => {
                const title = book.querySelector('h3').textContent.toLowerCase();
                const author = book.querySelector('.book-info p').textContent.toLowerCase();
                book.style.display = (title.includes(searchTerm) || author.includes(searchTerm)) ?
                    '' :
                    'none';
            });
        });

        const sortButton = document.getElementById('sortButton');
        let currentOrder = sortButton.dataset.currentDirection;

        sortButton.addEventListener('click', function() {
            currentOrder = currentOrder === 'asc' ? 'desc' : 'asc';
            document.getElementById('sortIcon').src = currentOrder === 'asc' ?
                "{{ asset('img/elementos/az.png') }}" :
                "{{ asset('img/elementos/za.png') }}";

            sortBooks(currentOrder);
        });

        function sortBooks(order) {
            const bookGrid = document.querySelector('.book-grid');
            const books = Array.from(document.querySelectorAll('.list-book-card'));

            books.sort((a, b) => {
                const textA = a.querySelector('h3').textContent.toLowerCase();
                const textB = b.querySelector('h3').textContent.toLowerCase();
                return order === 'asc' ?
                    textA.localeCompare(textB) :
                    textB.localeCompare(textA);
            });

            bookGrid.innerHTML = '';
            books.forEach(book => bookGrid.appendChild(book));
        }
    });
</script>
@endsection