@extends('layouts.app')

@section('content')
<!-- Botón de volver -->
<a href="{{ route('perfil') }}" class="position-fixed d-none d-lg-block" style="top: 100px; left: 40px; z-index: 1000;">
    <img src="{{ asset('img/elementos/volver.png') }}" alt="Volver" width="40" class="volver">
</a>

<div class="amigos-container">
    <!-- Contenido principal -->
    <div class="libreta-wrapper">

        <div class="libreta-content">
            <!-- Página izquierda - Formularios -->
            <div class="left-page">
                <!-- Sección 1: Buscar y solicitar amistad -->
                <div>
                    <div>
                        <h4>Solicitar amistad:</h4>
                        <form method="POST" action="{{ route('amigos.store') }}" class="mb-4">
                            @csrf
                            <input type="hidden" name="amigo_id" id="amigo-id-input">

                            <div>
                                <div class="w-100">
                                    <input
                                        type="email"
                                        name="email"
                                        id="buscar-email"
                                        placeholder="Introduce el email del usuario"
                                        required
                                        class="form-control border-0 px-0 bg-transparent w-100"
                                        style="min-width: 100%; border-bottom: 2px solid #dee2e6;"
                                        x-data
                                        x-on:input.debounce.500ms='
                                        const email = $event.target.value;
                                        const resultadoDiv = document.getElementById("resultado-busqueda");
                                        const btnEnviar = document.getElementById("btn-enviar-solicitud");
                                        
                                        if (email.includes("@")) {
                                            resultadoDiv.innerHTML = "<div class=\"text-muted mt-2\">Buscando usuario...</div>";
                                            btnEnviar.disabled = true;
                                            
                                            fetch(`/verificar-email?email=${encodeURIComponent(email)}`)
                                                .then(response => {
                                                    if (!response.ok) throw new Error("Error en la respuesta");
                                                    return response.json();
                                                })
                                                .then(data => {
                                                    if (data.existe) {
                                                        resultadoDiv.innerHTML = `
                                                            <div class="ms-5 p-3">
                                                                <div class="d-flex align-items-center">
                                                                    <img src="${data.usuario.imgPerfil ? "/storage/"+data.usuario.imgPerfil : "/images/default-user.jpg"}"
                                                                        class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;" alt="Foto de perfil">
                                                                    <div>
                                                                        <h5 class="mb-0">${data.usuario.name} ${data.usuario.apellidos || ""}</h5>
                                                                        <small class="text-muted">${email}</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        `;
                                                        document.getElementById("amigo-id-input").value = data.usuario.id;
                                                        btnEnviar.disabled = false;
                                                    } else {
                                                        resultadoDiv.innerHTML = "<div class=\"mt-2 p-2 text-danger\">No se encontró ningún usuario con ese email</div>";
                                                        document.getElementById("amigo-id-input").value = "";
                                                        btnEnviar.disabled = true;
                                                    }
                                                })
                                                .catch(error => {
                                                    console.error("Error en la búsqueda:", error);
                                                    resultadoDiv.innerHTML = "<div class=\"mt-2 p-2 text-danger\">Error al conectar con el servidor</div>";
                                                    btnEnviar.disabled = true;
                                                });
                                        } else if (email.length > 0) {
                                            resultadoDiv.innerHTML = "<div class=\"mt-2 p-2 text-warning\">Por favor, introduce un email válido</div>";
                                            btnEnviar.disabled = true;
                                        } else {
                                            resultadoDiv.innerHTML = "";
                                            btnEnviar.disabled = true;
                                        }
                                    '>
                                </div>
                            </div>

                            <div id="resultado-busqueda"></div>
                            <div class="d-flex gap-2 justify-content-center">
                                <x-button type="submit" class="px-6 py-3" id="btn-enviar-solicitud" disabled>
                                    {{ __('Enviar') }}
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sección 2: Lista de amigos con buscador -->
                <div class="mt-5">
                    <div class="mis-amigos mt-5">
                        <div class=" mb-4">
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
                        <div style="height: 50%px; overflow-y: auto;">
                            @foreach($amigos as $amigo)
                            <div class="amigo-item p-3 hover-bg-light transition cursor-pointer"
                                data-nombre="{{ $amigo->name }} {{ $amigo->apellidos ?? '' }}"
                                data-email="{{ $amigo->email }}"
                                onclick="mostrarDetalleAmigo('{{ $amigo->id }}')">
                                <div class="d-flex align-items-center gap-3">
                                    <div>
                                        <img src="{{ asset($amigo->imgPerfil) }}"
                                            alt="Foto de perfil" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                                    </div>
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

            <!-- Página derecha - Detalles del amigo -->
            <div class="right-page">
                <div class="h-100">
                    <div class="d-flex flex-column align-items-center">
                        <div class="mb-4 position-relative" style="width: 100%; height: 150px; margin-left: -50%; margin-top: 5%;">
                            <div class="position-absolute" style="top: 0; left: 50%; transform: translateX(-50%); z-index: 2;">

                                <div id="detalle-imagen-container" class="text-center" style="display: none; position: absolute; top: 35px; left: 33%; transform: translateX(-50%)rotate(-6deg); z-index: 2; width: 150px;">
                                    <img id="detalle-imagen" alt="Foto de perfil"
                                        src=""
                                        style="object-fit: cover; width: 180px; height: 190px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">

                                    <h3 id="detalle-nombre" class="h5 text-center ms-4 mb-5 mt-2" aria-live="polite">Nombre del amigo</h3>
                                </div>

                                <div class="position-absolute" style="top: 0; left: 35%; transform: translateX(-50%); z-index: 1;">
                                    <img src="{{ asset('img/amigos/polaroid-amigos.png') }}"
                                        alt="Marco polaroid"
                                        style="max-height: 300px;">
                                </div>

                                <div class="position-absolute" style="top: 75px; right: -85px; z-index: 0;">
                                    <img src="{{ asset('img/amigos/flor-amigos.png') }}"
                                        alt="Decoración flor"
                                        style="max-height: 450px; margin-left: 65%;
  transform: rotate(7deg);">
                                </div>
                            </div>

                            <div id="detalle-logros" class="mb-4 w-100 text-center" style="display: none; margin-top: 90%;">
                                <h4 class="h5 mt-4 mb-3">Últimos Logros</h4>
                                <div class="d-flex justify-content-center gap-3" id="logros-amigo-container"></div>
                            </div>

                            <div id="detalle-reto-container" class="mb-4 w-100 text-center" style="display: none;">
                                <h4 class="h5 mb-2">Reto Anual</h4>
                                <p id="detalle-reto-texto" class="mt-2 mb-0"></p>
                            </div>

                            <div id="detalle-boton-container" class="mt-auto pt-2 h-100 text-center" style="display: none;">
                                <form action="{{ route('amigos.destroy', $amigo->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" class="px-6 py-3" id="btn-enviar-solicitud">
                                        {{ __('Borrar amistad') }}
                                    </x-button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function mostrarDetalleAmigo(amigoId) {
            document.getElementById('detalle-imagen').src = '';
            document.getElementById('logros-amigo-container').innerHTML = '';

            fetch(`/amigos/${amigoId}/detalle`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('No autorizado');
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('detalle-imagen-container').style.display = 'block';
                    document.getElementById('detalle-logros').style.display = 'block';
                    document.getElementById('detalle-reto-container').style.display = 'block';
                    document.getElementById('detalle-boton-container').style.display = 'block';

                    const imgElement = document.getElementById('detalle-imagen');
                    const imgPerfil = data.imgPerfil ? `${data.imgPerfil}` : '/images/default-user.jpg';
                    imgElement.src = imgPerfil;
                    imgElement.onerror = function() {
                        this.src = '/images/default-user.jpg';
                    };

                    document.getElementById('detalle-nombre').textContent = `${data.name} ${data.apellidos || ''}`;

                    const retoAnual = data.retoAnual || 0;
                    const librosLeidos = data.librosLeidosAnual || 0;
                    const porcentaje = retoAnual > 0 ? Math.min(100, Math.round((librosLeidos / retoAnual) * 100)) : 0;
                    document.getElementById('detalle-reto-texto').textContent =
                        `${librosLeidos} de ${retoAnual} libros (${porcentaje}%)`;

                    const logrosContainer = document.getElementById('logros-amigo-container');
                    if (data.logros && data.logros.length > 0) {
                        data.logros.forEach(logro => {
                            const logroDiv = document.createElement('div');
                            logroDiv.className = 'logro-miniatura';
                            logroDiv.innerHTML = `
                            <img src="{{ asset('') }}${logro.imagen}"
                                 alt="${logro.nombre}"
                                 class="img-fluid rounded"
                                 data-bs-toggle="tooltip"
                                 title="${logro.nombre} - ${logro.fecha}">
                        `;
                            logrosContainer.appendChild(logroDiv);
                        });

                        $('[data-bs-toggle="tooltip"]').tooltip();
                    } else {
                        logrosContainer.innerHTML = '<p class="text-muted">Este usuario no tiene logros aún</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
    @endsection