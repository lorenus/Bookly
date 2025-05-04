@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h1 class="text-2xl font-bold mb-6">Editar Perfil</h1>

                <!-- Formulario de actualización -->
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Foto de perfil -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto de perfil</label>
                        <div class="flex items-center space-x-4">
                            <img src="{{ Auth::user()->imgPerfil ? asset('storage/'.Auth::user()->imgPerfil) : asset('images/default-user.jpg') }}" 
                                 alt="Foto de perfil" 
                                 class="h-16 w-16 rounded-full object-cover">
                            <input type="file" name="imgPerfil" 
                                   class="block w-full text-sm text-gray-500
                                   file:mr-4 file:py-2 file:px-4
                                   file:rounded-md file:border-0
                                   file:text-sm file:font-semibold
                                   file:bg-blue-50 file:text-blue-700
                                   hover:file:bg-blue-100">
                        </div>
                        @error('imgPerfil')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contraseña -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nueva contraseña (dejar en blanco para no cambiar)</label>
                        <input type="password" name="password" id="password" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmar contraseña -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirmar nueva contraseña</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                    </div>

                    <!-- Reto anual -->
                    <div>
                        <label for="retoAnual" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reto anual de lectura (número de libros)</label>
                        <input type="number" name="retoAnual" id="retoAnual" 
                               value="{{ old('retoAnual', Auth::user()->retoAnual ?? 12) }}"
                               min="1" max="100"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                        @error('retoAnual')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Borrar lista -->
                    <div>
                        <label for="lista_a_borrar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Vaciar lista</label>
                        <select name="lista_a_borrar" id="lista_a_borrar"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                            <option value="">Selecciona una lista para vaciar</option>
                            <option value="leyendo">Leyendo Actualmente</option>
                            <option value="leido">Mis Últimas Lecturas</option>
                            <option value="porLeer">Para Leer</option>
                            <option value="favoritos">Favoritos</option>
                        </select>
                        @error('lista_a_borrar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-between pt-6">
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Guardar cambios
                        </button>

                        <!-- Eliminar cuenta -->
                        <button type="button" 
                                onclick="confirmarEliminacion()"
                                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                            Eliminar cuenta
                        </button>
                    </div>
                </form>

                <!-- Formulario oculto para eliminar cuenta -->
                <form id="deleteAccountForm" method="POST" action="{{ route('profile.destroy') }}" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarEliminacion() {
    if (confirm('¿Estás seguro de que quieres eliminar tu cuenta? Esta acción no se puede deshacer.')) {
        document.getElementById('deleteAccountForm').submit();
    }
}
</script>
@endsection