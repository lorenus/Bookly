@extends('layouts.app')

@section('content')
@php
$oldLibroId = old('libro_id') ?? null; // Definimos la variable aquí
@endphp
<!-- Botón de volver -->
<a href="{{ route('perfil') }}" class="volver-btn" style="position: fixed;top: 100px;left: 40px;">
    <img src="{{ asset('img/elementos/volver.png') }}" alt="Volver">
</a>
<div class="prestar-container">

    <div class="prestar-paper-background">

        <div class="fila-prestar row mt-5 gx-5 align-items-center justify-content-center">
            <h3 class="text-2xl text-center">Prestar Libro</h3>
            <div class="col-3">
                <div class="portada-prestar">
                    <img id="portada-libro" src="" alt="Portada del libro" style="width: 150px; height: 220px; object-fit: cover; border: 1px solid #000; display: none;">
                </div>
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
                <form action="{{ route('prestamos.guardar') }}" method="POST" class="max-w-md mx-auto" data-old-libro="{{ old('libro_id') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="libro_id" class="block text-gray-700 mb-2">Libro:</label>
                        <select name="libro_id" id="libro_id" class="w-full px-3 py-2 border rounded select2" required>
                            <option value="">Buscar libro...</option>
                            @foreach($librosDisponibles as $libro)
                            <option value="{{ $libro->id }}" data-portada="{{ $libro->urlPortada ?? asset('img/default-book.png') }}">
                                {{ $libro->titulo }} ({{ $libro->autor }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="amigo_id" class="block text-gray-700 mb-2">Amigo:</label>
                        <select name="amigo_id" id="amigo_id" class="w-full px-3 py-2 border rounded select2" required>
                            <option value="">Buscar amigo...</option>
                            @foreach($amigos as $amigo)
                            <option value="{{ $amigo->id }}">
                                {{ $amigo->name }} {{ $amigo->lastname }}
                            </option>
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
                    <div class="text-center">
                        <x-button type="submit">
                            {{ __('Prestar') }}
                        </x-button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>

    <div class="lapiz"></div>
</div>


<script>
    $(document).ready(function() {
        // Inicializar Select2
        $('.select2').select2({
            language: {
                inputTooShort: function() {
                    return "Escribe para buscar...";
                },
                noResults: function() {
                    return "No se encontraron coincidencias";
                }
            },
            placeholder: "Buscar...",
            allowClear: true,
            width: '100%',
            minimumInputLength: 1,
            allowClear: false
        });


        // Manejar cambio de libro
        $('#libro_id').on('change', function() {
            var portadaUrl = $(this).find(':selected').data('portada');
            var imgElement = $('#portada-libro');

            if (portadaUrl) {
                imgElement.attr('src', portadaUrl).show();
            } else {
                imgElement.hide();
            }
        });

        // Cargar libro seleccionado anteriormente (si existe)
        var oldLibroId = "{{ $oldLibroId ?? '' }}"; // Usamos la variable PHP con valor por defecto
        if (oldLibroId && oldLibroId !== '') {
            var portadaUrl = $('#libro_id option[value="' + oldLibroId + '"]').data('portada');
            if (portadaUrl) {
                $('#portada-libro').attr('src', portadaUrl).show();
            }
            $('#libro_id').val(oldLibroId).trigger('change');
        }
    });
</script>
@endsection