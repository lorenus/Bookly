@extends('layouts.app')

@section('content')

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

            <div class="p-6 text-gray-900 dark:text-gray-100">
                <ul>
                    <li>
                        <a href="{{ route('logros') }}" class="text-blue-500 hover:underline">
                            {{ __('Logros') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('listas.show', 'leyendo') }}" class="text-blue-500 hover:underline">
                            {{ __('Leyendo Actualmente') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('listas.show', 'leido') }}" class="text-blue-500 hover:underline">
                            {{ __('Mis Últimas Lecturas') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('listas.show', 'favoritos') }}" class="text-blue-500 hover:underline">
                            {{ __('Mis favoritos') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection