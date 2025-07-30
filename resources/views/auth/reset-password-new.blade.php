<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nova Senha - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Definir Nova Senha
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Código verificado com sucesso! Agora defina sua nova senha
                </p>
            </div>
            
            <div class="bg-white py-8 px-6 shadow rounded-lg sm:px-10">
                <form method="POST" action="{{ route('password.reset.save') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="contact" value="{{ session('contact') }}">
                    <input type="hidden" name="contact_type" value="{{ session('contact_type') }}">

                    <!-- Nova Senha -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Nova Senha
                        </label>
                        <input id="password" 
                               name="password" 
                               type="password" 
                               required
                               minlength="8"
                               class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                               placeholder="Digite sua nova senha">
                        
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        
                        <p class="mt-1 text-xs text-gray-500">
                            A senha deve ter pelo menos 8 caracteres
                        </p>
                    </div>

                    <!-- Confirmar Senha -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            Confirmar Nova Senha
                        </label>
                        <input id="password_confirmation" 
                               name="password_confirmation" 
                               type="password" 
                               required
                               minlength="8"
                               class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                               placeholder="Confirme sua nova senha">
                        
                        @error('password_confirmation')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Indicador de força da senha -->
                    <div class="space-y-2">
                        <div class="text-xs text-gray-600">Força da senha:</div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div id="password-strength" class="bg-red-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                        <div id="password-feedback" class="text-xs"></div>
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-500">
                            ← Voltar ao Login
                        </a>
                        <button type="submit" class="group relative flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Salvar Nova Senha
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function checkPasswordStrength(password) {
            let strength = 0;
            let feedback = [];
            
            if (password.length >= 8) strength += 20;
            else feedback.push('Pelo menos 8 caracteres');
            
            if (/[a-z]/.test(password)) strength += 20;
            else feedback.push('Letras minúsculas');
            
            if (/[A-Z]/.test(password)) strength += 20;
            else feedback.push('Letras maiúsculas');
            
            if (/[0-9]/.test(password)) strength += 20;
            else feedback.push('Números');
            
            if (/[^A-Za-z0-9]/.test(password)) strength += 20;
            else feedback.push('Símbolos especiais');
            
            return { strength, feedback };
        }

        document.getElementById('password').addEventListener('input', function() {
            const result = checkPasswordStrength(this.value);
            const strengthBar = document.getElementById('password-strength');
            const feedbackDiv = document.getElementById('password-feedback');
            
            strengthBar.style.width = result.strength + '%';
            
            if (result.strength < 40) {
                strengthBar.className = 'bg-red-600 h-2 rounded-full transition-all duration-300';
                feedbackDiv.textContent = 'Fraca - Faltam: ' + result.feedback.join(', ');
                feedbackDiv.className = 'text-xs text-red-600';
            } else if (result.strength < 80) {
                strengthBar.className = 'bg-yellow-500 h-2 rounded-full transition-all duration-300';
                feedbackDiv.textContent = 'Média - Faltam: ' + result.feedback.join(', ');
                feedbackDiv.className = 'text-xs text-yellow-600';
            } else {
                strengthBar.className = 'bg-green-600 h-2 rounded-full transition-all duration-300';
                feedbackDiv.textContent = 'Forte - Senha segura!';
                feedbackDiv.className = 'text-xs text-green-600';
            }
        });

        // Verificar se as senhas coincidem
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            if (this.value && this.value !== password) {
                this.setCustomValidity('As senhas não coincidem');
                this.classList.add('border-red-500');
            } else {
                this.setCustomValidity('');
                this.classList.remove('border-red-500');
            }
        });
    </script>
</body>
</html>
