<div x-data="{ open: false }" class="relative">
    <!-- Botón hamburguesa mejorado -->
    <button @click="open = !open"
        class="p-2 rounded-md focus:outline-none hover:bg-gray-200 dark:hover:bg-gray-700 transition"
        :aria-expanded="open"
        aria-label="Menú principal">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <!-- Menú desplegable con transiciones -->
    <div x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @click.away="open = false"
        class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
        style="display: none;">
        <div class="py-1">
            <a href="{{ route('perfil') }}">Mi perfil</a>
            <a href="{{ route('listas.index') }}">Mis listas</a>
            <a href="{{ route('amigos') }}">Mis amigos</a>
            <a href="{{ route('prestar') }}">Prestar libro</a>
            <a href="{{ route('mensajes') }}">Mensajes</a>
            <a href="{{ route('profile.edit') }}">Editar perfil</a>
            <a href="{{ route('logros') }}"
                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                📊 {{ __('Cerrar sesión') }}
            </a>
        </div>
    </div>
</div>