@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <!-- Portada y detalles básicos -->
        <div class="md:flex">
            <div class="md:w-1/3 p-6">
                <img 
                    src="{{ $libro->urlPortada ?? '/images/default-book.png' }}" 
                    alt="{{ $libro->titulo }}" 
                    class="w-full h-auto rounded-lg shadow-sm"
                >
            </div>
            <div class="md:w-2/3 p-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $libro->titulo }}</h1>
                <p class="text-xl text-gray-600 dark:text-gray-300 mt-2">
                    {{ $libro->autor ?? 'Autor desconocido' }}
                </p>

                <!-- Botones de acción -->
                <div class="flex space-x-3 mt-6">
                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Añadir a "Leyendo"
                    </button>
                    <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                        Guardar en favoritos
                    </button>
                </div>

                <!-- Detalles adicionales -->
                <div class="mt-8 space-y-4">
                    @if($libro->sinopsis)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sinopsis</h3>
                        <p class="text-gray-700 dark:text-gray-300 mt-1">{{ $libro->sinopsis }}</p>
                    </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        @if($libro->isbn)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">ISBN</h3>
                            <p class="text-gray-700 dark:text-gray-300">{{ $libro->isbn }}</p>
                        </div>
                        @endif

                        @if($libro->numPaginas)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Páginas</h3>
                            <p class="text-gray-700 dark:text-gray-300">{{ $libro->numPaginas }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección adicional para datos de Google Books -->
        <div class="p-6 border-t border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Más información</h2>
            <div class="flex justify-center">
                <a 
                    href="https://books.google.com/books?id={{ $libro->google_id }}" 
                    target="_blank"
                    class="text-blue-500 hover:underline flex items-center"
                >
                    Ver en Google Books
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection