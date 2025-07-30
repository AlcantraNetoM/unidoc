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
                            <h1 class="text-3xl font-bold text-gray-900">Dashboard Financeiro</h1>
                            <p class="text-gray-600">Relatórios e analytics de pagamentos</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                        Receita Total: {{ number_format($total_revenue, 2, ',', '.') }} Kz
                    </div>
                    <span class="text-gray-700">{{ now()->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Revenue -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Receita Total</p>
                        <p class="text-3xl font-bold">{{ number_format($total_revenue, 0, ',', '.') }} Kz</p>
                        <p class="text-green-100 text-xs mt-1">+{{ number_format($revenue_growth, 1) }}% vs mês anterior</p>
                    </div>
                    <div class="p-3 bg-green-400 bg-opacity-30 rounded-full">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Personal Plan Revenue -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Receita Plano Pessoal</p>
                        <p class="text-3xl font-bold">{{ number_format($personal_revenue, 0, ',', '.') }} Kz</p>
                        <p class="text-blue-100 text-xs mt-1">{{ $personal_payments_count }} pagamentos</p>
                    </div>
                    <div class="p-3 bg-blue-400 bg-opacity-30 rounded-full">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Business Plan Revenue -->
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Receita Plano Empresarial</p>
                        <p class="text-3xl font-bold">{{ number_format($business_revenue, 0, ',', '.') }} Kz</p>
                        <p class="text-purple-100 text-xs mt-1">{{ $business_payments_count }} pagamentos</p>
                    </div>
                    <div class="p-3 bg-purple-400 bg-opacity-30 rounded-full">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Subscriptions -->
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">Assinantes Ativos</p>
                        <p class="text-3xl font-bold">{{ $active_subscriptions }}</p>
                        <p class="text-orange-100 text-xs mt-1">{{ $conversion_rate }}% taxa de conversão</p>
                    </div>
                    <div class="p-3 bg-orange-400 bg-opacity-30 rounded-full">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Revenue Chart -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Receita Mensal</h3>
                    <div class="flex space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Pessoal: 5.000 Kz
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            Empresarial: 15.000 Kz
                        </span>
                    </div>
                </div>
                <div class="h-80">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Plan Distribution -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Distribuição de Planos</h3>
                    <span class="text-sm text-gray-500">Total: {{ $total_payments }} pagamentos</span>
                </div>
                <div class="h-80">
                    <canvas id="planDistributionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Payment Timeline Chart -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">Timeline de Pagamentos (Últimos 12 Meses)</h3>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-600">Meses Bons (>50K Kz)</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-600">Meses Fracos (<50K Kz)</span>
                    </div>
                </div>
            </div>
            <div class="h-96">
                <canvas id="timelineChart"></canvas>
            </div>
        </div>

        <!-- Recent Payments Table -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">Pagamentos Recentes</h3>
                <a href="{{ route('super_admin.payments.all') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 text-sm font-medium">
                    Ver Todos os Pagamentos
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plano</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data do Pagamento</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recent_payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            @if($payment->payment_type === 'user' && $payment->user)
                                                <span class="text-sm font-medium text-blue-700">{{ substr($payment->user->name, 0, 1) }}</span>
                                            @elseif($payment->payment_type === 'empresa' && $payment->empresa)
                                                <span class="text-sm font-medium text-blue-700">{{ substr($payment->empresa->nome, 0, 1) }}</span>
                                            @else
                                                <span class="text-sm font-medium text-gray-500">?</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        @if($payment->payment_type === 'user' && $payment->user)
                                            <div class="text-sm font-medium text-gray-900">{{ $payment->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $payment->user->email }}</div>
                                        @elseif($payment->payment_type === 'empresa' && $payment->empresa)
                                            <div class="text-sm font-medium text-gray-900">{{ $payment->empresa->nome }}</div>
                                            <div class="text-sm text-gray-500">{{ $payment->empresa->email }}</div>
                                        @else
                                            <div class="text-sm text-gray-500">Dados não encontrados</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($payment->payment_type === 'user' && $payment->user && $payment->user->empresa)
                                    <div class="text-sm text-gray-900">{{ $payment->user->empresa->nome }}</div>
                                @elseif($payment->payment_type === 'empresa' && $payment->empresa)
                                    <div class="text-sm text-gray-900">{{ $payment->empresa->nome }}</div>
                                @else
                                    <span class="text-sm text-gray-500">Conta Pessoal</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $payment->plan_type === 'personal' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ $payment->plan_type === 'personal' ? 'Pessoal' : 'Empresarial' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ number_format($payment->amount, 0, ',', '.') }} Kz
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $payment->payment_date->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $payment->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                       ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Nenhum pagamento encontrado
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Métricas de Retenção e Churn -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Retention Overview -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Retenção e Churn</h3>
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ $retention_metrics['retention_rate'] }}%</div>
                        <div class="text-sm text-green-700">Taxa de Retenção</div>
                    </div>
                    <div class="text-center p-4 bg-red-50 rounded-lg">
                        <div class="text-2xl font-bold text-red-600">{{ $retention_metrics['churn_rate'] }}%</div>
                        <div class="text-sm text-red-700">Taxa de Churn</div>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">LTV Médio:</span>
                        <span class="font-medium">{{ number_format($retention_metrics['avg_ltv'], 0, ',', '.') }} Kz</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Duração Média:</span>
                        <span class="font-medium">{{ $retention_metrics['avg_subscription_duration'] }} meses</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Usuários Ativos:</span>
                        <span class="font-medium">{{ $retention_metrics['total_subscribers'] - $retention_metrics['churned_users'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Retention Chart -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Retenção Mensal</h3>
                <div class="h-64">
                    <canvas id="retentionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Análise Geográfica -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Geographic Overview -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Distribuição Geográfica</h3>
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <div class="text-sm text-blue-700">Província Líder</div>
                        <div class="text-lg font-bold text-blue-900">
                            {{ $geographic_data['top_province']->provincia ?? 'N/A' }}
                        </div>
                        <div class="text-sm text-blue-600">
                            {{ $geographic_data['top_province']->users_count ?? 0 }} usuários
                        </div>
                    </div>
                    
                    <div class="p-4 bg-purple-50 rounded-lg">
                        <div class="text-sm text-purple-700">Cidade Líder</div>
                        <div class="text-lg font-bold text-purple-900">
                            {{ $geographic_data['top_city']->cidade ?? 'N/A' }}
                        </div>
                        <div class="text-sm text-purple-600">
                            {{ number_format($geographic_data['top_city']->revenue ?? 0, 0, ',', '.') }} Kz
                        </div>
                    </div>
                </div>
            </div>

            <!-- Province Revenue Chart -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Receita por Província</h3>
                <div class="h-64">
                    <canvas id="provinceChart"></canvas>
                </div>
            </div>

            <!-- Top Cities Table -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Top Cidades</h3>
                <div class="space-y-3">
                    @foreach($geographic_data['city_data']->take(6) as $index => $city)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <div class="font-medium text-gray-900">{{ $city->cidade }}</div>
                            <div class="text-sm text-gray-600">{{ $city->provincia }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-gray-900">{{ $city->users_count }}</div>
                            <div class="text-sm text-gray-600">usuários</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Financial Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Monthly Performance -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 ml-3">Performance Mensal</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Este Mês:</span>
                        <span class="font-medium">{{ number_format($current_month_revenue, 0, ',', '.') }} Kz</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Mês Anterior:</span>
                        <span class="font-medium">{{ number_format($previous_month_revenue, 0, ',', '.') }} Kz</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Crescimento:</span>
                        <span class="font-medium {{ $revenue_growth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $revenue_growth >= 0 ? '+' : '' }}{{ number_format($revenue_growth, 1) }}%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Average Values -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 ml-3">Médias</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Receita/Mês:</span>
                        <span class="font-medium">{{ number_format($avg_monthly_revenue, 0, ',', '.') }} Kz</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Pagamentos/Mês:</span>
                        <span class="font-medium">{{ number_format($avg_monthly_payments, 1) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Valor/Pagamento:</span>
                        <span class="font-medium">{{ number_format($avg_payment_value, 0, ',', '.') }} Kz</span>
                    </div>
                </div>
            </div>

            <!-- Top Performers -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 ml-3">Destaques</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Melhor Mês:</span>
                        <span class="font-medium text-green-600">{{ $best_month }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Plano Mais Popular:</span>
                        <span class="font-medium">{{ $popular_plan }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Taxa Conversão:</span>
                        <span class="font-medium">{{ number_format($conversion_rate, 1) }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: @json($chart_data['months']),
            datasets: [{
                label: 'Plano Pessoal',
                data: @json($chart_data['personal_revenue']),
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1
            }, {
                label: 'Plano Empresarial',
                data: @json($chart_data['business_revenue']),
                backgroundColor: 'rgba(139, 92, 246, 0.8)',
                borderColor: 'rgba(139, 92, 246, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('pt-BR') + ' Kz';
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y.toLocaleString('pt-BR') + ' Kz';
                        }
                    }
                }
            }
        }
    });

    // Plan Distribution Chart
    const planCtx = document.getElementById('planDistributionChart').getContext('2d');
    const planChart = new Chart(planCtx, {
        type: 'doughnut',
        data: {
            labels: ['Plano Pessoal', 'Plano Empresarial'],
            datasets: [{
                data: [{{ (int) $personal_payments_count }}, {{ (int) $business_payments_count }}],
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(139, 92, 246, 0.8)'
                ],
                borderColor: [
                    'rgba(59, 130, 246, 1)',
                    'rgba(139, 92, 246, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Timeline Chart
    const timelineCtx = document.getElementById('timelineChart').getContext('2d');
    const timelineData = @json($chart_data['total_monthly_revenue']);
    const timelineLabels = @json($chart_data['months']);
    
    // Preparar cores baseadas nos valores
    const backgroundColors = timelineData.map(value => 
        value >= 50000 ? 'rgba(34, 197, 94, 0.2)' : 'rgba(239, 68, 68, 0.2)'
    );
    const borderColors = timelineData.map(value => 
        value >= 50000 ? 'rgba(34, 197, 94, 1)' : 'rgba(239, 68, 68, 1)'
    );
    const pointColors = timelineData.map(value => 
        value >= 50000 ? 'rgba(34, 197, 94, 1)' : 'rgba(239, 68, 68, 1)'
    );
    
    const timelineChart = new Chart(timelineCtx, {
        type: 'line',
        data: {
            labels: timelineLabels,
            datasets: [{
                label: 'Receita Total',
                data: timelineData,
                backgroundColor: backgroundColors,
                borderColor: borderColors,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: pointColors,
                pointBorderColor: pointColors,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('pt-BR') + ' Kz';
                        }
                    },
                    grid: {
                        color: function(context) {
                            if (context.tick.value === 50000) {
                                return 'rgba(239, 68, 68, 0.5)';
                            }
                            return 'rgba(0, 0, 0, 0.1)';
                        },
                        lineWidth: function(context) {
                            if (context.tick.value === 50000) {
                                return 2;
                            }
                            return 1;
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.parsed.y;
                            const status = value >= 50000 ? '✅ Mês Bom' : '❌ Mês Fraco';
                            return status + ': ' + value.toLocaleString('pt-BR') + ' Kz';
                        }
                    }
                },
                legend: {
                    display: false
                }
            }
        }
    });
    
    // Retention Chart
    const retentionCtx = document.getElementById('retentionChart').getContext('2d');
    const retentionChart = new Chart(retentionCtx, {
        type: 'line',
        data: {
            labels: @json(collect($retention_metrics['monthly_retention'])->pluck('month')),
            datasets: [{
                label: 'Taxa de Retenção (%)',
                data: @json(collect($retention_metrics['monthly_retention'])->pluck('retention_rate')),
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                borderColor: 'rgba(34, 197, 94, 1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgba(34, 197, 94, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Retenção: ' + context.parsed.y.toFixed(1) + '%';
                        }
                    }
                },
                legend: {
                    display: false
                }
            }
        }
    });

    // Province Chart
    const provinceCtx = document.getElementById('provinceChart').getContext('2d');
    const provinceChart = new Chart(provinceCtx, {
        type: 'doughnut',
        data: {
            labels: @json($geographic_data['chart_data']['labels']),
            datasets: [{
                data: @json($geographic_data['chart_data']['revenue']),
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(139, 92, 246, 0.8)',
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(251, 146, 60, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(168, 85, 247, 0.8)'
                ],
                borderColor: [
                    'rgba(59, 130, 246, 1)',
                    'rgba(139, 92, 246, 1)',
                    'rgba(34, 197, 94, 1)',
                    'rgba(251, 146, 60, 1)',
                    'rgba(239, 68, 68, 1)',
                    'rgba(168, 85, 247, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + context.parsed.toLocaleString('pt-BR') + ' Kz (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
