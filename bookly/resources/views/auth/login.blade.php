<x-guest-layout>
    <div class="login-container">
        <div class="paper-background">
            <form method="POST" action="{{ route('login') }}" class="login-form">
                @csrf
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Usuario -->
                <div class="form-group">
                    <label for="email" class="input-label">{{ __('Usuario') }}</label>
                    <div class="input-line">
                        <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="input-error" />
                </div>

                <!-- Contraseña -->
                <div class="form-group">
                    <label for="password" class="input-label">{{ __('Contraseña') }}</label>
                    <div class="input-line">
                        <input id="password" type="password" name="password" required autocomplete="current-password">
                    </div>
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
                        {{ __('¿No tienes cuenta? Regístrate aquí') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>