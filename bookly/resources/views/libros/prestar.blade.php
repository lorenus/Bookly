@extends('layouts.app')

@section('content')
<!-- Botón de volver -->
<a href="{{ route('perfil') }}" class="volver-btn" style="position: fixed;top: 100px;left: 40px;">
    <img src="{{ asset('img/elementos/volver.png') }}" alt="Volver">
</a>
<div class="prestar-container">

    <div class="prestar-paper-background">

        <div class="fila-prestar row mt-5 gx-5 align-items-center justify-content-center">
            <h3 class="text-2xl text-center">Prestar Libro</h3>
            <div class="col-3">
                <div class="portada-prestar"></div>
            </div>
            <div class="col-9">

                @if($librosDisponibles->isEmpty())
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4">
                    <p>No tienes libros disponibles para prestar. Primero debes comprar libros en tu biblioteca.</p>
                </div>
                <a href="{{ route('listas.biblioteca') }}" class="text-blue-500 hover:text-blue-700">
                    Ir a mi biblioteca
                </a>
                @else
                <form action="{{ route('prestamos.guardar') }}" method="POST" class="max-w-md">
                    @csrf

                    <div class="mb-4">
                        <label for="libro_id" class="block text-gray-700 mb-2">Libro:</label>
                        <select name="libro_id" id="libro_id" class="w-full px-3 py-2 border rounded" required>
                            <option value="">Selecciona un libro</option>
                            @foreach($librosDisponibles as $libro)
                            <option value="{{ $libro->id }}">
                                {{ $libro->titulo }} ({{ $libro->autor }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="amigo_id" class="block text-gray-700 mb-2">Amigo:</label>
                        <select name="amigo_id" id="amigo_id" class="w-full px-3 py-2 border rounded" required>
                            <option value="">Selecciona un amigo</option>
                            @foreach($amigos as $amigo)
                            <option value="{{ $amigo->id }}">{{ $amigo->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="fecha_devolucion" class="block text-gray-700 mb-2">Fecha de devolución:</label>
                        <input type="date" name="fecha_devolucion" id="fecha_devolucion"
                            class="w-full px-3 py-2 border rounded"
                            min="{{ now()->addDay()->format('Y-m-d') }}"
                            required>
                    </div>

                    <x-button type="submit" class="px-6 py-3">
                        {{ __('Prestar') }}
                    </x-button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <div class="lapiz"></div>
</div>
<div class="container mx-auto px-4 py-8">

</div>
@endsection