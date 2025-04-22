@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Encabezado -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">{{ $titulo ?? 'Mi Colección' }}</h1>
        <p class="text-gray-600 mt-1">{{ $libros->count() }} libros</p>
    </div>

    <!-- Grid de libros - 6 por fila -->
    @if($libros->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            @foreach($libros as $libro)
                <div class="book-card group">
                    <!-- Contenedor de la portada - Tamaño fijo -->
                    <div class="relative pb-[150%] mb-2 rounded-md overflow-hidden shadow-sm bg-gray-100 group-hover:shadow-md transition-shadow">
                        @if($libro->urlPortada)
                            <img src="{{ $libro->urlPortada }}" 
                                 alt="Portada de {{ $libro->titulo }}" 
                                 class="absolute h-full w-full object-cover">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center bg-gray-200">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Título - Una sola línea -->
                    <h3 class="text-sm font-medium text-gray-800 truncate">{{ $libro->titulo }}</h3>
                    
                    <!-- Autor - Opcional -->
                    <p class="text-xs text-gray-500 truncate">{{ $libro->autor }}</p>
                </div>
            @endforeach
        </div>
    @else
        <!-- Mensaje vacío -->
        <div class="text-center py-12 bg-gray-50 rounded-lg">
            <p class="text-gray-500">No hay libros en esta lista</p>
        </div>
    @endif
</div>
@endsection