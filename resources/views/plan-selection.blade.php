@extends('layouts.register')

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
    
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(50px);
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
    
    .animate-slideInRight {
        animation: slideInRight 0.8s ease-out;
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
                        <!-- Logo UNIDOC -->
                        <div class="w-16 h-16 mr-4 flex items-center justify-center">
                            <img src="{{ asset('logo.png') }}" alt="UNIDOC Logo" class="w-16 h-16 object-contain">
                        </div>
                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-400 rounded-full animate-pulse"></div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-blue-600">UNIDOC</h1>
                        <p class="text-sm text-gray-600 -mt-1">Único local para documentos</p>
                    </div>
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
        <!-- Animated background elements -->
        <div class="absolute inset-0">
            <div class="absolute top-10 left-10 w-20 h-20 bg-white opacity-10 rounded-full animate-pulse"></div>
            <div class="absolute top-32 right-20 w-32 h-32 bg-white opacity-5 rounded-full animate-bounce"></div>
            <div class="absolute bottom-20 left-1/4 w-16 h-16 bg-white opacity-10 rounded-full animate-ping"></div>
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
                        UNIDOC para
                        <span class="block bg-gradient-to-r from-yellow-400 to-orange-500 bg-clip-text text-transparent">
                            Empresas
                        </span>
                    @else
                        UNIDOC 
                        <span class="block bg-gradient-to-r from-green-400 to-emerald-500 bg-clip-text text-transparent">
                            Pessoal
                        </span>
                    @endif
                </h1>
                
                <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
                    @if($planType === 'empresa')
                        🏢 Único local para todos os documentos da sua empresa com segurança e eficiência máxima.
                    @else
                        👤 Único local para todos os seus documentos pessoais com organização profissional.
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Plan Comparison -->
            <div class="grid lg:grid-cols-2 gap-16 max-w-6xl mx-auto mb-16">
                <!-- Selected Plan Details -->
                <div class="animate-slideInLeft">
                    @if($planType === 'empresa')
                        <!-- Empresa Plan Details -->
                        <div class="relative bg-white rounded-3xl shadow-2xl p-8 border-4 border-blue-500 overflow-hidden">
                            <!-- Success Badge -->
                            <div class="absolute -top-3 -right-3 w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center shadow-lg animate-pulse-custom">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            
                            <!-- Gradient Background -->
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-purple-50 opacity-50"></div>
                            
                            <div class="relative z-10">
                                <div class="flex items-center mb-8">
                                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mr-6 shadow-lg">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-3xl font-bold text-gray-900 mb-2">Plano Empresarial</h3>
                                        <span class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-100 to-purple-100 text-blue-800 text-sm font-bold rounded-full">
                                            ✅ SELECIONADO
                                        </span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-4 mb-8">
                                    <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-green-200">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="font-semibold text-gray-900">👥 Usuários Ilimitados</span>
                                            <p class="text-sm text-gray-600">Adicione quantos colaboradores precisar</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-green-200">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="font-semibold text-gray-900">📊 Dashboard Completo</span>
                                            <p class="text-sm text-gray-600">Visão 360° da sua empresa</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-green-200">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="font-semibold text-gray-900">📈 Analytics Avançados</span>
                                            <p class="text-sm text-gray-600">Relatórios detalhados e insights</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-green-200">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="font-semibold text-gray-900">☁️ Armazenamento Ilimitado</span>
                                            <p class="text-sm text-gray-600">Sem limites para crescer</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-green-200">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="font-semibold text-gray-900">🚀 Suporte Prioritário 24/7</span>
                                            <p class="text-sm text-gray-600">Atendimento especializado</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-green-200">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="font-semibold text-gray-900">🔧 Ferramentas Avançadas</span>
                                            <p class="text-sm text-gray-600">Recursos premium para otimização</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center p-6 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl text-white">
                                    <div class="text-4xl font-bold mb-2">15.000 Kz</div>
                                    <div class="text-blue-100 text-lg">por mês</div>
                                    <div class="mt-3 text-sm bg-white bg-opacity-20 rounded-full px-4 py-2 inline-block">
                                        💰 Economia de 40% vs. concorrência
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Pessoal Plan Details -->
                        <div class="relative bg-white rounded-3xl shadow-2xl p-8 border-4 border-green-500 overflow-hidden">
                            <!-- Success Badge -->
                            <div class="absolute -top-3 -right-3 w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center shadow-lg animate-pulse-custom">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            
                            <!-- Gradient Background -->
                            <div class="absolute inset-0 bg-gradient-to-br from-green-50 to-emerald-50 opacity-50"></div>
                            
                            <div class="relative z-10">
                                <div class="flex items-center mb-8">
                                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mr-6 shadow-lg">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-3xl font-bold text-gray-900 mb-2">Plano Pessoal</h3>
                                        <span class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 text-sm font-bold rounded-full">
                                            ✅ SELECIONADO
                                        </span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-4 mb-8">
                                    <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-green-200">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="font-semibold text-gray-900">👤 Acesso Pessoal Completo</span>
                                            <p class="text-sm text-gray-600">Todas as funcionalidades para você</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-green-200">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="font-semibold text-gray-900">📁 Organização Avançada</span>
                                            <p class="text-sm text-gray-600">Categorias automáticas e personalizadas</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-green-200">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="font-semibold text-gray-900">🎨 Interface Premium</span>
                                            <p class="text-sm text-gray-600">Design moderno e responsivo</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-green-200">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="font-semibold text-gray-900">🔍 Busca Avançada</span>
                                            <p class="text-sm text-gray-600">Encontre arquivos instantaneamente</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-green-200">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="font-semibold text-gray-900">📧 Suporte Dedicado</span>
                                            <p class="text-sm text-gray-600">Atendimento especializado por email</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-green-200">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="font-semibold text-gray-900">💾 5GB Armazenamento Seguro</span>
                                            <p class="text-sm text-gray-600">Espaço generoso para seus arquivos</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center p-6 bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl text-white">
                                    <div class="text-4xl font-bold mb-2">5.000 Kz</div>
                                    <div class="text-green-100 text-lg">por mês</div>
                                    <div class="mt-3 text-sm bg-white bg-opacity-20 rounded-full px-4 py-2 inline-block">
                                        ✨ Perfeito para começar
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Comparison Plan Details -->
                <div class="animate-slideInRight">
                    @if($planType === 'pessoal')
                        <!-- Empresa Plan Details -->
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
                                    <span class="text-gray-700">Interface intuitiva</span>
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
                <div class="animate-slideInRight">
                    @if($planType === 'empresa')
                        <!-- Pessoal Plan as Alternative -->
                        <div class="bg-white rounded-3xl shadow-lg p-8 border-2 border-gray-200 opacity-75 hover:opacity-100 transition-all duration-300 hover:shadow-xl">
                            <div class="text-center mb-6">
                                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">Plano Pessoal</h3>
                                <span class="bg-gray-100 text-gray-600 text-sm font-medium px-3 py-1 rounded-full">ALTERNATIVO</span>
                            </div>

                            <div class="space-y-4 mb-8">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-gray-700">Acesso pessoal</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span class="text-gray-500">Sem usuários múltiplos</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span class="text-gray-500">Sem relatórios avançados</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span class="text-gray-500">Sem dashboard administrativo</span>
                                </div>
                            </div>

                            <div class="text-center mb-6">
                                <div class="text-3xl font-bold text-green-600 mb-2">5.000 Kz</div>
                                <div class="text-gray-500">por mês</div>
                                <div class="text-sm text-gray-500 mt-2">💰 Opção mais econômica</div>
                            </div>

                            <a href="{{ route('plan.select', 'pessoal') }}" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 px-6 rounded-xl font-medium transition-all duration-300 text-center block transform hover:scale-105">
                                Trocar para Este Plano
                            </a>
                        </div>
                    @else
                        <!-- Empresa Plan as Alternative -->
                        <div class="bg-white rounded-3xl shadow-lg p-8 border-2 border-gray-200 opacity-75 hover:opacity-100 transition-all duration-300 hover:shadow-xl">
                            <div class="text-center mb-6">
                                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">Plano Empresarial</h3>
                                <span class="bg-gray-100 text-gray-600 text-sm font-medium px-3 py-1 rounded-full">UPGRADE DISPONÍVEL</span>
                            </div>

                            <div class="space-y-4 mb-8">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-gray-700">Usuários ilimitados</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-gray-700">Dashboard administrativo</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-gray-700">Relatórios avançados</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-gray-700">Armazenamento ilimitado</span>
                                </div>
                            </div>

                            <div class="text-center mb-6">
                                <div class="text-3xl font-bold text-blue-600 mb-2">15.000 Kz</div>
                                <div class="text-gray-500">por mês</div>
                                <div class="text-sm text-gray-500 mt-2">🚀 Recursos avançados</div>
                            </div>

                            <a href="{{ route('plan.select', 'empresa') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-xl font-medium transition-all duration-300 text-center block transform hover:scale-105">
                                Fazer Upgrade
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Comparison Features -->
            <div class="bg-white rounded-3xl shadow-xl p-8 mb-16 animate-fadeInUp">
                <h3 class="text-3xl font-bold text-center text-gray-900 mb-8">
                    Comparação Detalhada de 
                    <span class="gradient-text">Recursos</span>
                </h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="text-left py-4 px-6 font-semibold text-gray-900">Recursos</th>
                                <th class="text-center py-4 px-6 font-semibold text-green-600">Pessoal</th>
                                <th class="text-center py-4 px-6 font-semibold text-blue-600">Empresarial</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-4 px-6 font-medium">👥 Usuários</td>
                                <td class="py-4 px-6 text-center">1 usuário</td>
                                <td class="py-4 px-6 text-center text-green-600 font-semibold">Ilimitados</td>
                            </tr>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-4 px-6 font-medium">💾 Armazenamento</td>
                                <td class="py-4 px-6 text-center">5GB</td>
                                <td class="py-4 px-6 text-center text-green-600 font-semibold">Ilimitado</td>
                            </tr>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-4 px-6 font-medium">📊 Dashboard Administrativo</td>
                                <td class="py-4 px-6 text-center text-red-500">✗</td>
                                <td class="py-4 px-6 text-center text-green-600">✓</td>
                            </tr>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-4 px-6 font-medium">📈 Relatórios Avançados</td>
                                <td class="py-4 px-6 text-center text-red-500">✗</td>
                                <td class="py-4 px-6 text-center text-green-600">✓</td>
                            </tr>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-4 px-6 font-medium">🔧 Automação</td>
                                <td class="py-4 px-6 text-center text-yellow-500">Básica</td>
                                <td class="py-4 px-6 text-center text-green-600 font-semibold">Avançada</td>
                            </tr>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-4 px-6 font-medium">🚀 Suporte</td>
                                <td class="py-4 px-6 text-center">Email</td>
                                <td class="py-4 px-6 text-center text-green-600 font-semibold">24/7 Prioritário</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center space-y-6 animate-fadeInUp">
                @if($planType === 'empresa')
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-3xl p-8 text-white mb-8">
                        <h3 class="text-2xl font-bold mb-4">🎉 Parabéns pela escolha!</h3>
                        <p class="text-blue-100 mb-6">Você está prestes a transformar a gestão de documentos da sua empresa com nossa solução mais avançada.</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                            <div class="bg-white bg-opacity-20 rounded-xl p-4">
                                <div class="text-2xl mb-2">🚀</div>
                                <div class="text-sm">Setup em 5 minutos</div>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-xl p-4">
                                <div class="text-2xl mb-2">📞</div>
                                <div class="text-sm">Onboarding personalizado</div>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-xl p-4">
                                <div class="text-2xl mb-2">🎯</div>
                                <div class="text-sm">ROI garantido</div>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('register.plan', 'empresa') }}" class="group inline-flex items-center bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-12 py-5 rounded-2xl font-bold text-xl transition-all duration-300 transform hover:scale-105 shadow-2xl">
                        <span class="mr-3">Continuar com Plano Empresarial</span>
                        <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                @else
                    <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-3xl p-8 text-white mb-8">
                        <h3 class="text-2xl font-bold mb-4">✨ Excelente escolha!</h3>
                        <p class="text-green-100 mb-6">Você está prestes a revolucionar sua organização pessoal com nossa plataforma intuitiva e poderosa.</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                            <div class="bg-white bg-opacity-20 rounded-xl p-4">
                                <div class="text-2xl mb-2">⚡</div>
                                <div class="text-sm">Ativação instantânea</div>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-xl p-4">
                                <div class="text-2xl mb-2">🎨</div>
                                <div class="text-sm">Interface intuitiva</div>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-xl p-4">
                                <div class="text-2xl mb-2">📱</div>
                                <div class="text-sm">Acesso multiplataforma</div>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('register.plan', 'pessoal') }}" class="group inline-flex items-center bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-12 py-5 rounded-2xl font-bold text-xl transition-all duration-300 transform hover:scale-105 shadow-2xl">
                        <span class="mr-3">Continuar com Plano Pessoal</span>
                        <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                @endif
                
                <div class="mt-8 p-6 bg-gray-100 rounded-2xl">
                    <div class="flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-semibold text-gray-800">Garantias Inclusas</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                        <div class="text-center">
                            <div class="font-semibold">🔒 Segurança Total</div>
                            <div>Criptografia de nível empresarial</div>
                        </div>
                        <div class="text-center">
                            <div class="font-semibold">💰 15 Dias Grátis</div>
                            <div>Teste sem compromisso</div>
                        </div>
                        <div class="text-center">
                            <div class="font-semibold">🔄 Suporte Dedicado</div>
                            <div>Assistência técnica completa</div>
                        </div>
                    </div>
                </div>
                
                <div class="text-gray-600">
                    <p class="mb-4">🤔 Ainda tem dúvidas sobre qual plano escolher?</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <a href="{{ route('landing') }}" class="text-blue-600 hover:text-blue-800 font-semibold transition-colors underline">
                            📊 Compare todos os planos
                        </a>
                        <span class="text-gray-400">•</span>
                        <a href="mailto:vambert_quaresma@gmail.com" class="text-blue-600 hover:text-blue-800 font-semibold transition-colors underline">
                            💬 Fale com nossa equipe
                        </a>
                        <span class="text-gray-400">•</span>
                        <a href="tel:+244939424288" class="text-blue-600 hover:text-blue-800 font-semibold transition-colors underline">
                            📞 +244 939 424 288
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
