@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Barra de búsqueda NUEVA -->
        <div class="mb-6 bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-lg shadow-sm">
            <div class="relative">
                <input 
                    type="text" 
                    id="book-search" 
                    placeholder="Buscar libros en Google Books..." 
                    class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                <div id="search-results" class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 shadow-lg rounded-md hidden max-h-96 overflow-y-auto border border-gray-200 dark:border-gray-700"></div>
            </div>
        </div>

        <!-- Tu contenido ORIGINAL (menú de listas) -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('logros') }}" class="block text-blue-500 hover:underline">
                            {{ __('Logros') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('listas.show', 'leyendo') }}" class="block text-blue-500 hover:underline">
                            {{ __('Leyendo Actualmente') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('listas.show', 'leido') }}" class="block text-blue-500 hover:underline">
                            {{ __('Mis Últimas Lecturas') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('listas.show', 'favoritos') }}" class="block text-blue-500 hover:underline">
                            {{ __('Mis favoritos') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('book-search');
    const resultsContainer = document.getElementById('search-results');
    let debounceTimer;

    searchInput.addEventListener('input', function(e) {
        clearTimeout(debounceTimer);
        const query = e.target.value.trim();
        
        if (query.length < 3) {
            resultsContainer.classList.add('hidden');
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`/api/search-books?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.items && data.items.length > 0) {
                        resultsContainer.innerHTML = '';
                        data.items.forEach(book => {
                            const bookElement = document.createElement('a');
                            bookElement.href = `/libros/google/${book.id}`;
                            bookElement.className = 'block p-3 hover:bg-gray-100 dark:hover:bg-gray-700 border-b border-gray-200 dark:border-gray-600';
                            
                            bookElement.innerHTML = `
                                <div class="flex items-center">
                                    <img src="${book.volumeInfo.imageLinks?.thumbnail || '/images/default-book.png'}" 
                                         alt="${book.volumeInfo.title}" 
                                         class="w-10 h-12 object-cover mr-3 rounded">
                                    <div>
                                        <h3 class="font-medium text-gray-900 dark:text-white truncate">${book.volumeInfo.title}</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                            ${book.volumeInfo.authors?.join(', ') || 'Autor desconocido'}
                                        </p>
                                    </div>
                                </div>
                            `;
                            resultsContainer.appendChild(bookElement);
                        });
                        resultsContainer.classList.remove('hidden');
                    } else {
                        resultsContainer.innerHTML = '<p class="p-3 text-gray-500 dark:text-gray-400">No se encontraron resultados</p>';
                        resultsContainer.classList.remove('hidden');
                    }
                });
        }, 300);
    });

    // Ocultar resultados al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
            resultsContainer.classList.add('hidden');
        }
    });
});
</script>
@endpush
@endsection