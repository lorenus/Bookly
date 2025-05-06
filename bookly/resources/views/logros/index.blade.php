@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Mis Logros</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($logros as $logro)
        @php
        $progreso = $logro->pivot->progreso ?? 0;
        $completado = $logro->pivot->completado ?? false;
        $porcentaje = min(100, ($progreso / $logro->requisito) * 100);
        @endphp

        <div class="border rounded-lg p-6 shadow-sm {{ $completado ? 'bg-green-50 border-green-200' : 'bg-gray-50' }}">
            <!-- ... resto del código ... -->

            @if($completado)
            <div class="text-green-500 font-medium">
                <i class="fas fa-check-circle mr-2"></i> Completado
            </div>
            @else
            <div class="pt-2">
                <div class="flex justify-between text-sm mb-1">
                    <span>Progreso</span>
                    <span>{{ $progreso }}/{{ $logro->requisito }}</span>
                </div>
                <!-- <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-green-500 h-2.5 rounded-full"
                        style="width: {{ $porcentaje }}%"></div>
                </div> -->
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endsection