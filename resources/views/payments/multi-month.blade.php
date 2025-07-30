@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <h1 class="text-2xl font-bold text-white">
                    <i class="fas fa-credit-card mr-2"></i>
                    Pagamento Multi-Mês
                </h1>
                <p class="text-blue-100 mt-1">Pague vários meses antecipadamente e garanta o acesso contínuo</p>
            </div>

            <div class="p-6">
                <!-- Informações da Conta -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        <i class="fas fa-user-circle mr-2"></i>
                        Informações da Conta
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Tipo de Conta</p>
                            <p class="font-medium">
                                @if($accountInfo['type'] === 'personal')
                                    <i class="fas fa-user text-blue-500 mr-1"></i>
                                    Conta Pessoal
                                @else
                                    <i class="fas fa-building text-green-500 mr-1"></i>
                                    Conta Empresarial
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Nome</p>
                            <p class="font-medium">{{ $accountInfo['name'] }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status Atual</p>
                            <p class="font-medium text-blue-600">{{ $accountInfo['status'] }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total de Meses Pagos</p>
                            <p class="font-medium text-green-600">{{ $accountInfo['total_months_paid'] }} mês(es)</p>
                        </div>
                    </div>
                </div>

                <!-- Formulário de Pagamento -->
                <form action="{{ route('payments.multi_month.process') }}" method="POST" enctype="multipart/form-data" id="paymentForm" data-monthly-price="{{ $monthlyPrice }}">
                    @csrf
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Seleção de Meses -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-800">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                Selecionar Período
                            </h3>
                            
                            <div>
                                <label for="months" class="block text-sm font-medium text-gray-700 mb-2">
                                    Número de Meses
                                </label>
                                <select name="months" id="months" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="">Selecione...</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'mês' : 'meses' }}</option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Cálculo do Valor -->
                            <div class="bg-blue-50 rounded-lg p-4">
                                <h4 class="font-semibold text-blue-800 mb-2">Cálculo do Valor</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span>Valor por mês:</span>
                                        <span class="font-medium">{{ number_format($monthlyPrice, 0, ',', '.') }} Kz</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Meses selecionados:</span>
                                        <span class="font-medium" id="selectedMonths">0</span>
                                    </div>
                                    <hr class="border-blue-200">
                                    <div class="flex justify-between text-lg font-bold text-blue-700">
                                        <span>Total a pagar:</span>
                                        <span id="totalAmount">0 Kz</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Upload do Comprovativo -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-800">
                                <i class="fas fa-receipt mr-2"></i>
                                Comprovativo de Pagamento
                            </h3>
                            
                            <!-- Informações de Pagamento -->
                            <div class="bg-green-50 rounded-lg p-4">
                                <h4 class="font-semibold text-green-800 mb-3">Dados para Transferência</h4>
                                <div class="space-y-2 text-sm">
                                    <div>
                                        <span class="text-gray-600">IBAN:</span>
                                        <span class="font-mono font-medium">AO06 0055. 0000. 9274. 3910. 1018. 0</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Banco:</span>
                                        <span class="font-medium">Atlântico</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Titular:</span>
                                        <span class="font-medium">Vambert Capita</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-2">
                                    Anexar Comprovativo
                                </label>
                                <input type="file" name="payment_proof" id="payment_proof" 
                                       accept="image/*,.pdf" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                       required>
                                <p class="mt-1 text-xs text-gray-500">
                                    Formatos aceitos: JPG, PNG, PDF (máx. 5MB)
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="flex flex-col sm:flex-row gap-3 mt-6 pt-6 border-t">
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-md transition duration-200 flex items-center justify-center">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Submeter Pagamento
                        </button>
                        <a href="{{ route('dashboard') }}" 
                           class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-6 rounded-md transition duration-200 text-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Voltar ao Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const monthsSelect = document.getElementById('months');
    const selectedMonthsSpan = document.getElementById('selectedMonths');
    const totalAmountSpan = document.getElementById('totalAmount');
    const paymentForm = document.getElementById('paymentForm');
    const monthlyRate = parseInt(paymentForm.dataset.monthlyPrice) || 5000;

    monthsSelect.addEventListener('change', function() {
        const months = parseInt(this.value) || 0;
        const total = months * monthlyRate;
        
        selectedMonthsSpan.textContent = months;
        totalAmountSpan.textContent = total.toLocaleString('pt-AO') + ' Kz';
    });

    // Validação do formulário
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        const months = document.getElementById('months').value;
        const proof = document.getElementById('payment_proof').files[0];
        
        if (!months || !proof) {
            e.preventDefault();
            alert('Por favor, preencha todos os campos obrigatórios.');
            return;
        }
        
        // Verificar tamanho do arquivo
        if (proof.size > 5 * 1024 * 1024) { // 5MB
            e.preventDefault();
            alert('O arquivo é muito grande. Tamanho máximo: 5MB');
            return;
        }
    });
});
</script>
@endsection
