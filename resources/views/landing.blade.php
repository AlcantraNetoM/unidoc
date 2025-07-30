{{-- 
    LOGO UNIDOC - Arquivo de Imagem
    
    O logo oficial do UNIDOC está disponível em: public/logo.png
    
    Para usar este logo em qualquer lugar do sistema, use:
    
    <img src="{{ asset('logo.png') }}" alt="UNIDOC Logo" class="w-[TAMANHO] h-[TAMANHO] object-contain">
    
    Tamanhos padronizados:
    - Header pequeno (login): w-12 h-12
    - Header normal (registros): w-14 h-14  
    - Header destaque (landing/planos): w-16 h-16
    - Centro/Destaque principal: w-16 h-16 ou w-20 h-20
    - Cards/Ícones pequenos: w-6 h-6 ou w-8 h-8
    
    IMPORTANTE: Sempre use object-contain para manter as proporções do logo
--}}

@extends('layouts.register')

@section('content')
<!-- Comentário para desenvolvedores: 
     O logo do UNIDOC deve ser colocado substituindo os placeholders com background gradient.
     Procure por divs com classes "bg-gradient-to-br from-blue-600 to-purple-600" (empresarial)
     ou "bg-gradient-to-br from-green-600 to-emerald-600" (pessoal) que contêm o ícone SVG.
-->
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
    
    @keyframes floatAnimation {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
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
    
    .animate-fadeInUp {
        animation: fadeInUp 0.8s ease-out;
    }
    
    .animate-float {
        animation: floatAnimation 3s ease-in-out infinite;
    }
    
    .animate-slideInLeft {
        animation: slideInLeft 0.8s ease-out;
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
                <div class="flex items-center space-x-6">
                    <!-- Menu Funcionalidades -->
                    <div class="relative group">
                        <button class="flex items-center text-gray-600 hover:text-blue-600 px-4 py-2 rounded-lg transition-all duration-300 hover:bg-gray-100">
                            <span class="font-medium">Ferramentas</span>
                            <svg class="w-4 h-4 ml-2 transform group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute top-full left-0 mt-2 w-56 bg-white rounded-lg shadow-xl border opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2 z-50">
                            <div class="py-2">
                                <a href="{{ route('tools.edit-pdf') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    <div>
                                        <div class="font-medium">Editar PDF</div>
                                        <div class="text-xs text-gray-500">Editor profissional de PDFs</div>
                                    </div>
                                </a>
                                
                                <a href="{{ route('tools.convert-pdf') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                    </svg>
                                    <div>
                                        <div class="font-medium">Converter PDF</div>
                                        <div class="text-xs text-gray-500">Converter entre formatos</div>
                                    </div>
                                </a>
                                
                                <a href="{{ route('tools.electronic-signature') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                    <div>
                                        <div class="font-medium">Assinaturas Eletrônicas</div>
                                        <div class="text-xs text-gray-500">Assinar documentos digitalmente</div>
                                    </div>
                                </a>
                                
                                <a href="{{ route('tools.compress-pdf') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    <div>
                                        <div class="font-medium">Comprimir PDF</div>
                                        <div class="text-xs text-gray-500">Reduzir tamanho dos arquivos</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botão Sobre -->
                    <a href="{{ route('sobre') }}" class="flex items-center text-gray-600 hover:text-blue-600 px-4 py-2 rounded-lg transition-all duration-300 hover:bg-gray-100">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-medium">Sobre</span>
                    </a>
                    
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            Acessar Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-4 py-2 rounded-lg transition-all duration-300 hover:bg-gray-100">Entrar</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-purple-700 py-20 relative overflow-hidden">
        <!-- Animated background elements -->
        <div class="absolute inset-0">
            <div class="absolute top-10 left-10 w-20 h-20 bg-white opacity-10 rounded-full animate-pulse"></div>
            <div class="absolute top-32 right-20 w-32 h-32 bg-white opacity-5 rounded-full animate-bounce"></div>
            <div class="absolute bottom-20 left-1/4 w-16 h-16 bg-white opacity-10 rounded-full animate-ping"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="animate-fadeInUp">
                <h1 class="text-6xl md:text-7xl font-bold text-white mb-6 leading-tight">
                    UNIDOC
                    <span class="block bg-gradient-to-r from-yellow-400 to-orange-500 bg-clip-text text-transparent">
                        Único Local para Documentos
                    </span>
                </h1>
                <p class="text-xl md:text-2xl text-blue-100 mb-12 max-w-4xl mx-auto leading-relaxed">
                    🚀 <strong>Transforme</strong> sua gestão de documentos com nossa plataforma de última geração. 
                    <br>Segurança máxima, organização automática e eficiência incomparável em um só lugar.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
                    @auth
                        <a href="{{ route('dashboard') }}" class="group inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold text-lg rounded-xl hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-2xl">
                            <span>Acessar Dashboard</span>
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                    @else
                        <a href="#plans" onclick="document.getElementById('plans').scrollIntoView({behavior: 'smooth'})" class="group inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold text-lg rounded-xl hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-2xl">
                            <span>Escolher Plano</span>
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                        <a href="#features" onclick="document.getElementById('features').scrollIntoView({behavior: 'smooth'})" class="inline-flex items-center px-8 py-4 border-2 border-white text-white font-bold text-lg rounded-xl hover:bg-white hover:text-blue-600 transition-all duration-300 transform hover:scale-105">
                            Ver Recursos
                        </a>
                    @endauth
                </div>
                
                <!-- Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-4xl mx-auto">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white mb-2">+75%</div>
                        <div class="text-blue-200">Produtividade</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white mb-2">10x</div>
                        <div class="text-blue-200">Velocidade</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white mb-2">100%</div>
                        <div class="text-blue-200">Segurança</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white mb-2">24/7</div>
                        <div class="text-blue-200">Suporte</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-fadeInUp">
                <h2 class="text-5xl font-bold text-gray-900 mb-6">
                    Por que escolher o
                    <span class="gradient-text">UNIDOC?</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    A única plataforma que você precisa para gerenciar todos os seus documentos com segurança e eficiência
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-12">
                <!-- Feature 1 -->
                <div class="text-center group hover:transform hover:scale-105 transition-all duration-300">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:shadow-xl transition-shadow animate-float">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Segurança Máxima</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Criptografia de nível militar, backups automáticos e conformidade com LGPD. 
                        Seus dados estão protegidos com a mais alta tecnologia de segurança.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="text-center group hover:transform hover:scale-105 transition-all duration-300">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:shadow-xl transition-shadow animate-float" style="animation-delay: 0.5s;">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Performance Ultra-Rápida</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Upload instantâneo, busca avançada e acesso em tempo real. 
                        Tecnologia otimizada para máxima velocidade e responsividade.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="text-center group hover:transform hover:scale-105 transition-all duration-300">
                    <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:shadow-xl transition-shadow animate-float" style="animation-delay: 1s;">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Automação Avançada</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Organização automática por conteúdo, tags personalizadas e sugestões otimizadas. 
                        Tecnologia avançada trabalhando para você.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Comprehensive Features Grid -->
    <div class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">
                    Funcionalidades Completas do
                    <span class="gradient-text">UNIDOC</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Tudo que você precisa para uma gestão documental profissional e eficiente
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Gestão de Arquivos -->
                <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">📁 Gestão de Arquivos</h3>
                    <ul class="text-gray-600 space-y-2 text-sm">
                        <li>• Upload instantâneo (JPG, PNG, PDF - máx. 5MB)</li>
                        <li>• Organização automática por categorias</li>
                        <li>• Sistema avançado de busca</li>
                        <li>• Visualização e download seguro</li>
                        <li>• Armazenamento com criptografia</li>
                    </ul>
                </div>

                <!-- Gestão de Usuários -->
                <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">👥 Gestão de Usuários</h3>
                    <ul class="text-gray-600 space-y-2 text-sm">
                        <li>Usuários ilimitados (empresarial)</li>
                        <li>Níveis de acesso: Admin, Técnico, Técnico Normal</li>
                        <li>Dashboard personalizado para cada empresa</li>
                        <li>Sistema de aprovação manual</li>
                        <li>Controle de permissões</li>
                    </ul>
                </div>

                <!-- Para Empresas -->
                <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">🏢 Para Empresas</h3>
                    <ul class="text-gray-600 space-y-2 text-sm">
                        <li>Dashboard administrativo completo</li>
                        <li>Gestão completa de usuários</li>
                        <li>Relatórios avançados e analytics</li>
                        <li>Ferramentas avançadas de gestão</li>
                        <li>Armazenamento ilimitado</li>
                    </ul>
                </div>

                <!-- Performance -->
                <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">⚡ Performance Ultra-Rápida</h3>
                    <ul class="text-gray-600 space-y-2 text-sm">
                        <li>Upload instantâneo</li>
                        <li>Busca avançada em tempo real</li>
                        <li>Interface responsiva</li>
                        <li>Velocidade 10x superior</li>
                        <li>Tecnologia otimizada</li>
                    </ul>
                </div>

                <!-- Segurança -->
                <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">🛡️ Segurança Máxima</h3>
                    <ul class="text-gray-600 space-y-2 text-sm">
                        <li>Criptografia de nível militar</li>
                        <li>Backups automáticos</li>
                        <li>Conformidade com LGPD</li>
                        <li>Armazenamento seguro</li>
                        <li>100% de proteção garantida</li>
                    </ul>
                </div>

                <!-- Automação -->
                <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">🔄 Automação Inteligente</h3>
                    <ul class="text-gray-600 space-y-2 text-sm">
                        <li>Organização automática</li>
                        <li>Tags personalizadas</li>
                        <li>Sugestões inteligentes</li>
                        <li>Categorização automática</li>
                        <li>Workflow otimizado</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Use Cases Section -->
    <div class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">
                    Casos de Uso do
                    <span class="gradient-text">UNIDOC</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Soluções adaptadas para suas necessidades específicas
                </p>
            </div>

            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Para Empresas -->
                <div class="bg-white rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">🏢 Para Empresas</h3>
                    </div>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-start">
                            <span class="text-blue-600 mr-2">•</span>
                            <span>Gestão de contratos e documentos administrativos</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 mr-2">•</span>
                            <span>Recursos humanos (currículos, avaliações, treinamentos)</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 mr-2">•</span>
                            <span>Departamento financeiro (faturas, recibos, orçamentos)</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 mr-2">•</span>
                            <span>Documentos operacionais e técnicos</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 mr-2">•</span>
                            <span>Relatórios e correspondências oficiais</span>
                        </li>
                    </ul>
                </div>

                <!-- Para Usuários Pessoais -->
                <div class="bg-white rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">👤 Para Usuários Pessoais</h3>
                    </div>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-start">
                            <span class="text-green-600 mr-2">•</span>
                            <span>Organização de documentos pessoais importantes</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-600 mr-2">•</span>
                            <span>Backup seguro de arquivos valiosos</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-600 mr-2">•</span>
                            <span>Acesso remoto aos seus documentos</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-600 mr-2">•</span>
                            <span>Compartilhamento controlado e seguro</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-600 mr-2">•</span>
                            <span>Arquivo digital pessoal organizado</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @guest
    <!-- Plans Section -->
    <div id="plans" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-fadeInUp">
                <h2 class="text-5xl font-bold text-gray-900 mb-6">
                    Escolha Seu Plano
                    <span class="gradient-text">UNIDOC</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Soluções personalizadas para cada necessidade. Comece sua transformação digital hoje mesmo com 15 dias gratuitos.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-12 max-w-5xl mx-auto">
                <!-- Plano Empresarial -->
                <div class="group relative bg-white rounded-3xl shadow-xl overflow-hidden border-2 border-transparent hover:border-blue-500 transition-all duration-500 hover:transform hover:scale-105">
                    <!-- Recommended Badge -->
                    <div class="absolute top-0 right-0 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-bl-2xl text-sm font-bold">
                        RECOMENDADO
                    </div>
                    
                    <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-purple-700 p-8 text-white text-center relative overflow-hidden">
                        <!-- Animated background -->
                        <div class="absolute inset-0 opacity-20">
                            <div class="absolute top-4 right-4 w-16 h-16 bg-white rounded-full animate-pulse"></div>
                            <div class="absolute bottom-4 left-4 w-12 h-12 bg-white rounded-full animate-bounce"></div>
                        </div>
                        
                        <div class="relative z-10">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <h3 class="text-3xl font-bold mb-2">Plano Empresarial</h3>
                            <p class="text-blue-100 text-lg">Para empresas que buscam excelência</p>
                        </div>
                    </div>
                    
                    <div class="p-8">
                        <div class="space-y-4 mb-8">
                            <div class="flex items-center group-hover:translate-x-2 transition-transform duration-300">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">Usuários ilimitados</span>
                            </div>
                            <div class="flex items-center group-hover:translate-x-2 transition-transform duration-300" style="transition-delay: 0.1s;">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">Dashboard administrativo completo</span>
                            </div>
                            <div class="flex items-center group-hover:translate-x-2 transition-transform duration-300" style="transition-delay: 0.2s;">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">Relatórios avançados e analytics</span>
                            </div>
                            <div class="flex items-center group-hover:translate-x-2 transition-transform duration-300" style="transition-delay: 0.3s;">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">Armazenamento ilimitado</span>
                            </div>
                            <div class="flex items-center group-hover:translate-x-2 transition-transform duration-300" style="transition-delay: 0.4s;">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">Suporte prioritário 24/7</span>
                            </div>
                            <div class="flex items-center group-hover:translate-x-2 transition-transform duration-300" style="transition-delay: 0.5s;">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">Ferramentas avançadas de gestão</span>
                            </div>
                        </div>
                        
                        <div class="text-center mb-8 p-6 bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl">
                            <div class="text-4xl font-bold gradient-text mb-2">15.000 Kz</div>
                            <div class="text-gray-500 text-lg">por mês</div>
                            <div class="text-sm text-green-600 font-semibold mt-2">Economia de 40% vs. concorrência</div>
                        </div>
                        
                        <a href="{{ route('plan.select', 'empresa') }}" class="group w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white py-4 px-6 rounded-xl font-bold text-lg text-center block transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            <span class="flex items-center justify-center">
                                Escolher Plano Empresarial
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>

                <!-- Plano Pessoal -->
                <div class="group relative bg-white rounded-3xl shadow-xl overflow-hidden border-2 border-transparent hover:border-green-500 transition-all duration-500 hover:transform hover:scale-105">
                    <div class="bg-gradient-to-br from-green-600 via-green-700 to-emerald-700 p-8 text-white text-center relative overflow-hidden">
                        <!-- Animated background -->
                        <div class="absolute inset-0 opacity-20">
                            <div class="absolute top-4 left-4 w-12 h-12 bg-white rounded-full animate-pulse"></div>
                            <div class="absolute bottom-4 right-4 w-16 h-16 bg-white rounded-full animate-bounce"></div>
                        </div>
                        
                        <div class="relative z-10">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h3 class="text-3xl font-bold mb-2">Plano Pessoal</h3>
                            <p class="text-green-100 text-lg">Ideal para uso individual</p>
                        </div>
                    </div>
                    
                    <div class="p-8">
                        <div class="space-y-4 mb-8">
                            <div class="flex items-center group-hover:translate-x-2 transition-transform duration-300">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">Acesso pessoal completo</span>
                            </div>
                            <div class="flex items-center group-hover:translate-x-2 transition-transform duration-300" style="transition-delay: 0.1s;">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">Organização avançada por categorias</span>
                            </div>
                            <div class="flex items-center group-hover:translate-x-2 transition-transform duration-300" style="transition-delay: 0.2s;">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">Interface moderna e intuitiva</span>
                            </div>
                            <div class="flex items-center group-hover:translate-x-2 transition-transform duration-300" style="transition-delay: 0.3s;">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">Busca avançada por conteúdo</span>
                            </div>
                            <div class="flex items-center group-hover:translate-x-2 transition-transform duration-300" style="transition-delay: 0.4s;">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">Suporte especializado via email</span>
                            </div>
                            <div class="flex items-center group-hover:translate-x-2 transition-transform duration-300" style="transition-delay: 0.5s;">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">5GB de armazenamento seguro</span>
                            </div>
                        </div>
                        
                        <div class="text-center mb-8 p-6 bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl">
                            <div class="text-4xl font-bold text-green-600 mb-2">5.000 Kz</div>
                            <div class="text-gray-500 text-lg">por mês</div>
                            <div class="text-sm text-green-600 font-semibold mt-2">Perfeito para começar</div>
                        </div>
                        
                        <a href="{{ route('plan.select', 'pessoal') }}" class="group w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white py-4 px-6 rounded-xl font-bold text-lg text-center block transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            <span class="flex items-center justify-center">
                                Escolher Plano Pessoal
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- CTA Bottom -->
            <div class="text-center mt-16">
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-3xl p-8 mb-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Período de Teste Gratuito</h3>
                    <p class="text-lg text-gray-700 mb-6">
                        Teste todas as funcionalidades do UNIDOC por <strong>15 dias completamente grátis</strong>
                    </p>
                    <div class="grid md:grid-cols-3 gap-6 text-center">
                        <div class="bg-white rounded-xl p-4 shadow-sm">
                            <div class="text-3xl mb-2">1️</div>
                            <h4 class="font-semibold text-gray-900">Escolha seu Plano</h4>
                            <p class="text-sm text-gray-600">Empresarial ou Pessoal</p>
                        </div>
                        <div class="bg-white rounded-xl p-4 shadow-sm">
                            <div class="text-3xl mb-2">2️</div>
                            <h4 class="font-semibold text-gray-900">Preencha os Dados</h4>
                            <p class="text-sm text-gray-600">Formulário específico</p>
                        </div>
                        <div class="bg-white rounded-xl p-4 shadow-sm">
                            <div class="text-3xl mb-2">3️</div>
                            <h4 class="font-semibold text-gray-900">Envie Comprovativo</h4>
                            <p class="text-sm text-gray-600">Upload do pagamento</p>
                        </div>
                    </div>
                </div>
                
                <p class="text-gray-600 mb-6">Todos os planos incluem criptografia de nível empresarial e backup automático</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <span class="text-gray-500">Precisa de mais informações?</span>
                    <a href="mailto:vambert_quaresma@gmail.com" class="text-blue-600 hover:text-blue-800 font-semibold transition-colors">
                        Fale com nossa equipe
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endguest

    <!-- Payment Information Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl p-8 shadow-xl border border-gray-200">
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Informações de Pagamento</h3>
                    <p class="text-gray-600">Dados bancários para ativação da sua conta UNIDOC</p>
                </div>
                
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl p-6">
                        <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <span class="text-2xl mr-3"></span>
                            Plano Empresarial
                        </h4>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Valor mensal:</span>
                                <span class="font-bold text-blue-600">15.000 Kz</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Período de teste:</span>
                                <span class="font-medium text-green-600">15 dias grátis</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6">
                        <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <span class="text-2xl mr-3"></span>
                            Plano Pessoal
                        </h4>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Valor mensal:</span>
                                <span class="font-bold text-green-600">5.000 Kz</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Período de teste:</span>
                                <span class="font-medium text-green-600">15 dias grátis</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 p-6 bg-gray-50 rounded-2xl">
                    <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <span class="text-2xl mr-3"></span>
                        Dados Bancários
                    </h4>
                    <div class="grid md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Banco:</span>
                            <span class="font-medium ml-2">Atlântico</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Titular:</span>
                            <span class="font-medium ml-2">Vambert Capita</span>
                        </div>
                        <div class="md:col-span-2">
                            <span class="text-gray-600">IBAN:</span>
                            <span class="font-bold ml-2 text-blue-600">AO06 0055. 0000. 9274. 3910. 1018. 0</span>
                        </div>
                    </div>
                    
                    <div class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <p class="text-sm text-blue-800">
                            <strong>Importante:</strong> Após o pagamento, faça upload do comprovativo no formulário de registro para ativação imediata da sua conta.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Final CTA Section -->
    <div class="py-24 bg-gradient-to-r from-blue-600 to-purple-600">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-white mb-6">
                Pronto para Transformar sua Gestão de Documentos?
            </h2>
            <p class="text-xl text-blue-100 mb-8">
                Junte-se a milhares de usuários que já transformaram sua produtividade com o UNIDOC
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                <a href="#plans" onclick="document.getElementById('plans').scrollIntoView({behavior: 'smooth'})" class="bg-white text-blue-600 px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    Começar Agora - Grátis por 15 dias
                </a>
                <a href="mailto:vambert_quaresma@gmail.com" class="border-2 border-white text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-white hover:text-blue-600 transition-all duration-300 transform hover:scale-105">
                    Falar com Especialista
                </a>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-3xl font-bold text-white">+75%</div>
                    <div class="text-blue-200">Produtividade</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-white">10x</div>
                    <div class="text-blue-200">Velocidade</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-white">100%</div>
                    <div class="text-blue-200">Segurança</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-white">24/7</div>
                    <div class="text-blue-200">Suporte</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
