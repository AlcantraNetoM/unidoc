<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verificar Código</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Verificar Código</h2>
            
            <div class="mb-4 text-sm text-gray-600">
                Enviamos um código de 8 dígitos para 
                <strong>{{ session('contact') }}</strong>
                via {{ session('contact_type') === 'email' ? 'email' : 'SMS' }}.
                Digite o código abaixo:
            </div>

            @if (session('message'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('message') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.verify.code') }}">
                @csrf
                <input type="hidden" name="contact" value="{{ session('contact') }}">
                <input type="hidden" name="contact_type" value="{{ session('contact_type') }}">

                <!-- Código -->
                <div class="mb-4">
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-1">
                        Código de Verificação
                    </label>
                    <input id="code" 
                           name="code" 
                           type="text" 
                           maxlength="8"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-center text-lg font-mono" 
                           value="{{ old('code') }}" 
                           required 
                           autofocus 
                           placeholder="00000000">
                    
                    @error('code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-500">
                        Solicitar Novo Código
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Verificar Código
                    </button>
                </div>
            </form>

            <div class="mt-4 text-center">
                <p class="text-xs text-gray-500">
                    O código expira em 15 minutos
                </p>
            </div>
        </div>
    </div>

    <script>
        // Formatar automaticamente o código
        document.getElementById('code').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove não-dígitos
            e.target.value = value;
        });
    </script>
</body>
</html>
