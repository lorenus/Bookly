@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Botón de volver -->
                <a href="{{ url()->previous() }}" class="inline-block mb-6 text-blue-500 hover:underline">
                    &larr; Volver
                </a>

                <!-- Contenido del libro -->
                <div class="flex flex-col md:flex-row gap-8">
                    <!-- Portada -->
                    <div class="md:w-1/5">
                        @if(isset($book['volumeInfo']['imageLinks']['thumbnail']))
                            <img src="{{ str_replace('http://', 'https://', $book['volumeInfo']['imageLinks']['thumbnail']) }}" 
                                 alt="Portada de {{ $book['volumeInfo']['title'] }}" 
                                 class="w-32 h-48 object-cover rounded mx-auto" >
                        @else
                            <div class="bg-gray-200 dark:bg-gray-700 h-64 flex items-center justify-center rounded-lg">
                                <span class="text-gray-500 dark:text-gray-400">Sin portada</span>
                            </div>
                        @endif
                    </div>

                    <!-- Detalles -->
                    <div class="md:w-2/3">
                        <h1 class="text-3xl font-bold mb-2">{{ $book['volumeInfo']['title'] ?? 'Título desconocido' }}</h1>
                        
                        @if(isset($book['volumeInfo']['authors']))
                            <p class="text-xl text-gray-600 dark:text-gray-300 mb-4">
                                Por {{ implode(', ', $book['volumeInfo']['authors']) }}
                            </p>
                        @endif

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            @if(isset($book['volumeInfo']['publishedDate']))
                                <div>
                                    <span class="font-semibold">Año:</span>
                                    {{ \Carbon\Carbon::parse($book['volumeInfo']['publishedDate'])->year }}
                                </div>
                            @endif
                            
                            @if(isset($book['volumeInfo']['pageCount']))
                                <div>
                                    <span class="font-semibold">Páginas:</span>
                                    {{ $book['volumeInfo']['pageCount'] }}
                                </div>
                            @endif
                            
                            @if(isset($book['volumeInfo']['publisher']))
                                <div>
                                    <span class="font-semibold">Editorial:</span>
                                    {{ $book['volumeInfo']['publisher'] }}
                                </div>
                            @endif
                            
                            @if(isset($book['volumeInfo']['language']))
                                <div>
                                    <span class="font-semibold">Idioma:</span>
                                    {{ strtoupper($book['volumeInfo']['language']) }}
                                </div>
                            @endif
                        </div>

                        @if(isset($book['volumeInfo']['description']))
                            <div class="prose dark:prose-invert max-w-none">
                                <h3 class="font-semibold text-lg">Sinopsis</h3>
                                <p>{{ $book['volumeInfo']['description'] }}</p>
                            </div>
                        @endif

                        <!-- Botones de acción -->
                        <div class="mt-8 flex gap-4">
                            <a href="#" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                Añadir a mi lista
                            </a>
                            @if(isset($book['volumeInfo']['previewLink']))
                                <a href="{{ $book['volumeInfo']['previewLink'] }}" target="_blank" class="px-4 py-2 border border-blue-500 text-blue-500 rounded hover:bg-blue-50 transition">
                                    Vista previa
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection