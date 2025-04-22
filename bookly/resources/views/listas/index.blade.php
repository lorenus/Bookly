@extends('layouts.app')

@section('content')

<ul>
    <li><a href="{{ route('listas.show', 'leyendo') }}">Leyendo Ahora</a></li>
    <li><a href="{{ route('miBiblioteca') }}">Mi Biblioteca</a></li>
    <li><a href="{{ route('listas.show', 'leido') }}">Leídos</a></li>
    <li><a href="{{ route('listas.show', 'porLeer') }}">Por Leer</a></li>
    <li><a href="{{ route('prestados') }}">Prestados</a></li>
</ul>
@endsection