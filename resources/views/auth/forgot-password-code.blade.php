<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar Senha - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Recuperar Senha
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Digite seu email ou telefone para receber um código de verificação
                </p>
            </div>
            
            <div class="bg-white py-8 px-6 shadow rounded-lg sm:px-10">
                @if (session('status'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('message'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('message') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.send.code') }}" class="space-y-6">
                    @csrf

                    <!-- Tipo de contato -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Como deseja receber o código?
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="contact_type" value="email" class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" checked>
                                <span class="text-sm text-gray-700">Email</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="contact_type" value="phone" class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span class="text-sm text-gray-700">Número de Telefone</span>
                            </label>
                        </div>
                    </div>

                    <!-- Campo de contato -->
                    <div>
                        <label for="contact" class="block text-sm font-medium text-gray-700 mb-1">
                            Email ou Telefone
                        </label>
                        <input id="contact" 
                               name="contact" 
                               type="text" 
                               required
                               class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                               placeholder="Digite seu email ou telefone"
                               value="{{ old('contact') }}">
                        
                        @error('contact')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-500">
                            ← Voltar ao Login
                        </a>
                        <button type="submit" class="group relative flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Enviar Código
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Atualizar tipo de input baseado no tipo selecionado
        document.querySelectorAll('input[name="contact_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const contactInput = document.getElementById('contact');
                const label = document.querySelector('label[for="contact"]');
                
                if (this.value === 'email') {
                    contactInput.placeholder = 'Digite seu email';
                    contactInput.type = 'email';
                    label.textContent = 'Email';
                } else {
                    contactInput.placeholder = 'Digite seu número de telefone';
                    contactInput.type = 'tel';
                    label.textContent = 'Número de Telefone';
                }
            });
        });
    </script>
</body>
</html>
