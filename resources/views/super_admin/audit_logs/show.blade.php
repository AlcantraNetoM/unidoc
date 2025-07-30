@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <div class="flex items-center">
                        <a href="{{ route('super_admin.audit_logs.index') }}" class="text-gray-400 hover:text-gray-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Detalhes do Log</h1>
                            <p class="text-gray-600">Informações detalhadas da atividade</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">{{ $auditLog->created_at->format('d/m/Y H:i:s') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Main Log Info -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Ação</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @switch($auditLog->action)
                            @case('login') bg-green-100 text-green-800 @break
                            @case('logout') bg-gray-100 text-gray-800 @break
                            @case('create') bg-blue-100 text-blue-800 @break
                            @case('update') bg-yellow-100 text-yellow-800 @break
                            @case('delete') bg-red-100 text-red-800 @break
                            @case('approve') bg-green-100 text-green-800 @break
                            @case('reject') bg-red-100 text-red-800 @break
                            @case('failed_login') bg-red-100 text-red-800 @break
                            @default bg-gray-100 text-gray-800
                        @endswitch
                    ">
                        {{ ucfirst(str_replace('_', ' ', $auditLog->action)) }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Severidade</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @switch($auditLog->severity)
                            @case('low') bg-green-100 text-green-800 @break
                            @case('medium') bg-yellow-100 text-yellow-800 @break
                            @case('high') bg-orange-100 text-orange-800 @break
                            @case('critical') bg-red-100 text-red-800 @break
                            @default bg-gray-100 text-gray-800
                        @endswitch
                    ">
                        {{ ucfirst($auditLog->severity) }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Data/Hora</label>
                    <div class="text-sm text-gray-900">
                        {{ $auditLog->created_at->format('d/m/Y H:i:s') }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">IP Address</label>
                    <div class="text-sm text-gray-900">
                        {{ $auditLog->ip_address ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- User Info -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Informações do Usuário</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Nome</label>
                    <div class="text-sm text-gray-900">
                        {{ $auditLog->user_name ?? 'Sistema' }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Email</label>
                    <div class="text-sm text-gray-900">
                        {{ $auditLog->user_email ?? 'N/A' }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Função</label>
                    <div class="text-sm text-gray-900">
                        {{ $auditLog->user_role ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Model Info -->
        @if($auditLog->model)
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Objeto Afetado</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Tipo</label>
                    <div class="text-sm text-gray-900">
                        {{ $auditLog->model }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">ID</label>
                    <div class="text-sm text-gray-900">
                        {{ $auditLog->model_id ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Description -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Descrição</h3>
            <div class="text-sm text-gray-900 bg-gray-50 p-4 rounded-lg">
                {{ $auditLog->description ?? 'Nenhuma descrição disponível' }}
            </div>
        </div>

        <!-- Changes -->
        @if($auditLog->old_values || $auditLog->new_values)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Old Values -->
            @if($auditLog->old_values)
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Valores Anteriores</h3>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <pre class="text-sm text-red-800 whitespace-pre-wrap">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </div>
            @endif

            <!-- New Values -->
            @if($auditLog->new_values)
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Novos Valores</h3>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <pre class="text-sm text-green-800 whitespace-pre-wrap">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Technical Info -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Informações Técnicas</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">User Agent</label>
                    <div class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg break-all">
                        {{ $auditLog->user_agent ?? 'N/A' }}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">ID do Log</label>
                        <div class="text-sm text-gray-900">
                            {{ $auditLog->id }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Timestamp</label>
                        <div class="text-sm text-gray-900">
                            {{ $auditLog->created_at->toISOString() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
