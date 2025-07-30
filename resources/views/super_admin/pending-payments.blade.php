@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                <h1 class="text-2xl font-bold text-white">
                    <i class="fas fa-credit-card mr-2"></i>
                    Gestão de Pagamentos
                </h1>
                <p class="text-purple-100 mt-1">Aprovar ou rejeitar pagamentos pendentes</p>
            </div>

            <div class="p-6">
                @if($payments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Conta
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Detalhes do Pagamento
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Data/Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Comprovativo
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($payments as $payment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                @if($payment->payment_type === 'user')
                                                    @php $entity = \App\Models\User::find($payment->entity_id); @endphp
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                            <i class="fas fa-user text-blue-600"></i>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $entity->name ?? 'Usuário não encontrado' }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            Conta Pessoal
                                                        </div>
                                                        <div class="text-xs text-gray-400">
                                                            {{ $entity->email ?? '' }}
                                                        </div>
                                                    </div>
                                                @else
                                                    @php $entity = \App\Models\Empresa::find($payment->entity_id); @endphp
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                                            <i class="fas fa-building text-green-600"></i>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $entity->nome ?? 'Empresa não encontrada' }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            Conta Empresarial
                                                        </div>
                                                        <div class="text-xs text-gray-400">
                                                            {{ $entity->email ?? '' }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                <div class="font-medium">
                                                    {{ $payment->months_paid }} {{ $payment->months_paid === 1 ? 'mês' : 'meses' }}
                                                </div>
                                                <div class="text-green-600 font-semibold">
                                                    {{ number_format($payment->amount, 0, ',', '.') }} Kz
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    ID: #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm">
                                                <div class="text-gray-900">
                                                    {{ $payment->payment_date->format('d/m/Y') }}
                                                </div>
                                                <div class="text-gray-500">
                                                    {{ $payment->payment_date->format('H:i') }}
                                                </div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Pendente
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($payment->payment_proof_path)
                                                <a href="{{ route('payments.download', $payment->id) }}" 
                                                   class="text-blue-600 hover:text-blue-900 transition duration-200 text-sm">
                                                    <i class="fas fa-download mr-1"></i>
                                                    Baixar
                                                </a>
                                            @else
                                                <span class="text-gray-400 text-sm">Sem comprovativo</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex space-x-2">
                                                <!-- Botão Aprovar -->
                                                <form action="{{ route('super-admin.payments.approve', $payment->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            onclick="return confirm('Tem certeza que deseja aprovar este pagamento?')"
                                                            class="bg-green-600 hover:bg-green-700 text-white text-xs font-medium py-2 px-3 rounded transition duration-200">
                                                        <i class="fas fa-check mr-1"></i>
                                                        Aprovar
                                                    </button>
                                                </form>
                                                
                                                <!-- Botão Rejeitar -->
                                                <button type="button" 
                                                        onclick="openRejectModal('{{ $payment->id }}')"
                                                        class="bg-red-600 hover:bg-red-700 text-white text-xs font-medium py-2 px-3 rounded transition duration-200">
                                                    <i class="fas fa-times mr-1"></i>
                                                    Rejeitar
                                                </button>
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
                        <h3 class="text-xl font-semibold text-gray-500 mb-2">Nenhum Pagamento Pendente</h3>
                        <p class="text-gray-400">Todos os pagamentos foram processados.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de Rejeição -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Rejeitar Pagamento</h3>
                <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Motivo da Rejeição
                    </label>
                    <textarea name="notes" id="notes" rows="4" 
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" 
                              placeholder="Explique o motivo da rejeição..." required></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()" 
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded transition duration-200">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded transition duration-200">
                        <i class="fas fa-times mr-1"></i>
                        Rejeitar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openRejectModal(paymentId) {
    document.getElementById('rejectForm').action = `/super-admin/payments/${paymentId}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('notes').value = '';
}

// Fechar modal ao clicar fora dele
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>
@endsection
