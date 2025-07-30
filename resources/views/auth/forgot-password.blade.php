<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Esqueceu sua senha? Sem problema. Informe seu email ou número de telefone e enviaremos um código de 8 dígitos para você redefinir sua senha.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.send.code') }}">
        @csrf

        <!-- Tipo de contato -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ __('Como deseja receber o código?') }}
            </label>
            <div class="space-y-2">
                <label class="flex items-center">
                    <input type="radio" name="contact_type" value="email" class="mr-2" checked>
                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('Email') }}</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" name="contact_type" value="phone" class="mr-2">
                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('Telefone') }}</span>
                </label>
            </div>
        </div>

        <!-- Email/Telefone -->
        <div class="mb-4">
            <x-input-label for="contact" :value="__('Email ou Telefone')" />
            <x-text-input id="contact" class="block mt-1 w-full" type="text" name="contact" :value="old('contact')" required autofocus placeholder="Digite seu email ou telefone" />
            <x-input-error :messages="$errors->get('contact')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <a class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100" href="{{ route('login') }}">
                {{ __('Voltar ao Login') }}
            </a>

            <x-primary-button>
                {{ __('Enviar Código') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        // Atualizar placeholder baseado no tipo selecionado
        document.querySelectorAll('input[name="contact_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const contactInput = document.getElementById('contact');
                const label = document.querySelector('label[for="contact"] span');
                
                if (this.value === 'email') {
                    contactInput.placeholder = 'Digite seu email';
                    contactInput.type = 'email';
                    label.textContent = 'Email';
                } else {
                    contactInput.placeholder = 'Digite seu telefone';
                    contactInput.type = 'tel';
                    label.textContent = 'Telefone';
                }
            });
        });
    </script>
</x-guest-layout>
