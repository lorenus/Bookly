@extends('layouts.app')

@section('content')
<!-- Botón de volver -->
<a href="{{ route('perfil') }}" class="inline-block mb-6 text-blue-500 hover:underline">
    &larr; Volver
</a>

<ul>
    <li><a href="{{ route('listas.show', 'leyendo') }}">Leyendo Ahora</a></li>
    <li><a href="{{ route('listas.biblioteca') }}">Mi Biblioteca</a></li>
    <li><a href="{{ route('listas.show', 'leido') }}">Leídos</a></li>
    <li><a href="{{ route('listas.show', 'porLeer') }}">Por Leer</a></li>
    <li><a href="{{ route('listas.prestados') }}">Prestados</a></li>
</ul>
@endsection