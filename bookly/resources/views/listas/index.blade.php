@extends('layouts.app')

@section('content')
<div class="lists-container relative min-h-screen-misListas overflow-hidden">
    <!-- Fondo decorativo (flores) -->
    <div class="flowers-decoration"></div>

    <!-- Botón de volver -->
    <a href="{{ route('perfil') }}" class="volver-btn" style="position: fixed;top: 100px;left: 40px;">
        <img src="{{ asset('img/elementos/volver.png') }}" alt="Volver">
    </a>

    <!-- Contenedor principal -->
    <div class="notebook-wrapper">
        <!-- Libreta -->
        <div class="notebook">
            <ul class="notebook-items">
                <li><a href="{{ route('listas.show', 'leyendo') }}">Leyendo Ahora</a></li>
                <li><a href="{{ route('listas.biblioteca') }}">Mi Biblioteca</a></li>
                <li><a href="{{ route('listas.show', 'leido') }}">Leídos</a></li>
                <li><a href="{{ route('listas.show', 'porLeer') }}">Por Leer</a></li>
                <li><a href="{{ route('listas.prestados') }}">Prestados</a></li>
            </ul>
        </div>
    </div>

    <!-- Decoración de papel -->
    <div class="paper-decoration"></div>
</div>
@endsection
