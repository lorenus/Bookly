@extends('layouts.app')

@section('content')
@php
$selectedLibroId = $preselectedLibroId ?? old('libro_id') ?? null;
$selectedAmigoId = $preselectedAmigoId ?? old('amigo_id') ?? null;
@endphp
<!-- Botón de volver -->
<a href="{{ route('perfil') }}" class="position-fixed d-none d-lg-block" style="top: 100px; left: 40px; z-index: 1000;">
    <img src="{{ asset('img/elementos/volver.png') }}" alt="Volver" width="40" class="volver">
</a>

<div class="prestar-container">

    <div class="prestar-paper-background">

        <div class="fila-prestar row mt-5 gx-5 align-items-center justify-content-center">
            <h3 class="text-2xl text-center">Prestar Libro</h3>
            <div class="col-3 columna-prestar1">
                <div class="portada-prestar">
                    <img id="portada-libro" src="" alt="Portada del libro" style="width: 150px; height: 220px; object-fit: cover; border: 1px solid #000; display: none;">
                </div>
            </div>
            <div class="col-9 columna-prestar2">

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
                            <option value="{{ $libro->id }}" data-portada="{{ $libro->urlPortada ?? asset('img/default-book.png') }}"
                                {{ $selectedLibroId == $libro->id ? 'selected' : '' }}>
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
                            <option value="{{ $amigo->id }}"
                                {{ $selectedAmigoId == $amigo->id ? 'selected' : '' }}>
                                {{ $amigo->name }} {{ $amigo->apellidos }}
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
            minimumInputLength: 0
        });

        var selectedLibroId = "{{ $selectedLibroId ?? '' }}";
        var selectedAmigoId = "{{ $selectedAmigoId ?? '' }}";

        if (selectedLibroId) {
            $('#libro_id').val(selectedLibroId).trigger('change');
            var portadaUrl = $('#libro_id option[value="' + selectedLibroId + '"]').data('portada');
            if (portadaUrl) {
                $('#portada-libro').attr('src', portadaUrl).show();
            }
        }

        if (selectedAmigoId) {
            $('#amigo_id').val(selectedAmigoId).trigger('change');
        }

        $('#libro_id').on('change', function() {
            var portadaUrl = $(this).find(':selected').data('portada');
            var imgElement = $('#portada-libro');

            if (portadaUrl) {
                imgElement.attr('src', portadaUrl).show();
            } else {
                imgElement.hide();
            }
        });
    });
</script>
@endsection