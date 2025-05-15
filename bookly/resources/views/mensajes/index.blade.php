@extends('layouts.app')

@section('content')
<!-- Botón de volver -->
<a href="{{ route('perfil') }}" class="position-fixed" style="top: 100px; left: 40px; z-index: 1000;">
    <img src="{{ asset('img/elementos/volver.png') }}" alt="Volver" width="40" class="volver">
</a>

<div class="mensajes-container container-fluid d-flex justify-content-center align-items-end"">
    <div class=" rounded-4 shadow-sm" style="width: 70%; height: 70vh; margin-left: 15%;">
    <div class="h-100 d-flex flex-column p-4">
        <!-- Header -->
        <div class="mensajes-header text-center py-3">
            <h1 class="h3 mb-0">Notificaciones</h1>
        </div>

        <!-- Lista con scroll -->
        <div class="list-scrollable flex-grow-1 overflow-auto pe-2">
            @forelse ($notificaciones as $notif)
            <div class="mb-3 border-0">
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
                                <h5 class="card-title mb-1">{{ $notif->remitente->name }}</h5>
                                <small class="text-muted">{{ $notif->created_at->format('d/m/Y') }}</small>
                            </div>
                            <p class="card-text text-muted mb-2">{{ $notif->contenido }}</p>

                            @if ($notif->estado === App\Models\Notificacion::ESTADO_PENDIENTE)
                            <div class="d-flex gap-2 mt-2">
                                <form method="POST" action="{{ route('mensajes.aceptar', $notif) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">
                                        Aceptar
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('mensajes.rechazar', $notif) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        Rechazar
                                    </button>
                                </form>
                            </div>
                            @else
                            <span class="badge {{ $notif->estado === App\Models\Notificacion::ESTADO_ACEPTADA ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($notif->estado) }}
                            </span>
                            @endif
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