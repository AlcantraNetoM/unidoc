<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Registro da Empresa</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Axios CDN -->
    <script src="https://cdn.jsdelivr.net/npm/axios@1.8.2/dist/axios.min.js"></script>
    
    <!-- AlpineJS CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.7/dist/cdn.min.js"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50">
        <!-- Header -->
        <header class="absolute top-0 w-full z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-gray-900">FileManager</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 transition-colors duration-200">
                            Já tem uma conta? Entrar
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl w-full space-y-8">
                <!-- Hero Section -->
                <div class="text-center">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">
                        Registre sua Empresa
                    </h2>
                    <p class="text-lg text-gray-600 mb-8">
                        Gerencie arquivos da sua empresa de forma segura e organizada
                    </p>
                </div>

                <!-- Registration Form -->
                <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                    <form method="POST" action="{{ route('empresa.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Progress Indicator -->
                        <div class="flex items-center justify-center mb-8">
                            <div class="flex space-x-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">1</div>
                                    <span class="ml-2 text-blue-600 font-medium">Dados da Empresa</span>
                                </div>
                                <div class="w-16 h-1 bg-gray-200 self-center"></div>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">2</div>
                                    <span class="ml-2 text-blue-600 font-medium">Administrador</span>
                                </div>
                            </div>
                        </div>

                        <!-- Company Information -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                                Informações da Empresa
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Company Name -->
                                <div class="md:col-span-2">
                                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nome da Empresa *
                                    </label>
                                    <input type="text" name="nome" id="nome" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('nome') border-red-500 @enderror"
                                           value="{{ old('nome') }}" placeholder="Digite o nome da sua empresa">
                                    @error('nome')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Company Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email da Empresa *
                                    </label>
                                    <input type="email" name="email" id="email" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror"
                                           value="{{ old('email') }}" placeholder="contato@empresa.com">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Company Logo -->
                                <div>
                                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                                        Logo da Empresa
                                    </label>
                                    <div class="flex items-center space-x-4">
                                        <input type="file" name="logo" id="logo" accept="image/*"
                                               class="hidden" onchange="previewLogo(this)">
                                        <label for="logo" class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            Escolher Arquivo
                                        </label>
                                        <div id="logo-preview" class="w-12 h-12 rounded-lg bg-gray-100 border-2 border-dashed border-gray-300 hidden items-center justify-center">
                                            <img id="logo-image" class="w-full h-full object-cover rounded-lg" style="display: none;">
                                        </div>
                                    </div>
                                    @error('logo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Company Address -->
                                <div class="md:col-span-2">
                                    <label for="endereco" class="block text-sm font-medium text-gray-700 mb-2">
                                        Endereço da Empresa *
                                    </label>
                                    <textarea name="endereco" id="endereco" rows="3" required
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('endereco') border-red-500 @enderror"
                                              placeholder="Digite o endereço completo da empresa">{{ old('endereco') }}</textarea>
                                    @error('endereco')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Administrator Information -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                                Dados do Administrador
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Admin Name -->
                                <div>
                                    <label for="admin_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nome do Administrador *
                                    </label>
                                    <input type="text" name="admin_name" id="admin_name" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('admin_name') border-red-500 @enderror"
                                           value="{{ old('admin_name') }}" placeholder="Nome completo do administrador">
                                    @error('admin_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Admin Email -->
                                <div>
                                    <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email do Administrador *
                                    </label>
                                    <input type="email" name="admin_email" id="admin_email" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('admin_email') border-red-500 @enderror"
                                           value="{{ old('admin_email') }}" placeholder="admin@empresa.com">
                                    @error('admin_email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Admin Password -->
                                <div>
                                    <label for="admin_password" class="block text-sm font-medium text-gray-700 mb-2">
                                        Senha *
                                    </label>
                                    <input type="password" name="admin_password" id="admin_password" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('admin_password') border-red-500 @enderror"
                                           placeholder="Mínimo 8 caracteres">
                                    @error('admin_password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label for="admin_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                        Confirmar Senha *
                                    </label>
                                    <input type="password" name="admin_password_confirmation" id="admin_password_confirmation" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                           placeholder="Confirme a senha">
                                </div>
                            </div>
                        </div>

                        <!-- Terms and Submit -->
                        <div class="space-y-6">
                            <div class="flex items-center">
                                <input type="checkbox" id="terms" name="terms" required
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="terms" class="ml-2 block text-sm text-gray-700">
                                    Eu concordo com os <a href="#" class="text-blue-600 hover:text-blue-500">Termos de Uso</a> e 
                                    <a href="#" class="text-blue-600 hover:text-blue-500">Política de Privacidade</a>
                                </label>
                            </div>

                            <button type="submit" 
                                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                Registrar Empresa
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Features -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                    <div class="text-center">
                        <div class="mx-auto h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Seguro</h3>
                        <p class="mt-2 text-sm text-gray-600">Seus arquivos protegidos com criptografia de nível empresarial</p>
                    </div>

                    <div class="text-center">
                        <div class="mx-auto h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Colaborativo</h3>
                        <p class="mt-2 text-sm text-gray-600">Gerencie equipes e permissões de acesso facilmente</p>
                    </div>

                    <div class="text-center">
                        <div class="mx-auto h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Organizado</h3>
                        <p class="mt-2 text-sm text-gray-600">Sistema de categorias e tags para organização perfeita</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewLogo(input) {
            const preview = document.getElementById('logo-preview');
            const image = document.getElementById('logo-image');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    image.src = e.target.result;
                    image.style.display = 'block';
                    preview.classList.remove('hidden');
                    preview.classList.add('flex');
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Form validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const password = document.getElementById('admin_password');
            const confirmPassword = document.getElementById('admin_password_confirmation');

            function validatePasswords() {
                if (password.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('As senhas não coincidem');
                } else {
                    confirmPassword.setCustomValidity('');
                }
            }

            password.addEventListener('input', validatePasswords);
            confirmPassword.addEventListener('input', validatePasswords);

            form.addEventListener('submit', function(e) {
                validatePasswords();
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });
        });
    </script>
</body>
</html> 