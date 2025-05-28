<x-guest-layout>

    <div class="login-container">

        <div class="paper-background">

            <form method="POST" action="{{ route('password.email') }}" class="login-form" style="gap: 0; padding: 5rem 5rem; padding-left: 12rem;">
                @csrf
                <h3>¿Has olvidado tu contraseña?</h3>
                <p class="reset-password-text" style="width: 50%;">
                    {{ __('Escribe tu correo y te mandaremos un enlace para cambiar la contraseña') }}
                <p>
                    <!-- Email Address -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end">
                    <x-button type="submit" class="px-6 py-3">
                        {{ __('Cambiar contraseña') }}
                    </x-button>
                </div>
            </form>

        </div>
        <div class="decoracion-2"></div>
        <div class="decoracion-1"></div>
    </div>


    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />


</x-guest-layout>