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
                            class="w-32 h-48 object-cover rounded mx-auto">
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
                        <div class="mt-8 flex gap-4" x-data="{ open: false }">
                            <button @click="open = !open" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                Añadir a mi lista
                            </button>

                            <div x-show="open" @click.away="open = false" class="absolute z-10 mt-10 bg-white dark:bg-gray-800 shadow-lg rounded-md overflow-hidden border border-gray-200 dark:border-gray-700">
                                <form action="{{ route('libros.add-to-list') }}" method="POST" class="p-2">
                                    @csrf
                                    <input type="hidden" name="libro_id" value="{{ $book['id'] }}">
                                    <input type="hidden" name="titulo" value="{{ $book['volumeInfo']['title'] ?? '' }}">
                                    <input type="hidden" name="autor" value="{{ implode(', ', $book['volumeInfo']['authors'] ?? []) }}">
                                    <input type="hidden" name="portada" value="{{ $book['volumeInfo']['imageLinks']['thumbnail'] ?? '' }}">

                                    <select name="estado" required class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white">
                                        <option value="">Selecciona lista</option>
                                        <option value="leyendo">Leyendo Actualmente</option>
                                        <option value="leido">Mis Últimas Lecturas</option>
                                        <option value="porLeer">Para Leer</option>
                                        <option value="favoritos">Favoritos</option>
                                    </select>

                                    <div class="mt-2 flex justify-between">
                                        <button type="button" @click="open = false" class="px-3 py-1 text-gray-600 dark:text-gray-300">
                                            Cancelar
                                        </button>
                                        <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                                            Guardar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                       <button >
                            <a href="{{ route('prestamos.crear', ['libro_id' => $book['id']]) }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">
                                Prestar libro
                            </a>
                       </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection