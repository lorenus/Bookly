@extends('layouts.app')

@section('content')
    <h1>{{ $titulo }}</h1>
    
    <div class="libros-container">
    @foreach ($libros as $libro)
        <div class="libro">
            <h3>{{ $libro->titulo }}</h3>
            <p>{{ $libro->autor }}</p>
            @if ($libro->usuarios[0]->pivot->valoracion)
                <p>Valoración: {{ $libro->usuarios[0]->pivot->valoracion }} estrellas</p>
            @endif
        </div>
    @endforeach
</div>
@endsection