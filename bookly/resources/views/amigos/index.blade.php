@extends('layouts.app')

@section('content')
<!-- Botón de volver -->
<a href="{{ route('perfil') }}" class="position-fixed" style="top: 100px; left: 40px; z-index: 1000;">
    <img src="{{ asset('img/elementos/volver.png') }}" alt="Volver" width="40" class="volver">
</a>

<div class="amigos-container container-fluid d-flex justify-content-center align-items-end position-relative" style="min-height: 100vh;">
    <!-- Imagen de fondo -->
    <div class="position-absolute bottom-0 start-0 w-100 text-center" style="z-index: 1;">
        <img src="{{ asset('img/amigos/libreta-amigos.png') }}" alt="Fondo" class="img-fluid mx-auto" style="max-height: 90vh;">
    </div>

    <!-- Contenido principal -->
    <div class="position-relative" style="width: 62%; min-height: 67vh; z-index: 2; margin-bottom: 5vh;">
        <div class="row g-4">
            <!-- Columna izquierda - Formularios -->
            <div class="col-md-6">
                <!-- Sección 1: Buscar y solicitar amistad -->
                <div class="mb-4">
                    <div>
                        <h4>Solicitar amistad:</h4>
                        <form method="POST" action="{{ route('amigos.store') }}" class="mb-4">
                            @csrf
                            <input type="hidden" name="amigo_id" id="amigo-id-input">

                            <!-- Input para el email -->
                            <div class="mb-5">
                                <div class="w-100"> <!-- Contenedor padre al 100% -->
                                    <input
                                        type="email"
                                        name="email"
                                        id="buscar-email"
                                        placeholder="Introduce el email del usuario"
                                        required
                                        class="form-control border-0 px-0 bg-transparent w-100"
                                        style="min-width: 100%;"
                                        x-data
                                        x-on:input.debounce.500ms='
                                            const email = $event.target.value;
                                            const resultadoDiv = document.getElementById("resultado-busqueda");
                                            
                                            if (email.includes("@")) {
                                                resultadoDiv.innerHTML = "<div class=\"text-muted mt-2\">Buscando usuario...</div>";
                                                
                                                fetch("/verificar-email?email=" + encodeURIComponent(email))
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        if (data.existe) {
                                                            resultadoDiv.innerHTML = `
                                                                <div class="mt-2 p-3 bg-light rounded border">
                                                                    <div class="d-flex align-items-center">
                                                                        <img src="${data.usuario.imgPerfil ? "/storage/"+data.usuario.imgPerfil : "/images/default-user.jpg"}" 
                                                                            class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;">
                                                                        <div>
                                                                            <h5 class="mb-0">${data.usuario.name} ${data.usuario.apellidos || ""}</h5>
                                                                            <small class="text-muted">${email}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            `;
                                                            document.getElementById("amigo-id-input").value = data.usuario.id;
                                                            document.getElementById("btn-enviar-solicitud").disabled = false;
                                                        } else {
                                                            resultadoDiv.innerHTML = "<div class=\"mt-2 p-2 text-danger\">No se encontró ningún usuario con ese email</div>";
                                                            document.getElementById("amigo-id-input").value = "";
                                                            document.getElementById("btn-enviar-solicitud").disabled = true;
                                                        }
                                                    })
                                                    .catch(error => {
                                                        resultadoDiv.innerHTML = "<div class=\"mt-2 p-2 text-danger\">Error al buscar el usuario</div>";
                                                        console.error("Error:", error);
                                                        document.getElementById("btn-enviar-solicitud").disabled = true;
                                                    });
                                            } else if (email.length > 0) {
                                                resultadoDiv.innerHTML = "<div class=\"mt-2 p-2 text-warning\">Por favor, introduce un email válido</div>";
                                                document.getElementById("btn-enviar-solicitud").disabled = true;
                                            } else {
                                                resultadoDiv.innerHTML = "";
                                                document.getElementById("btn-enviar-solicitud").disabled = true;
                                            }
                                        '>
                                </div>
                            </div>

                            <!-- Resultado de la búsqueda -->
                            <div id="resultado-busqueda" class="mb-3"></div>
                            <div class="d-flex gap-2 justify-content-center mb-5">
                                <x-button type="submit" class="px-6 py-3">
                                    {{ __('Enviar') }}
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sección 2: Lista de amigos con buscador -->
                <div class="mt-5">
                    <div>
                        <!-- Título y buscador en columna -->
                        <div class="mt-5 mb-4">
                            <h4>Mis Amigos ({{ $amigos->count() }})</h4>
                            <div class="w-100"> 
                                <input
                                    type="text"
                                    placeholder="Buscar entre mis amigos..."
                                    class="form-control border-0 px-0 bg-transparent w-100"
                                    style="min-width: 100%; border-bottom: 2px solid #dee2e6;"
                                    x-data
                                    x-on:input.debounce.300ms="
                        const search = $event.target.value.toLowerCase();
                        document.querySelectorAll('.amigo-item').forEach(item => {
                            const name = item.dataset.nombre.toLowerCase();
                            const email = item.dataset.email.toLowerCase();
                            item.style.display = (name.includes(search) || email.includes(search)) ? '' : 'none';
                        })">
                            </div>
                        </div>

                        @if($amigos->count() > 0)
                        <!-- Lista de amigos con scroll -->
                        <div style="height: 50%px; overflow-y: auto;"> <!-- Alto fijo y scroll -->
                            @foreach($amigos as $amigo)
                            <div class="amigo-item p-3 hover-bg-light transition cursor-pointer"
                                data-nombre="{{ $amigo->name }} {{ $amigo->apellidos ?? '' }}"
                                data-email="{{ $amigo->email }}"
                                onclick="mostrarDetalleAmigo('{{ $amigo->id }}')">
                                <div class="d-flex align-items-center gap-3">
                                    <!-- Avatar -->
                                    <div>
                                        <img src="{{ asset('storage/'.$amigo->imgPerfil) }}"
                                            class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                                    </div>

                                    <!-- Solo nombre (sin email) -->
                                    <div>
                                        <h5 class="font-medium mb-0">{{ $amigo->name }} {{ $amigo->apellidos ?? '' }}</h5>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-muted py-4">No tienes amigos aún. ¡Agrega algunos para compartir lecturas!</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Columna derecha - Detalles del amigo -->
            <div class="col-md-6">
                <div class="h-100">
                    <div class="d-flex flex-column align-items-center">
                        <!-- Fila 1: Foto de perfil del amigo seleccionado -->
                        <div id="detalle-imagen-container" class="mb-4 text-center" style="display: none;">
                            <img id="detalle-imagen"
                                src=""
                                class="rounded-circle mb-2"
                                width="120"
                                height="120">
                            <h3 id="detalle-nombre" class="h4 mb-1"></h3>
                            <p id="detalle-email" class="text-muted small"></p>
                        </div>

                        <!-- Fila 2: Dos imágenes mías -->
                        <div class="row mb-4 w-100">
                            <div class="col-6 text-center">
                                <img src="{{ asset('img/amigos/polaroid-amigos.png') }}"
                                    alt="Tu imagen 1"
                                    style="max-height: 150px;">
                            </div>
                            <div class="col-6 text-center">
                                <img src="{{ asset('img/amigos/flor-amigos.png') }}"
                                    alt="Tu imagen 2"
                                    style="max-height: 150px;">
                            </div>
                        </div>

                        <!-- Fila 3: Logros del amigo -->
                        <div id="detalle-logros" class="mb-4 w-100 text-center" style="display: none;">
                            <h4 class="h5 mb-3">Logros</h4>
                            <div class="d-flex justify-content-center flex-wrap gap-2" id="logros-container">
                                <!-- Los logros se cargarán aquí dinámicamente -->
                            </div>
                        </div>

                        <!-- Fila 4: Reto anual -->
                        <div id="detalle-reto-container" class="mb-4 w-100 text-center" style="display: none;">
                            <h4 class="h5 mb-2">Reto Anual</h4>
                            <div class="progress" style="height: 25px;">
                                <div id="reto-progress" class="progress-bar bg-success" role="progressbar" style="width: 0%;"></div>
                            </div>
                            <p id="detalle-reto-texto" class="mt-2 mb-0"></p>
                        </div>

                        <!-- Fila 5: Botón para ver perfil -->
                        <div id="detalle-boton-container" class="mt-auto w-100 text-center" style="display: none;">
                            <a id="detalle-enlace" href="#" class="btn btn-primary w-100">
                                Ver perfil completo
                            </a>
                        </div>
                    </div>
                </div>
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
                // Mostrar contenedores
                document.getElementById('detalle-imagen-container').style.display = 'block';
                document.getElementById('detalle-logros').style.display = 'block';
                document.getElementById('detalle-reto-container').style.display = 'block';
                document.getElementById('detalle-boton-container').style.display = 'block';

                // Actualizar datos
                const imgPerfil = data.imgPerfil ? `/storage/${data.imgPerfil}` : '/images/default-user.jpg';
                document.getElementById('detalle-imagen').src = imgPerfil;
                document.getElementById('detalle-nombre').textContent = `${data.name} ${data.apellidos || ''}`;
                document.getElementById('detalle-email').textContent = data.email;

                // Actualizar reto anual
                const retoAnual = data.retoAnual || 0;
                const librosLeidos = data.librosLeidosAnual || 0;
                const porcentaje = retoAnual > 0 ? Math.min(100, Math.round((librosLeidos / retoAnual) * 100)) : 0;
                document.getElementById('reto-progress').style.width = `${porcentaje}%`;
                document.getElementById('detalle-reto-texto').textContent =
                    `${librosLeidos} de ${retoAnual} libros (${porcentaje}%)`;

                // Actualizar logros
                const logrosContainer = document.getElementById('logros-container');
                logrosContainer.innerHTML = '';
                if (data.logros && data.logros.length > 0) {
                    data.logros.forEach(logro => {
                        const badge = document.createElement('span');
                        badge.className = 'badge bg-secondary';
                        badge.textContent = logro;
                        logrosContainer.appendChild(badge);
                    });
                } else {
                    logrosContainer.innerHTML = '<p class="text-muted">No hay logros aún</p>';
                }

                // Actualizar enlace al perfil
                document.getElementById('detalle-enlace').href = `/perfil/${data.id}`;
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
</script>

@endsection