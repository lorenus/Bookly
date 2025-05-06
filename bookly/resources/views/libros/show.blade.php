@extends('layouts.app')

@section('content')
@if(session('success'))
<div class="mb-4 p-2 bg-green-100 text-green-700 rounded">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-4 p-2 bg-red-100 text-red-700 rounded">
    {{ session('error') }}
</div>
@endif

<div class="py-12">
    <div>
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
            <div class="p-6">
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
                        <div class="w-32 h-48 bg-gray-200 flex items-center justify-center rounded mx-auto">
                            <span class="text-gray-500">Sin portada</span>
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
                        <div class="mt-8 space-y-4">
                            <!-- Formulario para marcar como comprado -->
                            <form action="{{ route('libros.comprar', $book['id']) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('POST')

                                @php
                                $isComprado = Auth::user()->libros()->where('google_id', $book['id'])->where('comprado', true)->exists();
                                @endphp

                                <input type="checkbox" id="comprado" name="comprado"
                                    class="rounded text-blue-500" {{ $isComprado ? 'checked' : '' }}
                                    onchange="this.form.submit()">

                                <label for="comprado" class="cursor-pointer">
                                    Marcar como comprado
                                </label>
                            </form>

                            <!-- Botones para añadir a lista -->
                            <div class="flex flex-wrap gap-2">
                                <form action="{{ route('libros.add-to-list') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="libro_id" value="{{ $book['id'] }}">
                                    <input type="hidden" name="titulo" value="{{ $book['volumeInfo']['title'] ?? '' }}">
                                    <input type="hidden" name="autor" value="{{ implode(', ', $book['volumeInfo']['authors'] ?? []) }}">
                                    <input type="hidden" name="portada" value="{{ $book['volumeInfo']['imageLinks']['thumbnail'] ?? '' }}">
                                    <input type="hidden" name="estado" value="leyendo">
                                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                        Leyendo
                                    </button>
                                </form>

                                <form action="{{ route('libros.add-to-list') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="libro_id" value="{{ $book['id'] }}">
                                    <input type="hidden" name="titulo" value="{{ $book['volumeInfo']['title'] ?? '' }}">
                                    <input type="hidden" name="autor" value="{{ implode(', ', $book['volumeInfo']['authors'] ?? []) }}">
                                    <input type="hidden" name="portada" value="{{ $book['volumeInfo']['imageLinks']['thumbnail'] ?? '' }}">
                                    <input type="hidden" name="estado" value="leido">
                                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                                        Leído
                                    </button>
                                </form>

                                <form action="{{ route('libros.add-to-list') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="libro_id" value="{{ $book['id'] }}">
                                    <input type="hidden" name="titulo" value="{{ $book['volumeInfo']['title'] ?? '' }}">
                                    <input type="hidden" name="autor" value="{{ implode(', ', $book['volumeInfo']['authors'] ?? []) }}">
                                    <input type="hidden" name="portada" value="{{ $book['volumeInfo']['imageLinks']['thumbnail'] ?? '' }}">
                                    <input type="hidden" name="estado" value="porLeer">
                                    <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                        Por leer
                                    </button>
                                </form>

                                <form action="{{ route('libros.add-to-list') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="libro_id" value="{{ $book['id'] }}">
                                    <input type="hidden" name="titulo" value="{{ $book['volumeInfo']['title'] ?? '' }}">
                                    <input type="hidden" name="autor" value="{{ implode(', ', $book['volumeInfo']['authors'] ?? []) }}">
                                    <input type="hidden" name="portada" value="{{ $book['volumeInfo']['imageLinks']['thumbnail'] ?? '' }}">
                                    <input type="hidden" name="estado" value="favoritos">
                                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                        Favoritos
                                    </button>
                                </form>
                            </div>

                            <!-- Botón para prestar libro -->
                            <div class="pt-4">
                                <a href="{{ route('prestamos.crear', ['libro_id' => $book['id']]) }}"
                                    class="px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600">
                                    Prestar libro
                                </a>
                            </div>
                            <div class="mt-6">
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open"
                                        class="px-4 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-600">
                                        Recomendar a un amigo
                                    </button>

                                    <div x-show="open" @click.away="open = false"
                                        class="absolute z-10 mt-2 w-64 bg-white dark:bg-gray-700 rounded-md shadow-lg p-4">
                                        <form action="{{ route('libros.recomendar') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="libro_id" value="{{ $book['id'] }}">
                                            <input type="hidden" name="titulo" value="{{ $book['volumeInfo']['title'] ?? '' }}">
                                            <input type="hidden" name="portada" value="{{ $book['volumeInfo']['imageLinks']['thumbnail'] ?? '' }}">

                                            <div class="mb-4">
                                                <label for="amigo_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                    Seleccionar amigo
                                                </label>
                                                <select name="amigo_id" id="amigo_id" required
                                                    class="w-full p-2 border rounded dark:bg-gray-800 dark:text-white">
                                                    <option value="">-- Elegir amigo --</option>
                                                    @foreach(Auth::user()->amigos as $amigo)
                                                    <option value="{{ $amigo->id }}">{{ $amigo->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-4">
                                                <label for="mensaje" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                    Mensaje (opcional)
                                                </label>
                                                <textarea name="mensaje" id="mensaje" rows="2"
                                                    class="w-full p-2 border rounded dark:bg-gray-800 dark:text-white"
                                                    placeholder="¡Creo que te gustará este libro!"></textarea>
                                            </div>

                                            <div class="flex justify-end gap-2">
                                                <button type="button" @click="open = false"
                                                    class="px-3 py-1 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 rounded">
                                                    Cancelar
                                                </button>
                                                <button type="submit"
                                                    class="px-3 py-1 bg-indigo-500 text-white rounded hover:bg-indigo-600">
                                                    Enviar recomendación
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection