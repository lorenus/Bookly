@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Mis Logros</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($logros as $logro)
        <div class="border rounded-lg p-6 shadow-sm {{ $logro->pivot->completado ? 'bg-'.$logro->color.'-50 border-'.$logro->color.'-200' : 'bg-gray-50' }}">
            <div class="flex items-center mb-4">
                <div class="text-3xl mr-4 text-{{ $logro->color }}-500">
                    <i class="fas fa-{{ $logro->icono }}"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg">{{ $logro->nombre }}</h3>
                    <p class="text-gray-600">{{ $logro->descripcion }}</p>
                </div>
            </div>

            @if($logro->pivot->completado)
            <div class="text-green-500 font-medium">
                <i class="fas fa-check-circle mr-2"></i> Completado
            </div>
            @else
            <div class="pt-2">
                <div class="flex justify-between text-sm mb-1">
                    <span>Progreso</span>
                    <span>{{ $logro->pivot->progreso }}/{{ $logro->requisito }}</span>
                </div>
                <div class="w-3/4 bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                    <div class="bg-blue-600 h-2.5 rounded-full"
                        style="width: <?php echo min(100, ($logro->pivot->progreso / $logro->requisito) * 100); ?>%">
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endsection