@extends('layouts.app')

@section('content')
<!-- Volver -->
<a href="{{ route('perfil') }}" class="position-fixed d-none d-lg-block" style="top: 100px; left: 40px; z-index: 1000;">
    <img src="{{ asset('img/elementos/volver.png') }}" alt="Volver" width="40" class="volver">
</a>

<div class="amigos-container">
    <!-- Main content -->
    <div class="amigos-libreta-wrapper">
        <!-- Fondo (solo movil) -->
        <div class="amigos-libreta-background"></div>

        <div class="amigos-libreta-content">
            <!-- Pagina izq. Formularios -->
            <div class="amigos-left-page">
                <!-- Section 1: Solicitar amistad -->
                <div class="amigos-section">
                    <h3 class="h3-responsive">Solicitar amistad:</h3>
                    <form method="POST" action="{{ route('amigos.store') }}" class="mb-4">
                        @csrf
                        <input type="hidden" name="amigo_id" id="amigo-id-input">

                        <div class="amigos-search-input ">
                            <input
                                type="email"
                                name="email"
                                id="buscar-email"
                                placeholder="Introduce el email del usuario"
                                required
                                class="buscar-email"
                                x-data
                                x-on:input.debounce.500ms='
                                const email = $event.target.value;
                                const resultadoDiv = document.getElementById("amigos-resultado-busqueda");
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
                                                    <div class="p-3">
                                                        <div class="d-flex align-items-center">
                                                            <img src="${data.usuario.imgPerfil ? "/storage/"+data.usuario.imgPerfil : "/images/default-user.jpg"}"
                                                                class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;" alt="Foto">
                                                            <div>
                                                                <h5 class="mb-0 h5-responsive">${data.usuario.name} ${data.usuario.apellidos || ""}</h5>
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

                        <div id="amigos-resultado-busqueda"></div>
                        <div class="amigos-form-actions">
                            <x-button type="submit" class="btn-amigos" id="btn-enviar-solicitud" disabled>
                                {{ __('Enviar') }}
                            </x-button>
                        </div>
                    </form>
                </div>

                <!-- Section 2: Lista amigos con buscador -->
                <div class="amigos-section mt-4">
                    <div class="mis-amigos">
                        <h3 class="h3-responsive">Mis Amigos ({{ $amigos->count() }})</h3>
                        <div class="amigos-search-input">
                            <input
                                type="text"
                                placeholder="Buscar entre mis amigos..."
                                x-data
                                x-on:input.debounce.300ms="
                                const search = $event.target.value.toLowerCase();
                                document.querySelectorAll('.amigo-item').forEach(item => {
                                    const name = item.dataset.nombre.toLowerCase();
                                    const email = item.dataset.email.toLowerCase();
                                    item.style.display = (name.includes(search) || email.includes(search)) ? '' : 'none';
                                })">
                        </div>

                        @if($amigos->count() > 0)
                        <div class="amigos-list-container">
                            @foreach($amigos as $amigo)
                            <div class="amigo-item"
                                data-nombre="{{ $amigo->name }} {{ $amigo->apellidos ?? '' }}"
                                data-email="{{ $amigo->email }}"
                                onclick="mostrarDetalleAmigo('{{ $amigo->id }}')">
                                <div class="amigo-item-content">
                                    <img src="{{ asset($amigo->imgPerfil) }}"
                                        alt="Foto de perfil" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                                    <div class="amigo-info">
                                        <h5 class="h5-responsive">{{ $amigo->name }} {{ $amigo->apellidos ?? '' }}</h5>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="amigos-empty-message">No tienes amigos aún. ¡Agrega algunos para compartir lecturas!</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Pagina derecha: detalles amigos -->
            <div class="amigos-right-page">
                <div class="amigos-detail-container">
                    <div class="amigos-polaroid-container">
                        <div id="amigos-detalle-imagen-container" class="amigos-polaroid-photo">
                            <img id="amigos-detalle-imagen" alt="Foto de perfil"
                                src=""
                                class="amigos-detail-image">
                            <h3 id="amigos-detalle-nombre" class="amigos-detalle-nombre" style="font-size: large; text-align: center; margin-left: -35%;">Nombre del amigo</h3>
                        </div>

                        <img src="{{ asset('img/amigos/polaroid-amigos.png') }}"
                            alt="Marco polaroid"
                            class="amigos-polaroid-frame">

                        <img src="{{ asset('img/amigos/flor-amigos.png') }}"
                            alt="Decoración flor"
                            class="amigos-floral-decoration">
                    </div>

                    <div id="amigos-detalle-logros" class="amigos-achievements-section">
                        <h3 class="h3-responsive">Últimos Logros</h3>
                        <div class="amigos-achievements-container" id="amigos-logros-amigo-container"></div>
                    </div>

                    <div id="amigos-detalle-reto-container" class="amigos-challenge-section">
                        <h3 class="h3-responsive">Reto Anual</h3>
                        <p id="amigos-detalle-reto-texto"></p>
                    </div>

                    <div id="amigos-detalle-boton-container" class="amigos-action-button" style="display: none;">
                        <form action="#" method="POST" id="amigos-delete-form">
                            @csrf
                            @method('DELETE')
                            <x-button type="submit" class="btn-amigos-eliminar">
                                {{ __('Borrar amistad') }}
                            </x-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function mostrarDetalleAmigo(amigoId) {
        document.getElementById('amigos-detalle-imagen').src = '';
        document.getElementById('amigos-logros-amigo-container').innerHTML = '';

        fetch(`/amigos/${amigoId}/detalle`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('No autorizado');
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('amigos-detalle-imagen-container').style.display = 'block';
                document.getElementById('amigos-detalle-logros').style.display = 'block';
                document.getElementById('amigos-detalle-reto-container').style.display = 'block';
                document.getElementById('amigos-detalle-boton-container').style.display = 'block';

                const imgElement = document.getElementById('amigos-detalle-imagen');
                const imgPerfil = data.imgPerfil ? `${data.imgPerfil}` : '/images/default-user.jpg';
                imgElement.src = imgPerfil;
                imgElement.onerror = function() {
                    this.src = '/images/default-user.jpg';
                };

                const deleteForm = document.getElementById('amigos-delete-form');
                deleteForm.action = `/amigos/${amigoId}`;

                document.getElementById('amigos-detalle-nombre').textContent = `${data.name} ${data.apellidos || ''}`;

                const retoAnual = data.retoAnual || 0;
                const librosLeidos = data.librosLeidosAnual || 0;
                const porcentaje = retoAnual > 0 ? Math.min(100, Math.round((librosLeidos / retoAnual) * 100)) : 0;
                document.getElementById('amigos-detalle-reto-texto').textContent =
                    `${librosLeidos} de ${retoAnual} libros (${porcentaje}%)`;

                const logrosContainer = document.getElementById('amigos-logros-amigo-container');
                if (data.logros && data.logros.length > 0) {
                    data.logros.forEach(logro => {
                        const logroDiv = document.createElement('div');
                        logroDiv.className = 'amigos-logro-miniatura';
                        logroDiv.innerHTML = `
                            <img src="${logro.imagen}"
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