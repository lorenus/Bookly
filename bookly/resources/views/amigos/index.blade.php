@extends('layouts.app')

@section('content')
<!-- Botón de volver -->
<a href="{{ route('perfil') }}" class="inline-block mb-6 text-blue-500 hover:underline">
    &larr; Volver
</a>

<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
        <h1 class="text-2xl font-semibold mb-6">Gestión de Amigos</h1>

        <!-- Sección 1: Buscar y solicitar amistad -->
        <div class="mb-8">
            <h2 class="text-xl font-medium mb-4">Solicitar amistad</h2>
            <form method="POST" action="{{ route('amigos.store') }}" class="flex gap-2 mb-4">
                @csrf
                <input type="hidden" name="amigo_id" id="amigo-id-input"> <!-- Campo oculto para el ID -->
                <input
                    type="email"
                    name="email"
                    id="buscar-email"
                    placeholder="Introduce el email del usuario"
                    required
                    class="w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600"
                    x-data
                    x-on:input.debounce.500ms="
            if ($event.target.value.includes('@')) {
                fetch('/verificar-email?email=' + encodeURIComponent($event.target.value))
                    .then(response => response.json())
                    .then(data => {
                        const resultado = document.getElementById('resultado-busqueda');
                        if (data.existe) {
                            resultado.innerHTML = `
                                <div class='mt-2 p-2 bg-green-100 dark:bg-green-900 rounded'>
                                    Usuario encontrado: ${data.usuario.name}
                                </div>
                            `;
                            document.getElementById('amigo-id-input').value = data.usuario.id; // Actualiza el campo oculto
                        } else {
                            resultado.innerHTML = `
                                <div class='mt-2 p-2 bg-red-100 dark:bg-red-900 rounded'>
                                    No se encontró ningún usuario con ese email
                                </div>
                            `;
                            document.getElementById('amigo-id-input').value = ''; // Limpia el campo
                        }
                    });
            }">
                <button
                    type="submit"
                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 whitespace-nowrap">
                    Enviar solicitud
                </button>
            </form>
            <div id="resultado-busqueda"></div>
        </div>

        <!-- Sección 2: Lista de amigos con buscador -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-medium">Mis Amigos ({{ $amigos->count() }})</h2>
                <input
                    type="text"
                    placeholder="Buscar entre mis amigos..."
                    class="rounded-md border-gray-300 shadow-sm w-64 dark:bg-gray-700 dark:border-gray-600"
                    x-data
                    x-on:input.debounce.300ms="
                        const search = $event.target.value.toLowerCase();
                        document.querySelectorAll('.amigo-item').forEach(item => {
                            const name = item.dataset.nombre.toLowerCase();
                            const email = item.dataset.email.toLowerCase();
                            item.style.display = (name.includes(search) || email.includes(search)) ? '' : 'none';
                        })
                    ">
            </div>

            @if($amigos->count() > 0)
            <div class="border rounded-lg max-h-96 overflow-y-auto">
                @foreach($amigos as $amigo)
                <div class="amigo-item border-b p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition cursor-pointer"
                    data-nombre="{{ $amigo->name }} {{ $amigo->apellidos ?? '' }}"
                    data-email="{{ $amigo->email }}"
                    onclick="mostrarDetalleAmigo('{{ $amigo->id }}')">
                    <div class="flex items-center space-x-3">
                        <!-- Avatar -->
                        <div class="flex-shrink-0">
                            <img src="{{ asset('storage/'.$amigo->imgPerfil) }}"
                                class="h-10 w-10 rounded-full object-cover">
                        </div>

                        <!-- Información básica -->
                        <div>
                            <h3 class="font-medium">{{ $amigo->name }} {{ $amigo->apellidos ?? '' }}</h3>
                            <p class="text-sm text-gray-500">{{ $amigo->email }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 py-4">No tienes amigos aún. ¡Agrega algunos para compartir lecturas!</p>
            @endif
        </div>

        <!-- Sección 3: Detalles del amigo seleccionado -->
        <div id="detalle-amigo" class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 hidden">
            <div class="flex flex-col items-center text-center">
                <!-- Imagen de perfil -->
                <img id="detalle-imagen"
                    src=""
                    class="h-24 w-24 rounded-full object-cover mb-4">

                <!-- Nombre -->
                <h2 id="detalle-nombre" class="text-xl font-semibold mb-1"></h2>

                <!-- Email -->
                <p id="detalle-email" class="text-gray-600 dark:text-gray-300 mb-2"></p>

                <!-- Reto anual -->
                <div class="mb-4">
                    <span class="font-medium">Reto anual:</span>
                    <span id="detalle-reto" class="text-blue-600 dark:text-blue-300"></span>
                </div>

                <!-- Enlace al perfil -->
                <a id="detalle-enlace" href="#"
                    class="px-4 py-2 bg-indigo-500 text-white rounded-md hover:bg-indigo-600">
                    Ver perfil completo
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Función para mostrar detalles del amigo
    function mostrarDetalleAmigo(amigoId) {
        fetch(`/amigos/${amigoId}/detalle`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('No autorizado');
                }
                return response.json();
            })
            .then(data => {
                const detalleDiv = document.getElementById('detalle-amigo');
                document.getElementById('detalle-imagen').src = data.imgPerfil ?
                    `/storage/${data.imgPerfil}` :
                    '/images/default-user.jpg';
                document.getElementById('detalle-nombre').textContent = `${data.name} ${data.apellidos || ''}`;
                document.getElementById('detalle-email').textContent = data.email;
                document.getElementById('detalle-reto').textContent = `${data.retoAnual || '0'} libros`;
                document.getElementById('detalle-enlace').href = `/perfil/${data.id}`;

                detalleDiv.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('No se pudo cargar la información del amigo');
            });
    }

    // Cerrar detalles al hacer click fuera
    document.addEventListener('click', function(event) {
        const detalleDiv = document.getElementById('detalle-amigo');
        if (!detalleDiv.contains(event.target) &&
            !event.target.closest('.amigo-item') &&
            detalleDiv.style.display !== 'none') {
            detalleDiv.classList.add('hidden');
        }
    });
</script>
@endsection