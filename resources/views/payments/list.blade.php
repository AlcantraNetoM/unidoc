@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <h1 class="text-2xl font-bold text-white">
                    <i class="fas fa-credit-card mr-2"></i>
                    Histórico de Pagamentos
                </h1>
                <p class="text-blue-100 mt-1">Gerencie seus pagamentos e subscrições</p>
            </div>

            <div class="p-6">
                <!-- Botão para Novo Pagamento -->
                <div class="mb-6">
                    <a href="{{ route('payments.multi_month') }}" 
                       class="bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-md transition duration-200 inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Fazer Novo Pagamento
                    </a>
                </div>

                <!-- Lista de Pagamentos -->
                @if($payments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID / Data
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Meses
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Valor
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($payments as $payment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $payment->payment_date->format('d/m/Y H:i') }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $payment->months_paid }} {{ $payment->months_paid === 1 ? 'mês' : 'meses' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ number_format($payment->amount, 0, ',', '.') }} Kz
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($payment->status === 'pending')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Pendente
                                                </span>
                                            @elseif($payment->status === 'approved')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i>
                                                    Aprovado
                                                </span>
                                            @elseif($payment->status === 'rejected')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-times mr-1"></i>
                                                    Rejeitado
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                @if(isset($payment->type) && $payment->type === 'new_system')
                                                    <a href="{{ route('payment.status', $payment->id) }}" 
                                                       class="text-blue-600 hover:text-blue-900 transition duration-200">
                                                        <i class="fas fa-eye mr-1"></i>
                                                        Ver
                                                    </a>
                                                @endif
                                                @if($payment->payment_proof_path)
                                                    <a href="{{ route('payments.download', $payment->id) }}" 
                                                       class="text-green-600 hover:text-green-900 transition duration-200">
                                                        <i class="fas fa-download mr-1"></i>
                                                        Baixar
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-credit-card text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-500 mb-2">Nenhum Pagamento Encontrado</h3>
                        <p class="text-gray-400 mb-6">Você ainda não fez nenhum pagamento.</p>
                        <a href="{{ route('payments.multi_month') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-md transition duration-200 inline-flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Fazer Primeiro Pagamento
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
