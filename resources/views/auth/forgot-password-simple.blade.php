<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar Senha</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Recuperar Senha</h2>
            
            <div class="mb-4 text-sm text-gray-600">
                Esqueceu sua senha? Sem problema. Informe seu email ou número de telefone e enviaremos um código de 8 dígitos para você redefinir sua senha.
            </div>

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

            <form method="POST" action="{{ route('password.send.code') }}">
                @csrf

                <!-- Tipo de contato -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Como deseja receber o código?
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="contact_type" value="email" class="mr-2" checked>
                            <span class="text-sm text-gray-700">Email</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="contact_type" value="phone" class="mr-2">
                            <span class="text-sm text-gray-700">Número de Telefone</span>
                        </label>
                    </div>
                </div>

                <!-- Campo de contato -->
                <div class="mb-4">
                    <label for="contact" class="block text-sm font-medium text-gray-700 mb-1">
                        Email ou Telefone
                    </label>
                    <input id="contact" 
                           name="contact" 
                           type="text" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           value="{{ old('contact') }}" 
                           required 
                           autofocus 
                           placeholder="Digite seu email ou telefone">
                    
                    @error('contact')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-500">
                        Voltar ao Login
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Enviar Código
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Atualizar placeholder baseado no tipo selecionado
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
