<x-guest-layout>
    <div class="login-container">

        <div class="paper-background">
            <form method="POST" action="{{ route('login') }}" class="login-form">
                @csrf
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Usuario -->
                <div class="form-group">
                    <x-input-label for="email" :value="__('Usuario')" />
                    <x-text-input id="email" class="block w-full" type="text" name="email" :value="old('email')" required autofocus autocomplete="email" />
                    <x-input-error :messages="$errors->get('email')" class="input-error" />
                </div>

                <!-- Contraseña -->
                <div class="form-group">
                    <x-input-label for="password" :value="__('Contraseña')" />
                    <x-text-input id="password" class="block w-full" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="input-error" />
                </div>

                <!-- Enlace recuperación -->
                <div class="form-link">
                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">
                        {{ __('¿No te acuerdas?') }}
                    </a>
                    @endif
                </div>

                <!-- Botón Entrar -->
                <x-button type="submit" class="px-6 py-3">
                    {{ __('Entrar') }}
                </x-button>

                <!-- Enlace registro -->
                <div class="form-link register-link">
                    <a href="{{ route('register') }}">
                        ¿No tienes cuenta?<br>Regístrate aquí
                    </a>
                </div>
            </form>
        </div>
        <div class="decoracion-2"></div>
        <div class="decoracion-1"></div>
    </div>
</x-guest-layout>