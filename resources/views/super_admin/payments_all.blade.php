@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <div class="flex items-center">
                        <a href="{{ route('super_admin.financial_dashboard') }}" class="text-gray-400 hover:text-gray-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Histórico de Pagamentos</h1>
                            <p class="text-gray-600">Todos os pagamentos processados no sistema</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                        Total: {{ $payments->count() }} pagamentos
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="GET" action="{{ route('super_admin.payments.all') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Todos</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Aprovados</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendentes</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejeitados</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Plano</label>
                    <select name="plan_type" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Todos</option>
                        <option value="personal" {{ request('plan_type') === 'personal' ? 'selected' : '' }}>Pessoal</option>
                        <option value="business" {{ request('plan_type') === 'business' ? 'selected' : '' }}>Empresarial</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Data Inicial</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Data Final</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="md:col-span-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        Filtrar
                    </button>
                    <a href="{{ route('super_admin.payments.all') }}" class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition duration-200">
                        Limpar
                    </a>
                </div>
            </form>
        </div>

        <!-- Payments Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Pagamentos</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário/Empresa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plano</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meses</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data do Pagamento</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aprovado Por</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                                #{{ $payment->id }}
                            </td>
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
                                            @if($payment->user->empresa)
                                                <div class="text-xs text-gray-400">Empresa: {{ $payment->user->empresa->nome }}</div>
                                            @endif
                                        @elseif($payment->payment_type === 'empresa' && $payment->empresa)
                                            <div class="text-sm font-medium text-gray-900">{{ $payment->empresa->nome }}</div>
                                            <div class="text-sm text-gray-500">{{ $payment->empresa->email }}</div>
                                        @else
                                            <div class="text-sm text-gray-500">Entidade não encontrada</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $planType = $payment->plan_type ?? ($payment->payment_type === 'empresa' ? 'business' : 'personal');
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $planType === 'personal' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ $planType === 'personal' ? 'Pessoal' : 'Empresarial' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ number_format($payment->amount, 0, ',', '.') }} Kz
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $payment->months_paid ?? 1 }} mês(es)</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $payment->payment_date ? $payment->payment_date->format('d/m/Y H:i') : 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $payment->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                       ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($payment->approved_by && $payment->approver)
                                    <div class="text-sm text-gray-900">{{ $payment->approver->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $payment->approved_at->format('d/m/Y H:i') }}</div>
                                @else
                                    <span class="text-sm text-gray-500">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    @if($payment->payment_proof_path)
                                        <a href="{{ route('super_admin.payments.proof', $payment) }}" 
                                           target="_blank"
                                           class="text-blue-600 hover:text-blue-900">
                                            Ver Comprovativo
                                        </a>
                                    @endif
                                    @if($payment->status === 'pending')
                                        <form action="{{ route('super_admin.payments.approve', $payment) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="text-green-600 hover:text-green-900"
                                                    onclick="return confirm('Tem certeza que deseja aprovar este pagamento?')">
                                                Aprovar
                                            </button>
                                        </form>
                                        <form action="{{ route('super_admin.payments.reject', $payment) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Tem certeza que deseja rejeitar este pagamento?')">
                                                Rejeitar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                Nenhum pagamento encontrado
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($payments->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $payments->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
