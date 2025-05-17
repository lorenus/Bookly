@extends('layouts.app')

@section('content')
<!-- Botón de volver -->
<a href="{{ route('perfil') }}" class="position-fixed" style="top: 100px; left: 40px; z-index: 1000;">
    <img src="{{ asset('img/elementos/volver.png') }}" alt="Volver" width="40" class="volver">
</a>

<div class="mensajes-container container-fluid d-flex justify-content-center align-items-end">
    <div class="rounded-4 shadow-sm" style="width: 70%; height: 70vh; margin-left: 15%;">
        <div class="h-100 d-flex flex-column p-4">
            <!-- Header -->
            <div class="mensajes-header text-center py-3">
                <h1 class="h3 mb-0">Notificaciones</h1>
            </div>

            <!-- Lista con scroll -->
            <div class="list-scrollable flex-grow-1 overflow-auto pe-2">
                @forelse ($notificaciones as $notif)
                <div class="mb-3 border-0 position-relative">
                    <!-- Indicador de no leída -->
                    @if(!$notif->leida)
                    <span class="position-absolute top-0 start-0 translate-middle p-2 bg-danger border border-light rounded-circle" style="width: 12px; height: 12px;"></span>
                    @endif

                    <div class="cuerpo-mensaje">
                        <div class="d-flex">
                            <img src="{{ $notif->remitente->imgPerfil ?? asset('images/default-user.jpg') }}"
                                alt="{{ $notif->remitente->name }}"
                                class="rounded-circle me-3"
                                width="50"
                                height="50"
                                style="object-fit: cover;">

                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h5 class="mb-1">{{ $notif->remitente->name }}</h5>

                                    <small class="text-muted">{{ $notif->created_at->format('d/m/Y') }}</small>
                                </div>
                                <div class="d-flex align-items-start">

                                    @unless($notif->estado === App\Models\Notificacion::ESTADO_PENDIENTE)
                                    <small class="mb-3 {{ $notif->estado === App\Models\Notificacion::ESTADO_ACEPTADA ? 'text-success' : 'text-danger' }}">
                                        {{ ucfirst($notif->estado) }}
                                    </small>
                                    @endunless
                                </div>
                                <p class="text-muted mb-2">{{ $notif->contenido }}</p>

                                <div class="d-flex justify-content-between align-items-center mt-2 position-relative">
                                    <!-- Espacio izquierdo vacío para balancear -->
                                    <div style="width: 40px;"></div>

                                    <!-- Botones Aceptar/Rechazar centrados -->
                                    <div class="d-flex gap-2 justify-content-center position-absolute start-50 translate-middle-x">
                                        @if ($notif->estado === App\Models\Notificacion::ESTADO_PENDIENTE)
                                        <form method="POST" action="{{ route('mensajes.aceptar', $notif) }}" class="d-inline">
                                            @csrf
                                            <x-button-green type="submit" class="px-4 py-2">
                                                {{ __('Aceptar') }}
                                            </x-button-green>
                                        </form>
                                        <form method="POST" action="{{ route('mensajes.rechazar', $notif) }}" class="d-inline">
                                            @csrf
                                            <x-button type="submit" class="px-4 py-2">
                                                {{ __('Rechazar') }}
                                            </x-button>
                                        </form>
                                        @endif
                                    </div>

                                    <!-- Botón Eliminar a la derecha -->
                                    <form method="POST" action="{{ route('mensajes.eliminar', $notif) }}" class="ms-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link p-0 border-0 bg-transparent"
                                            onclick="return confirm('¿Estás seguro de querer eliminar esta notificación?')">
                                            <button type="submit"
                                                class="btn btn-link p-0 border-0 bg-transparent"
                                                onclick="return confirm('¿Estás seguro de querer eliminar esta notificación?')"
                                                onmouseover="this.querySelector('img').style.filter='brightness(1)'"
                                                onmouseout="this.querySelector('img').style.filter='brightness(0.8)'"
                                                onfocus="this.querySelector('img').style.filter='brightness(1)'"
                                                style="background: none;">
                                                <img src="{{ asset('img/elementos/eliminar.png') }}"
                                                    alt="Eliminar"
                                                    width="40"
                                                    height="auto"
                                                    style="filter: brightness(0.8); transition: filter 0.3s;">
                                            </button>
                                        </button>
                                    </form>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="text-center my-2">
                        <img src="{{ asset('img/mensajes/separador.png') }}" alt="separador" class="img-fluid" style="max-height: 30px; width: 100%">
                    </div>
                </div>
                @empty
                <div class="alert alert-info text-center">
                    No tienes notificaciones.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
