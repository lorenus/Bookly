<x-guest-layout>
    <div class="register-container">

        <div class="paper-background-register" style="display: flex; justify-content: center; align-items: center;">
            <form method="POST" action="{{ route('register') }}" class="register-form">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <x-input-label for="name" :value="__('Nombre')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2 input-error"/>
                </div>

                <div class="form-group">
                    <x-input-label for="apellidos" :value="__('Apellidos')" />
                    <x-text-input id="apellidos" class="block mt-1 w-full" type="text" name="apellidos" :value="old('apellidos')" required autofocus autocomplete="apellidos" />
                    <x-input-error :messages="$errors->get('apellidos')" class="mt-2 input-error" />
                </div>

                <!-- Email Address -->
                <div class="form-group">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 input-error" />
                </div>

                <!-- Password -->
                <div class="form-group">
                    <x-input-label for="password" :value="__('Contraseña')" />

                    <x-text-input id="password" class="block mt-1 w-full"
                        type="password"
                        name="password"
                        required autocomplete="new-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2 input-error" />
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <x-input-label for="password_confirmation" :value="__('Confirma la contraseña')" />

                    <x-text-input id="password_confirmation" class="block mt-1 w-full"
                        type="password"
                        name="password_confirmation" required autocomplete="new-password" />

                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 input-error" />
                </div>

                <div class="form-group register-link">
                    <a href="{{ route('login') }}">
                        {{ __('¿Ya tienes cuenta?') }}
                    </a>
                </div>
                <div class="form-group">
                    <x-button class="ms-4">
                        {{ __('¡Regístrame!') }}
                    </x-button>
                </div>
            </form>
        </div>
        <div class="decoracion-3"></div>
        <div class="decoracion-4"></div>
    </div>


</x-guest-layout>