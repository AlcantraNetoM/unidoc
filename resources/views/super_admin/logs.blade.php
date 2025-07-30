@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Logs de Auditoria</h1>
                <p class="text-gray-600 mt-2">Monitore todas as atividades do sistema</p>
            </div>
            <a href="{{ route('super_admin.dashboard') }}" 
               class="inline-block bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition-colors">
                ← Voltar ao Dashboard
            </a>
        </div>

        <!-- Estatísticas de Atividade -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Usuários Criados Hoje</h3>
                        <p class="text-2xl font-bold text-blue-600">{{ $atividades['usuarios_criados_hoje'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Empresas Criadas Hoje</h3>
                        <p class="text-2xl font-bold text-green-600">{{ $atividades['empresas_criadas_hoje'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Logins Hoje</h3>
                        <p class="text-2xl font-bold text-yellow-600">{{ $atividades['logins_hoje'] }}</p>
                        <p class="text-xs text-gray-500">Em desenvolvimento</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Uploads Hoje</h3>
                        <p class="text-2xl font-bold text-purple-600">{{ $atividades['uploads_hoje'] }}</p>
                        <p class="text-xs text-gray-500">Em desenvolvimento</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logs Recentes -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Logs Recentes do Sistema</h2>
                <p class="text-gray-600 text-sm">Últimas {{ count($logs) }} entradas de log</p>
            </div>

            @if(count($logs) > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($logs as $index => $log)
                        @php
                            $logLevel = 'INFO';
                            $logColor = 'gray';
                            
                            if(strpos($log, 'ERROR') !== false) {
                                $logLevel = 'ERROR';
                                $logColor = 'red';
                            } elseif(strpos($log, 'WARNING') !== false) {
                                $logLevel = 'WARNING';
                                $logColor = 'yellow';
                            } elseif(strpos($log, 'DEBUG') !== false) {
                                $logLevel = 'DEBUG';
                                $logColor = 'blue';
                            }
                        @endphp
                        
                        <div class="px-6 py-4 hover:bg-gray-50">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $logColor }}-100 text-{{ $logColor }}-800">
                                        {{ $logLevel }}
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm text-gray-900 font-mono bg-gray-50 p-2 rounded overflow-x-auto">
                                        {{ $log }}
                                    </div>
                                </div>
                                <div class="flex-shrink-0 text-xs text-gray-500">
                                    #{{ $index + 1 }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="px-6 py-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum log encontrado</h3>
                    <p class="mt-1 text-sm text-gray-500">Não há logs disponíveis no momento.</p>
                </div>
            @endif
        </div>

        <!-- Funcionalidades Futuras -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-800 mb-4">🚀 Funcionalidades Futuras</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="font-medium text-blue-900 mb-2">Sistema de Auditoria Avançado:</h4>
                    <ul class="list-disc list-inside space-y-1 text-blue-700 text-sm">
                        <li>Rastreamento de logins e logouts</li>
                        <li>Logs de criação, edição e exclusão</li>
                        <li>Auditoria de uploads de arquivos</li>
                        <li>Histórico de mudanças de permissões</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium text-blue-900 mb-2">Recursos Adicionais:</h4>
                    <ul class="list-disc list-inside space-y-1 text-blue-700 text-sm">
                        <li>Filtros por data, usuário e tipo</li>
                        <li>Exportação em CSV/PDF</li>
                        <li>Alertas em tempo real</li>
                        <li>Dashboard de análise de atividades</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
