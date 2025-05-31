@extends('layouts.app')

@section('content')
<a href="{{ route('perfil') }}" class="position-fixed d-none d-lg-block" style="top: 100px; left: 40px; z-index: 1000;">
    <img src="{{ asset('img/elementos/volver.png') }}" alt="Volver" width="40" class="volver">
</a>

<div class="container-fluid container-lg">
    <div class="logros-container position-relative">
        <div class="corcho-background"></div>
        <div class="titulo-logros text-center">
            <img src="{{ asset('img/logros/titol.png') }}" alt="Título Logros">
        </div>
        <div class="logros-grid">
            {{-- Itera sobre TODOS los logros disponibles en el sistema --}}
            @foreach($logros as $logro)
                @php
                   
                    $logroNumber = $loop->index + 1;
                    $descripcion = ''; // Variable para la descripción del logro
                    $estado = 'No desbloqueado aún'; // Estado por defecto

                    $isDesbloqueado = in_array($logro->id, $userLogrosIds ?? []); // Usar ?? [] para evitar error si userLogrosIds no existe
                    $fechaCompletado = null;

                    if ($isDesbloqueado) {
                        
                        $logroDelUsuario = $user->logros->where('id', $logro->id)->first();
                        if ($logroDelUsuario && isset($logroDelUsuario->pivot->completado_en)) {
                            try {
                                $fecha = is_object($logroDelUsuario->pivot->completado_en)
                                    ? $logroDelUsuario->pivot->completado_en
                                    : new DateTime($logroDelUsuario->pivot->completado_en);
                                $fechaCompletado = $fecha->format('d/m/Y');
                                $estado = 'Desbloqueado: ' . $fechaCompletado;
                            } catch (Exception $e) {
                                $estado = 'Desbloqueado (fecha no disponible)';
                            }
                        } else {
                             $estado = 'Desbloqueado';
                        }
                    }
                @endphp

                {{-- Asigna la descripción del logro usando el nombre del logro --}}
                @switch($logro->nombre)
                    @case('Primer paso')
                        @php $descripcion = '¡Bienvenido al club de lectura!'; @endphp
                    @break
                    @case('Lector Novato')
                        @php $descripcion = 'Has leído 5 libros'; @endphp
                    @break
                    @case('Explorador Literario')
                        @php $descripcion = 'Has alcanzado los 15 libros'; @endphp
                    @break
                    @case('Bibliófilo Dedicado')
                        @php $descripcion = '¡30 libros leídos!'; @endphp
                    @break
                    @case('Maratón Semanal')
                        @php $descripcion = '3 libros leídos en una semana'; @endphp
                    @break
                    @case('Constancia Lectora')
                        @php $descripcion = '4 semanas leyendo sin parar'; @endphp
                    @break
                    @case('Enero Lector')
                        @php $descripcion = 'Libro leído en enero'; @endphp
                    @break
                    @case('Reto Anual Completado')
                        @php $descripcion = 'Has completado tu reto anual'; @endphp
                    @break
                    @default
                        {{-- Si el logro tiene una columna 'descripcion' en la DB, úsala --}}
                        @php $descripcion = $logro->descripcion ?? 'Descripción no disponible'; @endphp
                @endswitch

                {{-- Botón que activa el modal con la información del logro --}}
                <button type="button"
                        class="logro-item"
                        onclick="mostrarModal('{{ addslashes($logro->nombre) }}', '{{ addslashes($descripcion) }}', '{{ addslashes($estado) }}')"
                        style="background: none; border: none; padding: 0;">

                    @if($isDesbloqueado)
                        <img src="{{ asset('img/logros/desbloqueado'.$logroNumber.'.png') }}"
                            alt="{{ $logro->nombre }}"
                            class="logro-imagen desbloqueado">
                    @else
                        <img src="{{ asset('img/logros/bloqueado'.$logroNumber.'.png') }}"
                            alt="Logro bloqueado"
                            class="logro-imagen bloqueado">
                    @endif
                </button>
            @endforeach
        </div>
    </div>
</div>

<div id="modalLogro" class="modal-logro">
    <div class="modal-content">
        <button type="button" class="close-modal" onclick="cerrarModal()" onkeydown="if(event.key==='Enter'||event.key===' '){cerrarModal();}" style="background: none; border: none; padding: 0;">
            <img src="{{ asset('img/elementos/cerrar.png') }}" alt="Cerrar" width="30">
        </button>
        <h4 id="modalTitulo">Información del logro</h4>
        <p id="modalDescripcion"></p>
        <small id="modalEstado"></small>
    </div>
</div>

<script>
    // Función para mostrar el modal con la información del logro
    function mostrarModal(titulo, descripcion, estado) {
        document.getElementById('modalTitulo').textContent = titulo;
        document.getElementById('modalDescripcion').textContent = descripcion;
        document.getElementById('modalEstado').textContent = estado;
        document.getElementById('modalLogro').style.display = 'flex'; // Muestra el modal
    }

    // Función para cerrar el modal
    function cerrarModal() {
        document.getElementById('modalLogro').style.display = 'none'; // Oculta el modal
    }

    // Cierra el modal si se hace clic fuera de su contenido
    window.onclick = function(event) {
        const modal = document.getElementById('modalLogro');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>
@endsection