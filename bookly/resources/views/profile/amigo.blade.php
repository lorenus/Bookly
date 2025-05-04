@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sección izquierda: Información del perfil -->
            <div class="md:w-1/3">
                <div class="flex flex-col items-center text-center">
                    <img src="{{ $amigo->imgPerfil ? asset('storage/'.$amigo->imgPerfil) : asset('images/default-user.jpg') }}"
                        class="h-32 w-32 rounded-full object-cover mb-4">

                    <h1 class="text-2xl font-bold">{{ $amigo->name }} {{ $amigo->apellidos ?? '' }}</h1>
                    <p class="text-gray-600 dark:text-gray-300 mb-2">{{ $amigo->email }}</p>

                    <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-4 w-full mt-4">
                        <h3 class="font-semibold mb-2">Reto Anual</h3>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-medium">{{ $amigo->retoAnual ?? 0 }} libros</span>
                            <div class="w-3/4 bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full"
                                    style="width: <?php echo min(100, ($librosLeidos / ($amigo->retoAnual ?? 1)) * 100); ?>%">
                                </div>
                            </div>
                            <p class="text-sm mt-2">
                                {{ $librosLeidos }} de {{ $amigo->retoAnual ?? 0 }} libros leídos
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Sección derecha: Estadísticas y libros -->
                <div class="md:w-2/3">
                    <h2 class="text-xl font-semibold mb-4">Estadísticas de lectura</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4">
                            <h3 class="font-medium text-gray-500 dark:text-gray-300">Libros leídos</h3>
                            <p class="text-3xl font-bold">{{ $librosLeidos }}</p>
                        </div>

                        <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4">
                            <h3 class="font-medium text-gray-500 dark:text-gray-300">Leyendo actualmente</h3>
                            <p class="text-3xl font-bold">{{ $librosLeyendo }}</p>
                        </div>
                    </div>

                    <h2 class="text-xl font-semibold mb-4">Últimos libros leídos</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach($amigo->libros()->wherePivot('estado', 'leido')->latest()->take(8)->get() as $libro)
                        <div class="border rounded-lg p-2 hover:shadow-md transition">
                            <img src="{{ $libro->pivot->urlPortada ?? asset('images/default-book.jpg') }}"
                                alt="{{ $libro->titulo }}"
                                class="w-full h-40 object-cover rounded">
                            <h3 class="text-sm font-medium mt-2 truncate">{{ $libro->titulo }}</h3>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection