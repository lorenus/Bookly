@extends('layouts.app')

@section('content')
<!-- Botón de volver -->
<a href="{{ route('perfil') }}" class="position-fixed" style="top: 100px; left: 40px; z-index: 1000;">
    <img src="{{ asset('img/elementos/volver.png') }}" alt="Volver" width="40" class="volver">
</a>

<div class="container px-4 py-8 mx-auto">
    <!-- Contenedor principal con fondo de corcho -->
    <div class="logros-container">
        <!-- Grid de logros -->
        <div class="logros-grid">
            @foreach($logros as $index => $logro)
                @php
                    $postItNumber = $index + 1;
                    $estado = 'No desbloqueado aún';
                    
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

                <div class="logro-item" onclick="mostrarModal('{{ addslashes($logro->nombre) }}', '{{ addslashes($logro->descripcion) }}', '{{ addslashes($estado) }}')">
                    <!-- Post-it -->
                    <img src="{{ asset('img/logros/post'.$postItNumber.'.png') }}"
                         alt="Post-it"
                         class="logro-postit">

                    <!-- Contenido del logro -->
                    <div class="logro-content">
                        @if($logro->users->isNotEmpty())
                            <img src="{{ asset('img/logros/logro'.$postItNumber.'.png') }}"
                                 alt="{{ $logro->nombre }}"
                                 class="logro-imagen">
                        @else
                            <div class="logro-bloqueado">
                                🔒
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Modal para mostrar la información -->
<div id="modalLogro" class="modal-logro">
    <div class="modal-content">
        <span class="close-modal" onclick="cerrarModal()">&times;</span>
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

    // Cerrar modal al hacer clic fuera del contenido
    window.onclick = function(event) {
        const modal = document.getElementById('modalLogro');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>
@endsection