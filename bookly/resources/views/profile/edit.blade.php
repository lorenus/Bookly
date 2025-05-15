@extends('layouts.app')

@section('content')
<!-- Botón de volver -->
<a href="{{ route('perfil') }}" class="volver-btn" style="position: fixed;top: 100px;left: 40px;">
    <img src="{{ asset('img/elementos/volver.png') }}" alt="Volver">
</a>

<div class="editar-container">
    <div class="editar-paper-background">
        <div class="contenido-edit">
            <div class="text-center pt-3 mb-5">
                <h3>Editar Perfil</h3>
            </div>
            @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="row align-items-center mb-3">
                    <!-- Columna 1 - Icono -->
                    <div class="col-auto d-flex flex-column justify-content-center">
                        <img src="{{ asset('img/editarPerfil/imagen.png') }}" alt="Icono foto" class="img-fluid" style="width: 75px;">
                    </div>

                    <!-- Columna 2 - Label e Input -->
                    <div class="col">
                        <div class="row align-items-center mb-1">
                            <div class="col-md-4">
                                <label class="form-label mb-0">Foto de perfil</label>
                            </div>
                            <div class="col-md-8">
                                <!-- Contenedor del input file personalizado -->
                                <div class="file-select-wrapper">
                                    <button type="button" class="btn-file-select">
                                        Seleccionar archivo
                                    </button>
                                    <input type="file" name="imgPerfil" id="imgPerfil" class="file-select-input">
                                </div>

                                <!-- Mostrar nombre del archivo truncado -->
                                <div id="fileNameDisplay" class="small text-muted mt-2 text-truncate" style="max-width: 250px;"></div>

                                @error('imgPerfil')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contraseña -->
                <div class="row align-items-center mb-3">
                    <!-- Columna 1 - Icono -->
                    <div class="col-auto d-flex flex-column justify-content-center">
                        <img src="{{ asset('img/editarPerfil/contrasenya.png') }}" alt="Icono contraseña" class="img-fluid" style="width: 75px;">
                    </div>

                    <!-- Columna 2 - Contraseña y confirmación -->
                    <div class="col">
                        <div class="row">
                            <!-- Nueva contraseña -->
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="password" class="form-label mb-0">Nueva contraseña</label>
                                    <input type="password" name="password" id="password" class="form-control">
                                    @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Confirmar contraseña -->
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="password_confirmation" class="form-label mb-0">Confirmar contraseña</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reto anual -->
                <div class="row align-items-center mb-3">
                    <!-- Columna 1 - Icono -->
                    <div class="col-auto d-flex flex-column justify-content-center" style="height: 80px;">
                        <img src="{{ asset('img/editarPerfil/libro.png') }}" alt="Icono reto" class="img-fluid" style="width: 75px;">
                    </div>

                    <!-- Columna 2 - Label e Input -->
                    <div class="col">
                        <div class="row align-items-center mb-2">
                            <div class="col-md-4">
                                <label for="retoAnual" class="form-label mb-0">Reto anual</label>
                            </div>
                            <div class="col-md-8">
                                <input type="number" name="retoAnual" id="retoAnual"
                                    value="{{ old('retoAnual', Auth::user()->retoAnual ?? 12) }}"
                                    min="1" max="1000" class="form-control">
                                @error('retoAnual')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Borrar lista -->
                <div class="row align-items-center mb-4">
                    <!-- Columna 1 - Icono -->
                    <div class="col-auto d-flex flex-column justify-content-center" style="height: 80px;">
                        <img src="{{ asset('img/editarPerfil/borrarLista.png') }}" alt="Icono borrar" class="img-fluid" style="width: 75px;">
                    </div>

                    <!-- Columna 2 - Label e Select -->
                    <div class="col">
                        <div class="row align-items-center mb-2">
                            <div class="col-md-4">
                                <label for="lista_a_borrar" class="form-label mb-0">Vaciar lista</label>
                            </div>
                            <div class="col-md-8">
                                <select name="lista_a_borrar" id="lista_a_borrar" class="form-select">
                                    <option value="">Selecciona...</option>
                                    <option value="leyendo">Leyendo Actualmente</option>
                                    <option value="leido">Mis Últimas Lecturas</option>
                                    <option value="porLeer">Para Leer</option>
                                    <option value="favoritos">Favoritos</option>
                                </select>
                                @error('lista_a_borrar')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="d-flex flex-column align-items-start pt-3" style="gap: 15px;">
                    <button type="submit" class="btn btn-outline-primary px-4">
                        Guardar cambios
                    </button>

                    <button type="button" onclick="confirmarEliminacion()" class="btn btn-outline-danger px-4">
                        Eliminar cuenta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="flor-editar"></div>

<script>
    function confirmarEliminacion() {
        if (confirm('¿Estás seguro de que quieres eliminar tu cuenta? Esta acción no se puede deshacer.')) {
            document.getElementById('deleteAccountForm').submit();
        }
    }

    function strLimit(text, limit = 100, end = '...') {
        if (!text) return '';
        if (text.length <= limit) return text;

        return text.substring(0, limit) + end;
    }

    // Uso con el input file
    document.getElementById('imgPerfil').addEventListener('change', function(e) {
        const fileName = this.files[0]?.name || 'Ningún archivo seleccionado';
        document.getElementById('fileNameDisplay').textContent = strLimit(fileName, 20);
    });
</script>