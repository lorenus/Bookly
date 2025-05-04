@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Notificaciones</h1>

    <div class="space-y-4">
        @forelse ($notificaciones as $notif)
            <div class="p-4 border rounded-lg shadow-sm {{ $notif->estado === App\Models\Notificacion::ESTADO_PENDIENTE ? 'bg-blue-50' : 'bg-white' }}">
                <div class="flex items-start">
                    <img 
                        src="{{ $notif->remitente->foto_perfil ?? asset('images/default-user.jpg') }}" 
                        class="w-10 h-10 rounded-full mr-3"
                        alt="{{ $notif->remitente->name }}"
                    >
                    <div class="flex-1">
                        <div class="flex justify-between">
                            <h3 class="font-semibold">{{ $notif->remitente->name }}</h3>
                            <span class="text-sm text-gray-500">
                                {{ $notif->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <p class="mt-1">{{ $notif->contenido }}</p>
                        
                        @if ($notif->estado === App\Models\Notificacion::ESTADO_PENDIENTE)
                            <div class="flex space-x-2 mt-2">
                                <form method="POST" action="{{ route('mensajes.aceptar', $notif) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-green-600 rounded text-sm">
                                        Aceptar
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('mensajes.rechazar', $notif) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-red-600 rounded text-sm">
                                        Rechazar
                                    </button>
                                </form>
                            </div>
                        @else
                            <span class="text-sm mt-2 {{ $notif->estado === App\Models\Notificacion::ESTADO_ACEPTADA ? 'text-green-600' : 'text-red-600' }}">
                                {{ ucfirst($notif->estado) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="text-gray-500">No tienes notificaciones.</p>
        @endforelse
    </div>
</div>
@endsection