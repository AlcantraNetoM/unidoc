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
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                            Acessar Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-4 py-2 rounded-lg transition duration-200">Entrar</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl font-bold text-white mb-6">
                Sistema de Gestão de Arquivos
            </h1>
            <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
                Transforme sua gestão de documentos com nossa plataforma profissional. 
                Organização, segurança e eficiência em uma única solução.
            </p>
            
            @auth
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold text-lg rounded-xl hover:bg-gray-100 transition-all duration-300">
                    Acessar Dashboard
                </a>
            @else
                <a href="#plans" onclick="document.getElementById('plans').scrollIntoView({behavior: 'smooth'})" class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold text-lg rounded-xl hover:bg-gray-100 transition-all duration-300">
                    Escolher Plano
                </a>
            @endauth
        </div>
    </div>

    @guest
    <!-- Plans Section -->
    <div id="plans" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Escolha Seu Plano</h2>
                <p class="text-xl text-gray-600">
                    Selecione a opção que melhor se adapta às suas necessidades
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- Plano Empresarial -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden border hover:shadow-xl transition-shadow duration-300">
                    <div class="bg-blue-600 p-6 text-white text-center">
                        <h3 class="text-2xl font-bold mb-2">Plano Empresarial</h3>
                        <p class="text-blue-100">Para empresas que buscam excelência</p>
                    </div>
                    
                    <div class="p-6">
                        <ul class="space-y-3 mb-6">
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Gestão completa de usuários</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Dashboard administrativo</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Relatórios avançados</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Armazenamento ilimitado</span>
                            </li>
                        </ul>
                        
                        <div class="text-center mb-6">
                            <div class="text-3xl font-bold text-blue-600">15.000 Kz</div>
                            <div class="text-gray-500">por mês</div>
                        </div>
                        
                        <a href="{{ route('plan.select', 'empresa') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg font-semibold text-center block transition-colors duration-300">
                            Escolher Plano
                        </a>
                    </div>
                </div>

                <!-- Plano Pessoal -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden border hover:shadow-xl transition-shadow duration-300">
                    <div class="bg-green-600 p-6 text-white text-center">
                        <h3 class="text-2xl font-bold mb-2">Plano Pessoal</h3>
                        <p class="text-green-100">Ideal para uso individual</p>
                    </div>
                    
                    <div class="p-6">
                        <ul class="space-y-3 mb-6">
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Acesso pessoal completo</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Organização por categorias</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Interface intuitiva</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Suporte via email</span>
                            </li>
                        </ul>
                        
                        <div class="text-center mb-6">
                            <div class="text-3xl font-bold text-green-600">5.000 Kz</div>
                            <div class="text-gray-500">por mês</div>
                        </div>
                        
                        <a href="{{ route('plan.select', 'pessoal') }}" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-lg font-semibold text-center block transition-colors duration-300">
                            Escolher Plano
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endguest
</div>
@endsection
