@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <div class="flex items-center">
                        <a href="{{ route('super_admin.dashboard') }}" class="text-gray-400 hover:text-gray-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Sistema de Auditoria e Logs</h1>
                            <p class="text-gray-600">Monitoramento e rastreamento de atividades do sistema</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('super_admin.audit_logs.export', request()->query()) }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Exportar CSV
                    </a>
                    <span class="text-gray-700">{{ now()->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total de Logs</p>
                        <p class="text-3xl font-bold">{{ number_format($stats['total_logs'] ?? 0) }}</p>
                    </div>
                    <div class="p-3 bg-blue-400 bg-opacity-30 rounded-full">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Logs Hoje</p>
                        <p class="text-3xl font-bold">{{ number_format($stats['today_logs'] ?? 0) }}</p>
                    </div>
                    <div class="p-3 bg-green-400 bg-opacity-30 rounded-full">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium">Logs Críticos</p>
                        <p class="text-3xl font-bold">{{ number_format($stats['critical_logs'] ?? 0) }}</p>
                    </div>
                    <div class="p-3 bg-red-400 bg-opacity-30 rounded-full">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">Login Falhados Hoje</p>
                        <p class="text-3xl font-bold">{{ number_format($stats['failed_logins'] ?? 0) }}</p>
                    </div>
                    <div class="p-3 bg-yellow-400 bg-opacity-30 rounded-full">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Usuários Ativos Hoje</p>
                        <p class="text-3xl font-bold">{{ number_format($stats['unique_users_today'] ?? 0) }}</p>
                    </div>
                    <div class="p-3 bg-purple-400 bg-opacity-30 rounded-full">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Actions Chart -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Ações Mais Frequentes (30 dias)</h3>
                <div class="h-64">
                    <canvas id="actionsChart"></canvas>
                </div>
            </div>

            <!-- Daily Activity Chart -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Atividade Diária (14 dias)</h3>
                <div class="h-64">
                    <canvas id="dailyActivityChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Filtros</h3>
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ação</label>
                    <select name="action" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todas</option>
                        <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                        <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Logout</option>
                        <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Criar</option>
                        <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Atualizar</option>
                        <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Deletar</option>
                        <option value="approve" {{ request('action') == 'approve' ? 'selected' : '' }}>Aprovar</option>
                        <option value="reject" {{ request('action') == 'reject' ? 'selected' : '' }}>Rejeitar</option>
                        <option value="failed_login" {{ request('action') == 'failed_login' ? 'selected' : '' }}>Login Falhado</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Severidade</label>
                    <select name="severity" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todas</option>
                        <option value="low" {{ request('severity') == 'low' ? 'selected' : '' }}>Baixa</option>
                        <option value="medium" {{ request('severity') == 'medium' ? 'selected' : '' }}>Média</option>
                        <option value="high" {{ request('severity') == 'high' ? 'selected' : '' }}>Alta</option>
                        <option value="critical" {{ request('severity') == 'critical' ? 'selected' : '' }}>Crítica</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Modelo</label>
                    <select name="model" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todos</option>
                        <option value="User" {{ request('model') == 'User' ? 'selected' : '' }}>Usuário</option>
                        <option value="Empresa" {{ request('model') == 'Empresa' ? 'selected' : '' }}>Empresa</option>
                        <option value="Payment" {{ request('model') == 'Payment' ? 'selected' : '' }}>Pagamento</option>
                        <option value="Category" {{ request('model') == 'Category' ? 'selected' : '' }}>Categoria</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data De</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data Até</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Logs Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">Logs de Auditoria</h3>
                    <form action="{{ route('super_admin.audit_logs.delete-old') }}" method="POST" class="inline">
                        @csrf
                        <select name="days" class="rounded-lg border-gray-300 text-sm mr-2">
                            <option value="30">30 dias</option>
                            <option value="60">60 dias</option>
                            <option value="90" selected>90 dias</option>
                            <option value="180">180 dias</option>
                        </select>
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm"
                                onclick="return confirm('Tem certeza que deseja deletar logs antigos?')">
                            Limpar Logs Antigos
                        </button>
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data/Hora</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ação</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modelo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Severidade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if(isset($logs) && $logs->count() > 0)
                            @foreach($logs as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $log->created_at->format('d/m/Y') }}</div>
                                    <div class="text-sm text-gray-500">{{ $log->created_at->format('H:i:s') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($log->action == 'login') bg-green-100 text-green-800
                                        @elseif($log->action == 'logout') bg-gray-100 text-gray-800
                                        @elseif($log->action == 'create') bg-blue-100 text-blue-800
                                        @elseif($log->action == 'update') bg-yellow-100 text-yellow-800
                                        @elseif($log->action == 'delete') bg-red-100 text-red-800
                                        @elseif($log->action == 'approve') bg-green-100 text-green-800
                                        @elseif($log->action == 'reject') bg-red-100 text-red-800
                                        @elseif($log->action == 'failed_login') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif
                                    ">
                                        {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($log->user_name)
                                        <div class="text-sm font-medium text-gray-900">{{ $log->user_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $log->user_email }}</div>
                                    @else
                                        <span class="text-sm text-gray-500">Sistema</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($log->model)
                                        <div class="text-sm text-gray-900">{{ $log->model }}</div>
                                        @if($log->model_id)
                                            <div class="text-sm text-gray-500">ID: {{ $log->model_id }}</div>
                                        @endif
                                    @else
                                        <span class="text-sm text-gray-500">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $log->ip_address ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($log->severity == 'low') bg-green-100 text-green-800
                                        @elseif($log->severity == 'medium') bg-yellow-100 text-yellow-800
                                        @elseif($log->severity == 'high') bg-orange-100 text-orange-800
                                        @elseif($log->severity == 'critical') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif
                                    ">
                                        {{ ucfirst($log->severity) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate">{{ $log->description }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('super_admin.audit_logs.show', $log) }}" 
                                       class="text-blue-600 hover:text-blue-900">Ver Detalhes</a>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                    Nenhum log encontrado.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(isset($logs) && method_exists($logs, 'withQueryString'))
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $logs->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Verificar se os dados dos gráficos existem
    const actionsChartData = @json($actionsChart ?? []);
    const dailyActivityData = @json($dailyActivity ?? []);
    
    // Actions Chart
    const actionsCtx = document.getElementById('actionsChart');
    if (actionsCtx && Object.keys(actionsChartData).length > 0) {
        new Chart(actionsCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(actionsChartData),
                datasets: [{
                    data: Object.values(actionsChartData),
                    backgroundColor: [
                        '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#06B6D4', '#F97316'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Daily Activity Chart
    const dailyCtx = document.getElementById('dailyActivityChart');
    if (dailyCtx && Object.keys(dailyActivityData).length > 0) {
        new Chart(dailyCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: Object.keys(dailyActivityData),
                datasets: [{
                    label: 'Atividade Diária',
                    data: Object.values(dailyActivityData),
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
});
</script>
@endsection
