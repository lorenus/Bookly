@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Prestar Libro</h1>

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

            <div class="flex items-center justify-between">
                <a href="{{ url()->previous() }}" class="text-gray-600 hover:text-gray-800">Cancelar</a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded">
                    Prestar Libro
                </button>
            </div>
        </form>
    @endif
</div>
@endsection