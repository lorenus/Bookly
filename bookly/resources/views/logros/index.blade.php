@extends('layouts.app')

@section('content')
<!-- Botón de volver -->
<a href="{{ route('perfil') }}" class="position-fixed" style="top: 100px; left: 40px; z-index: 1000;">
    <img src="{{ asset('img/elementos/volver.png') }}" alt="Volver" width="40" class="volver">
</a>

<div class="container px-4 py-8 mx-auto">
    <!-- Contenedor principal -->
    <div class="logros-container">
        <!-- Grid de logros -->
        <div class="logros-grid">
            @foreach($logros as $index => $logro)
            @php
            $logroNumber = $index + 1;
            $estado = 'No desbloqueado aún';
            $descripcion = '';
            @endphp

            @switch($index)
                @case(0)
                   @php $descripcion = '¡Bienvenido al club de lectura!'; @endphp
                @break
                @case(1)
                    @php $descripcion = 'Has leído 5 libros'; @endphp
                @break
                @case(2)
                    @php $descripcion = 'Has alcanzado los 15 libros'; @endphp
                @break
                @case(3)
                    @php $descripcion = '¡30 libros leídos!'; @endphp
                @break
                @case(4)
                    @php $descripcion = '3 libros leídos en una semana'; @endphp
                @break
                @case(5)
                    @php $descripcion = '4 semanas leyendo sin parar'; @endphp
                @break
                @case(6)
                    @php $descripcion = 'Libro leído en enero'; @endphp
                @break
                @case(7)
                    @php $descripcion = 'Has completado tu reto anual'; @endphp
                @break
                @endswitch

            @php
            if($logro->users->isNotEmpty() && isset($logro->users[0]->pivot->completado_en)) {
            try {
            $fecha = is_object($logro->users[0]->pivot->completado_en)
            ? $logro->users[0]->pivot->completado_en
            : new DateTime($logro->users[0]->pivot->completado_en);
            $estado = 'Desbloqueado: ' . $fecha->format('d/m/Y');
            } catch (Exception $e) {
            $estado = 'Desbloqueado (fecha no disponible)';
            }
            }
            @endphp

            <div class="logro-item" onclick="mostrarModal('{{ addslashes($logro->nombre) }}', '{{ addslashes($descripcion) }}', '{{ addslashes($estado) }}')">
                <!-- Mostrar imagen bloqueada o desbloqueada según estado -->
                @if($logro->users->isNotEmpty())
                <img src="{{ asset('img/logros/desbloqueado'.$logroNumber.'.png') }}"
                    alt="{{ $logro->nombre }}"
                    class="logro-imagen desbloqueado">
                @else
                <img src="{{ asset('img/logros/bloqueado'.$logroNumber.'.png') }}"
                    alt="Logro bloqueado"
                    class="logro-imagen bloqueado">
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Modal para mostrar la información -->
<div id="modalLogro" class="modal-logro">
    <div class="modal-content">
        <span class="close-modal" onclick="cerrarModal()">
            <img src="{{ asset('img/elementos/cerrar.png') }}" alt="Cerrar" width="30">
        </span>
        <h4 id="modalTitulo"></h4>
        <p id="modalDescripcion"></p>
        <small id="modalEstado"></small>
    </div>
</div>



<script>
    function mostrarModal(titulo, descripcion, estado) {
        document.getElementById('modalTitulo').textContent = titulo;
        document.getElementById('modalDescripcion').textContent = descripcion;
        document.getElementById('modalEstado').textContent = estado;
        document.getElementById('modalLogro').style.display = 'flex';
    }

    function cerrarModal() {
        document.getElementById('modalLogro').style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('modalLogro');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>
@endsection