@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Encabezado -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">{{ $titulo }}</h1>
        <p class="text-gray-600 mt-1">{{ $libros->count() }} 
            @if($libros->count()>1)
                libros
            @else
                libro
            @endif
        </p>
    </div>

    @if($libros->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            @foreach($libros as $libro)
                <div class="book-card group relative">
                    <!-- Enlace a la ficha del libro -->
                    <a href="{{ route('libro.show', $libro->google_id) }}" class="block">
                        <!-- Portada del libro -->
                        <div class="relative pb-[150%] mb-2 rounded-md overflow-hidden shadow-sm bg-gray-100">
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
                            
                            <!-- Badge de préstamo -->
                            <span class="absolute top-2 right-2 bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">
                                Prestado
                            </span>
                        </div>
                        
                        <!-- Información básica -->
                        <h3 class="text-sm font-medium text-gray-800 truncate">{{ $libro->titulo }}</h3>
                        <p class="text-xs text-gray-500 truncate">{{ $libro->autor }}</p>
                    </a>
                    
                    <!-- Información del préstamo (fuera del enlace para que no afecte el click) -->
                    @if($mostrarInfoPrestamo ?? false)
                        @php
                            $prestamo = $libro->prestamos->first();
                        @endphp
                        <div class="mt-1 text-xs text-gray-600">
                            <p>Prestado a: <span class="font-medium">{{ $prestamo->receptor->name }}</span></p>
                            <p>Hasta: {{ $prestamo->fecha_limite->format('d/m/Y') }}</p>
                            @if($prestamo->estaRetrasado())
                                <p class="text-red-500 font-medium">¡Retrasado!</p>
                            @endif
                            
                            <!-- Botón para marcar como devuelto -->
                            <form action="{{ route('prestamos.devolver', $prestamo->id) }}" method="POST" class="mt-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded hover:bg-green-200">
                                    Marcar devuelto
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12 bg-gray-50 rounded-lg">
            <p class="text-gray-500">No has prestado ningún libro actualmente</p>
        </div>
    @endif
</div>
@endsection