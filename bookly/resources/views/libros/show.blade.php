@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $libro->titulo }}</h1>
        <p class="text-xl text-gray-600 dark:text-gray-300 mt-2">{{ $libro->autor }}</p>
        
        @if($libro->portada)
        <img src="{{ $libro->portada }}" alt="Portada" class="mt-4 max-w-xs rounded-lg">
        @endif
        
        <div class="mt-6">
            <h2 class="text-xl font-semibold dark:text-white">Tu valoración:</h2>
            <div class="flex items-center mt-2">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="w-6 h-6 {{ $i <= ($valoracionUsuario ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" 
                         fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endfor
            </div>
        </div>

        <div class="mt-8">
            <a href="{{ url()->previous() }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Volver atrás
            </a>
        </div>
    </div>
</div>
@endsection