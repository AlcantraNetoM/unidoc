@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Configurações do Sistema</h1>
                <p class="text-gray-600 mt-2">Configure as opções globais do sistema</p>
            </div>
            <a href="{{ route('super_admin.dashboard') }}" 
               class="inline-block bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition-colors">
                ← Voltar ao Dashboard
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Estatísticas de Sistema -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Arquivos</h3>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['total_files'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Armazenamento Usado</h3>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['total_storage_used'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Tamanho Médio</h3>
                        <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['average_file_size'] / 1024, 2) }} KB</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Configurações do Sistema -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Configurações da Aplicação</h2>
                
                <form action="{{ route('super_admin.settings.update') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label for="app_name" class="block text-sm font-medium text-gray-700 mb-2">Nome da Aplicação</label>
                        <input type="text" 
                               id="app_name" 
                               name="app_name" 
                               value="{{ $config['app_name'] }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="app_url" class="block text-sm font-medium text-gray-700 mb-2">URL da Aplicação</label>
                        <input type="url" 
                               id="app_url" 
                               name="app_url" 
                               value="{{ $config['app_url'] }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="timezone" class="block text-sm font-medium text-gray-700 mb-2">Fuso Horário</label>
                        <select id="timezone" 
                                name="timezone" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="America/Sao_Paulo" {{ $config['timezone'] == 'America/Sao_Paulo' ? 'selected' : '' }}>América/São Paulo</option>
                            <option value="UTC" {{ $config['timezone'] == 'UTC' ? 'selected' : '' }}>UTC</option>
                            <option value="America/New_York" {{ $config['timezone'] == 'America/New_York' ? 'selected' : '' }}>América/Nova York</option>
                            <option value="Europe/London" {{ $config['timezone'] == 'Europe/London' ? 'selected' : '' }}>Europa/Londres</option>
                        </select>
                    </div>

                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            Salvar Configurações
                        </button>
                    </div>
                </form>
            </div>

            <!-- Informações do Sistema -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informações do Sistema</h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Driver de Email:</span>
                        <span class="font-medium text-gray-900">{{ ucfirst($config['mail_driver']) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Tamanho Max. Arquivo:</span>
                        <span class="font-medium text-gray-900">{{ $config['max_file_size'] }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Modo Debug:</span>
                        <span class="font-medium {{ $config['debug_mode'] ? 'text-red-600' : 'text-green-600' }}">
                            {{ $config['debug_mode'] ? 'Ativado' : 'Desativado' }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Versão PHP:</span>
                        <span class="font-medium text-gray-900">{{ PHP_VERSION }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600">Versão Laravel:</span>
                        <span class="font-medium text-gray-900">{{ app()->version() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configurações Avançadas -->
        <div class="mt-8 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Configurações Avançadas</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-medium text-gray-900 mb-2">Cache</h3>
                    <p class="text-sm text-gray-600 mb-3">Limpar cache da aplicação</p>
                    <button class="w-full bg-yellow-600 text-white py-2 px-3 rounded hover:bg-yellow-700 transition-colors text-sm">
                        Limpar Cache
                    </button>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-medium text-gray-900 mb-2">Logs</h3>
                    <p class="text-sm text-gray-600 mb-3">Limpar logs antigos</p>
                    <button class="w-full bg-red-600 text-white py-2 px-3 rounded hover:bg-red-700 transition-colors text-sm">
                        Limpar Logs
                    </button>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-medium text-gray-900 mb-2">Backup</h3>
                    <p class="text-sm text-gray-600 mb-3">Criar backup do sistema</p>
                    <button class="w-full bg-green-600 text-white py-2 px-3 rounded hover:bg-green-700 transition-colors text-sm">
                        Criar Backup
                    </button>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-medium text-gray-900 mb-2">Manutenção</h3>
                    <p class="text-sm text-gray-600 mb-3">Ativar modo manutenção</p>
                    <button class="w-full bg-orange-600 text-white py-2 px-3 rounded hover:bg-orange-700 transition-colors text-sm">
                        Ativar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
