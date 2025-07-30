@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                <h1 class="text-2xl font-bold text-white">
                    <i class="fas fa-check-circle mr-2"></i>
                    Status do Pagamento
                </h1>
            </div>

            <div class="p-6">
                <!-- Status do Pagamento -->
                <div class="text-center mb-6">
                    @if($payment->status === 'pending')
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <i class="fas fa-clock text-yellow-500 text-3xl mb-2"></i>
                            <h2 class="text-xl font-semibold text-yellow-800">Pagamento em Análise</h2>
                            <p class="text-yellow-700 mt-2">
                                Seu pagamento foi recebido e está sendo analisado pela nossa equipe.
                                Você receberá uma notificação quando for aprovado.
                            </p>
                        </div>
                    @elseif($payment->status === 'approved')
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <i class="fas fa-check-circle text-green-500 text-3xl mb-2"></i>
                            <h2 class="text-xl font-semibold text-green-800">Pagamento Aprovado!</h2>
                            <p class="text-green-700 mt-2">
                                Seu pagamento foi aprovado com sucesso. Sua conta foi estendida.
                            </p>
                        </div>
                    @elseif($payment->status === 'rejected')
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <i class="fas fa-times-circle text-red-500 text-3xl mb-2"></i>
                            <h2 class="text-xl font-semibold text-red-800">Pagamento Rejeitado</h2>
                            <p class="text-red-700 mt-2">
                                Infelizmente, seu pagamento foi rejeitado.
                            </p>
                            @if($payment->notes)
                                <div class="mt-3 p-3 bg-red-100 rounded">
                                    <p class="text-sm text-red-800">
                                        <strong>Motivo:</strong> {{ $payment->notes }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Detalhes do Pagamento -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-info-circle mr-2"></i>
                        Detalhes do Pagamento
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">ID do Pagamento</p>
                            <p class="font-mono font-medium">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Data do Pagamento</p>
                            <p class="font-medium">{{ $payment->payment_date->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Meses Pagos</p>
                            <p class="font-medium">{{ $payment->months_paid }} {{ $payment->months_paid === 1 ? 'mês' : 'meses' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Valor Total</p>
                            <p class="font-medium text-green-600">{{ number_format($payment->amount, 0, ',', '.') }} Kz</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tipo de Conta</p>
                            <p class="font-medium">
                                @if($payment->payment_type === 'user')
                                    <i class="fas fa-user text-blue-500 mr-1"></i>
                                    Conta Pessoal
                                @else
                                    <i class="fas fa-building text-green-500 mr-1"></i>
                                    Conta Empresarial
                                @endif
                            </p>
                        </div>
                        @if($payment->approved_at)
                            <div>
                                <p class="text-sm text-gray-600">Processado em</p>
                                <p class="font-medium">{{ $payment->approved_at->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Comprovativo -->
                @if($payment->payment_proof_path)
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-semibold text-blue-800 mb-3">
                            <i class="fas fa-receipt mr-2"></i>
                            Comprovativo de Pagamento
                        </h3>
                        <a href="{{ route('payments.download', $payment->id) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-200">
                            <i class="fas fa-download mr-2"></i>
                            Baixar Comprovativo
                        </a>
                    </div>
                @endif

                <!-- Ações -->
                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t">
                    <a href="{{ route('payments.status') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-md transition duration-200 text-center">
                        <i class="fas fa-list mr-2"></i>
                        Ver Todos os Pagamentos
                    </a>
                    
                    @if($payment->status === 'rejected')
                        <a href="{{ route('payments.multi_month') }}" 
                           class="bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-md transition duration-200 text-center">
                            <i class="fas fa-redo mr-2"></i>
                            Fazer Novo Pagamento
                        </a>
                    @endif
                    
                    <a href="{{ route('dashboard') }}" 
                       class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-6 rounded-md transition duration-200 text-center">
                        <i class="fas fa-home mr-2"></i>
                        Voltar ao Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
