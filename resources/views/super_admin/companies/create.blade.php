@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Criar Nova Empresa</h1>
                <p class="text-gray-600 mt-2">Registre uma nova empresa no sistema</p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-8">
                <form method="POST" action="{{ route('super_admin.companies.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

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
                                       value="{{ old('nome') }}" placeholder="Digite o nome da empresa">
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
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

                    <!-- Form Actions -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('super_admin.companies.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Voltar
                        </a>

                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            Criar Empresa
                        </button>
                    </div>
                </form>
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
    </script>
@endsection 