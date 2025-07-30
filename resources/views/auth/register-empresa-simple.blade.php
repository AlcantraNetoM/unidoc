@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h1 class="text-2xl font-bold text-gray-900">{{ config('app.name', 'Sistema de Gestão') }}</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('plan.select', 'empresa') }}" class="text-gray-600 hover:text-gray-900 px-4 py-2 rounded-lg transition duration-200">Voltar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Form -->
    <div class="py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Registro Empresarial</h1>
                <p class="text-xl text-gray-600">Complete o formulário para criar sua conta empresarial</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8 border">
                <!-- Plan Info -->
                <div class="bg-blue-50 rounded-lg p-4 mb-8 border border-blue-200">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Plano Empresarial Selecionado</h3>
                            <p class="text-sm text-gray-600">Acesso completo às funcionalidades empresariais</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('register.store.empresa') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <input type="hidden" name="account_type" value="company">
                    <input type="hidden" name="plan_type" value="empresa">

                    <!-- Company Name -->
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome da Empresa *
                        </label>
                        <input 
                            id="company_name" 
                            name="company_name" 
                            type="text" 
                            required 
                            value="{{ old('company_name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Digite o nome da sua empresa"
                        >
                        @error('company_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Company Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email da Empresa *
                        </label>
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            required 
                            value="{{ old('email') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="empresa@exemplo.com"
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Admin Name -->
                    <div>
                        <label for="admin_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome do Administrador *
                        </label>
                        <input 
                            id="admin_name" 
                            name="admin_name" 
                            type="text" 
                            required 
                            value="{{ old('admin_name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Nome completo do administrador"
                        >
                        @error('admin_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Palavra-passe *
                        </label>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Mínimo 8 caracteres"
                        >
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmar Palavra-passe *
                        </label>
                        <input 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            type="password" 
                            required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Confirme sua palavra-passe"
                        >
                    </div>

                    <!-- Payment Information -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Informações de Pagamento</h4>
                        <div class="space-y-2 text-sm text-gray-700">
                            <p><strong>IBAN:</strong> 0055.0000.9274.3910.1018.0</p>
                            <p><strong>Titular:</strong> Vambert Capita</p>
                            <p><strong>Referência:</strong> EMPRESA-{{ now()->format('Ymd') }}</p>
                        </div>
                        <div class="mt-4 p-3 bg-blue-100 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <strong>Importante:</strong> Efetue o pagamento antes de enviar o formulário. 
                                Sua conta será ativada após validação do comprovativo.
                            </p>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input 
                                id="terms" 
                                name="terms" 
                                type="checkbox" 
                                required
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                            >
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="text-gray-700">
                                Concordo com os 
                                <a href="#" class="text-blue-600 hover:text-blue-800 underline">termos de serviço</a> 
                                e 
                                <a href="#" class="text-blue-600 hover:text-blue-800 underline">política de privacidade</a>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button 
                            type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 px-6 rounded-lg font-bold text-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Criar Conta Empresarial
                        </button>
                    </div>

                    <!-- Alternative Actions -->
                    <div class="text-center space-y-2 pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600">
                            Já tem uma conta? 
                            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                Faça login
                            </a>
                        </p>
                        <p class="text-sm text-gray-600">
                            <a href="{{ route('plan.select', 'pessoal') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                Mudar para Plano Pessoal
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
