@extends('layouts.app')

@section('content')
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .animate-fadeInUp {
        animation: fadeInUp 0.8s ease-out;
    }
    
    .animate-slideInLeft {
        animation: slideInLeft 0.8s ease-out;
    }
    
    .animate-pulse-custom {
        animation: pulse 2s ease-in-out infinite;
    }
    
    .gradient-text {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
</style>

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center animate-slideInLeft">
                    <div class="relative">
                        <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                    </div>
                    <h1 class="text-2xl font-bold gradient-text">{{ config('app.name', 'File Management') }}</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('landing') }}" class="text-gray-600 hover:text-gray-900 px-4 py-2 rounded-lg transition-all duration-300 hover:bg-gray-100">
                        ← Voltar ao Início
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-purple-700 py-16 relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-10 left-10 w-20 h-20 bg-white opacity-10 rounded-full animate-pulse"></div>
            <div class="absolute top-32 right-20 w-32 h-32 bg-white opacity-5 rounded-full animate-bounce"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="animate-fadeInUp">
                <div class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-white mb-6">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="font-semibold">
                        @if($planType === 'empresa')
                            Plano Empresarial Selecionado
                        @else
                            Plano Pessoal Selecionado
                        @endif
                    </span>
                </div>
                
                <h1 class="text-5xl md:text-6xl font-bold text-white mb-6 leading-tight">
                    @if($planType === 'empresa')
                        Solução 
                        <span class="block bg-gradient-to-r from-yellow-400 to-orange-500 bg-clip-text text-transparent">
                            Empresarial
                        </span>
                    @else
                        Gestão 
                        <span class="block bg-gradient-to-r from-green-400 to-emerald-500 bg-clip-text text-transparent">
                            Pessoal
                        </span>
                    @endif
                </h1>
                
                <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
                    @if($planType === 'empresa')
                        🚀 Transforme a gestão documental da sua empresa com recursos avançados
                    @else
                        ✨ Organize seus arquivos pessoais com eficiência e segurança
                    @endif
                </p>
            </div>
        </div>
    </div>

            <!-- Plan Comparison -->
            <div class="grid md:grid-cols-2 gap-12 max-w-6xl mx-auto mb-12">
                <!-- Selected Plan Details -->
                <div>
                    @if($planType === 'empresa')
                        <!-- Empresa Plan Details -->
                        <div class="bg-white rounded-xl shadow-lg p-8 border-4 border-blue-500">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900">Plano Empresarial</h3>
                                    <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">SELECIONADO</span>
                                </div>
                            </div>

                            <div class="space-y-4 mb-8">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">Usuários ilimitados</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">Dashboard administrativo completo</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">Relatórios avançados e estatísticas</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">Gestão completa de categorias e subcategorias</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">Upload de arquivos sem limite de tamanho</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">Controle total de permissões</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">Suporte prioritário</span>
                                </div>
                            </div>

                            <div class="text-center">
                                <div class="text-3xl font-bold text-blue-600 mb-2">15.000 Kz</div>
                                <div class="text-gray-500">por mês</div>
                            </div>
                        </div>
                    @else
                        <!-- Pessoal Plan Details -->
                        <div class="bg-white rounded-xl shadow-lg p-8 border-4 border-green-500">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900">Plano Pessoal</h3>
                                    <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">SELECIONADO</span>
                                </div>
                            </div>

                            <div class="space-y-4 mb-8">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">Acesso pessoal completo</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">Gestão de arquivos pessoais</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">Organização por categorias</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">Upload de arquivos até 100MB</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">Interface intuitiva e amigável</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">Suporte via email</span>
                                </div>
                            </div>

                            <div class="text-center">
                                <div class="text-3xl font-bold text-green-600 mb-2">5.000 Kz</div>
                                <div class="text-gray-500">por mês</div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Alternative Plan -->
                <div>
                    @if($planType === 'empresa')
                        <!-- Pessoal Plan as Alternative -->
                        <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-200 opacity-75">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gray-400 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900">Plano Pessoal</h3>
                                    <span class="bg-gray-100 text-gray-600 text-sm font-medium px-3 py-1 rounded-full">ALTERNATIVO</span>
                                </div>
                            </div>

                            <div class="space-y-4 mb-8">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">Acesso pessoal completo</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">Gestão de arquivos pessoais</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    <span class="text-gray-500">Sem usuários múltiplos</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    <span class="text-gray-500">Sem relatórios avançados</span>
                                </div>
                            </div>

                            <div class="text-center">
                                <div class="text-3xl font-bold text-green-600 mb-2">5.000 Kz</div>
                                <div class="text-gray-500">por mês</div>
                            </div>

                            <div class="mt-6">
                                <a href="{{ route('plan.select', 'pessoal') }}" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 px-6 rounded-lg font-medium transition-colors duration-300 text-center block">
                                    Trocar para Este Plano
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- Empresa Plan as Alternative -->
                        <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-200 opacity-75">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gray-400 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900">Plano Empresarial</h3>
                                    <span class="bg-gray-100 text-gray-600 text-sm font-medium px-3 py-1 rounded-full">ALTERNATIVO</span>
                                </div>
                            </div>

                            <div class="space-y-4 mb-8">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">Usuários ilimitados</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">Dashboard administrativo completo</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">Relatórios avançados</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">Upload sem limite de tamanho</span>
                                </div>
                            </div>

                            <div class="text-center">
                                <div class="text-3xl font-bold text-blue-600 mb-2">15.000 Kz</div>
                                <div class="text-gray-500">por mês</div>
                            </div>

                            <div class="mt-6">
                                <a href="{{ route('plan.select', 'empresa') }}" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 px-6 rounded-lg font-medium transition-colors duration-300 text-center block">
                                    Trocar para Este Plano
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center space-y-4">
                @if($planType === 'empresa')
                    <a href="{{ route('register.empresa') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg font-bold text-lg transition-colors duration-300">
                        Continuar com Plano Empresarial
                    </a>
                @else
                    <a href="{{ route('register.pessoal') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-lg font-bold text-lg transition-colors duration-300">
                        Continuar com Plano Pessoal
                    </a>
                @endif
                
                <div class="text-gray-600">
                    <p>Não tem certeza? <a href="{{ route('landing') }}" class="text-blue-600 hover:text-blue-800 underline">Compare todos os planos</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection
