@extends('layouts.app')

@section('content')
    <h1>{{ $titulo }}</h1>
    
    <div class="libros-container">
    @foreach ($libros as $libro)
    <div class="libro mb-4 p-4 border rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
        <a href="{{ route('libro.show', $libro->id) }}" class="block">
            <h3 class="text-xl font-bold text-blue-600 dark:text-blue-400 hover:underline">
                {{ $libro->titulo }}
            </h3>
            <p class="text-gray-600 dark:text-gray-300">{{ $libro->autor }}</p>
        </a>
    </div>
@endforeach
</div>
@endsection