<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verificar Código - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Verificar Código
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Enviamos um código de 8 dígitos para <strong>{{ session('contact') }}</strong>
                </p>
            </div>
            
            <div class="bg-white py-8 px-6 shadow rounded-lg sm:px-10">
                @if (session('message'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('message') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.verify.code') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="contact" value="{{ session('contact') }}">
                    <input type="hidden" name="contact_type" value="{{ session('contact_type') }}">

                    <!-- Código -->
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">
                            Código de Verificação
                        </label>
                        <input id="code" 
                               name="code" 
                               type="text" 
                               maxlength="8"
                               required
                               class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-center text-2xl font-mono tracking-widest" 
                               placeholder="00000000"
                               value="{{ old('code') }}">
                        
                        @error('code')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        
                        <p class="mt-2 text-xs text-gray-500 text-center">
                            Digite os 8 dígitos do código recebido
                        </p>
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-500">
                            ← Solicitar Novo Código
                        </a>
                        <button type="submit" class="group relative flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Verificar Código
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-500">
                        O código expira em 15 minutos
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-format do código para apenas números
        document.getElementById('code').addEventListener('input', function(e) {
            // Remove qualquer caractere que não seja número
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Limita a 8 dígitos
            if (this.value.length > 8) {
                this.value = this.value.substring(0, 8);
            }
        });

        // Auto-submit quando 8 dígitos forem digitados
        document.getElementById('code').addEventListener('input', function(e) {
            if (this.value.length === 8) {
                // Aguarda um momento para o usuário ver o código completo
                setTimeout(() => {
                    this.form.submit();
                }, 500);
            }
        });
    </script>
</body>
</html>
