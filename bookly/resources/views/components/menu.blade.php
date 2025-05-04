<!-- Fondo oscuro -->
<div x-show="open" x-transition.opacity @click="open = false"
     class="fixed inset-0 bg-black bg-opacity-50 z-40" style="display: none;"></div>

<!-- Menú lateral desde la DERECHA -->
<div x-show="open"
     x-transition:enter="transition transform ease-out duration-300"
     x-transition:enter-start="translate-x-full"
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transition transform ease-in duration-200"
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="translate-x-full"
     class="fixed top-0 right-0 h-full w-[20vw] bg-white dark:bg-gray-800 shadow-lg z-50 p-6 overflow-y-auto transform translate-x-full"
     style="display: none;">

    <!-- Botón cerrar -->
    <button @click="open = false" class="absolute top-4 right-4 text-gray-600 dark:text-gray-300 hover:text-red-500">
        ✕
    </button>

    <!-- Enlaces -->
    <nav class="mt-12 space-y-2">
        <a href="{{ route('perfil') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Mi perfil</a>
        <a href="{{ route('listas.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Mis listas</a>
        <a href="{{ route('amigos') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Mis amigos</a>
        <a href="{{ route('prestamos.crear') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Prestar libro</a>
        <a href="{{ route('mensajes.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Mensajes</a>
        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Editar perfil</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                Cerrar sesión
            </button>
        </form>
    </nav>
</div>
