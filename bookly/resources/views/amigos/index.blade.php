@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
        <h1 class="text-2xl font-semibold mb-6">Gestión de Amigos</h1>

        <!-- Sección 1: Buscar y solicitar amistad -->
        <div class="mb-8">
            <h2 class="text-xl font-medium mb-4">Solicitar amistad</h2>
            <form method="POST" action="{{ route('amigos.store') }}" class="flex gap-2">
                @csrf
                <input
                    type="text"
                    name="busqueda"
                    id="buscar-usuario"
                    placeholder="Buscar usuario por nombre o apellido"
                    class="w-full rounded-md border-gray-300 shadow-sm"
                    x-data
                    x-on:input.debounce.500ms="
                        fetch('/buscar-usuarios?q=' + $event.target.value)
                            .then(response => response.json())
                            .then(data => {
                                // Implementar lógica para mostrar sugerencias
                                console.log(data);
                            })
                    ">
                <button
                    type="submit"
                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 whitespace-nowrap">
                    Enviar solicitud
                </button>
            </form>
        </div>

        <!-- Sección 2: Lista de amigos con buscador -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-medium">Mis Amigos ({{ $amigos->count() }})</h2>
                <input
                    type="text"
                    placeholder="Filtrar amigos..."
                    class="rounded-md border-gray-300 shadow-sm w-64"
                    x-data
                    x-on:input.debounce.300ms="
                        const search = $event.target.value.toLowerCase();
                        document.querySelectorAll('.amigo-item').forEach(item => {
                            const name = item.dataset.nombre.toLowerCase();
                            item.style.display = name.includes(search) ? '' : 'none';
                        })
                    ">
            </div>

            @if($amigos->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($amigos as $amigo)
                <div class="border rounded-lg p-4 flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <div class="flex items-center space-x-3">
                        <!-- Avatar/Iniciales -->
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                            <span class="text-indigo-600 dark:text-indigo-300 font-medium">
                                {{ substr($amigo->name, 0, 1) }}{{ substr($amigo->apellidos ?? '', 0, 1) }}
                            </span>
                        </div>

                        <!-- Información del amigo -->
                        <div>
                            <h3 class="font-medium">{{ $amigo->name }} {{ $amigo->apellidos ?? '' }}</h3>
                            <p class="text-sm text-gray-500">
                                Reto anual: {{ $amigo->retoAnual ?? '0' }} libros
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ $amigo->email }}
                            </p>
                        </div>
                    </div>

                    <!-- Botón Eliminar -->
                    <form method="POST" action="{{ route('amigos.destroy', $amigo->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            onclick="return confirm('¿Eliminar a {{ addslashes($amigo->name) }} de tus amigos?')"
                            class="text-red-500 hover:text-red-700 px-2 py-1 rounded hover:bg-red-50 dark:hover:bg-red-900/50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 py-4">No tienes amigos aún. ¡Agrega algunos para compartir lecturas!</p>
            @endif
        </div>

        <!-- Botón de eliminar en el panel de detalles -->
        <div id="detalle-amigo" class="hidden">
            <form method="POST" id="form-eliminar-amistad" action="#">
                @csrf
                @method('DELETE')
                <button type="button"
                    onclick="if(confirm('¿Estás seguro?')) { this.form.submit(); }"
                    class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                    Eliminar amistad
                </button>
            </form>
        </div>

        <script>
            function mostrarDetalleAmigo(amigoId) {
                const form = document.getElementById('form-eliminar-amistad');
                form.action = `/amigos/${amigoId}`; // Sintaxis directa garantizada
            }
        </script>
        @endsection