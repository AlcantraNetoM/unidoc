@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
                <h1 class="text-2xl font-bold text-white">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Conta Bloqueada
                </h1>
            </div>

            <div class="p-6">
                <!-- Mensagens de Sucesso e Erro -->
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                            <p class="text-green-800 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-500 text-xl mr-3"></i>
                            <p class="text-red-800 font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Mensagem de Bloqueio -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                    <div class="text-center">
                        <i class="fas fa-lock text-red-500 text-4xl mb-4"></i>
                        <h2 class="text-xl font-semibold text-red-800 mb-2">Acesso Bloqueado</h2>
                        <p class="text-red-700">
                            {{ session('blocked_message', 'Sua conta foi bloqueada. Entre em contato com o suporte.') }}
                        </p>
                    </div>
                </div>

                <!-- Opções de Reativação -->
                @if($entityInfo ?? null)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-blue-800 mb-4">
                            <i class="fas fa-credit-card mr-2"></i>
                            Reativar Conta {{ $entityInfo['account_type_label'] }}
                        </h3>
                        <p class="text-blue-700 mb-4">
                            Para reativar sua conta, faça um novo pagamento seguindo os passos abaixo:
                        </p>
                        
                        <div class="space-y-3 text-sm">
                            <div class="flex items-start">
                                <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5">1</span>
                                <p>Faça uma transferência bancária para os dados abaixo</p>
                            </div>
                            <div class="flex items-start">
                                <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5">2</span>
                                <p>Tire uma foto ou screenshot do comprovativo</p>
                            </div>
                            <div class="flex items-start">
                                <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5">3</span>
                                <p>Envie o comprovativo através do formulário abaixo</p>
                            </div>
                            <div class="flex items-start">
                                <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5">4</span>
                                <p>Aguarde a aprovação do administrador</p>
                            </div>
                        </div>
                    </div>

                    <!-- Dados Bancários -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-green-800 mb-4">
                            <i class="fas fa-university mr-2"></i>
                            Dados para Transferência
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600 font-medium">Tipo de Conta:</p>
                                <p class="text-lg font-bold text-green-700">{{ $entityInfo['account_type_label'] }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 font-medium">Valor Mensal:</p>
                                <p class="text-2xl font-bold text-green-700">{{ number_format($entityInfo['monthly_price'], 0, ',', '.') }} Kz</p>
                            </div>
                            <div>
                                <p class="text-gray-600 font-medium">IBAN:</p>
                                <p class="font-mono font-medium">AO06 0055. 0000. 9274. 3910. 1018. 0</p>
                            </div>
                            <div>
                                <p class="text-gray-600 font-medium">Banco:</p>
                                <p class="font-medium">Atlântico</p>
                            </div>
                            <div>
                                <p class="text-gray-600 font-medium">Titular:</p>
                                <p class="font-medium">Vambert Capita</p>
                            </div>
                        </div>
                    </div>

                    <!-- Formulário de Reativação -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-upload mr-2"></i>
                            Enviar Comprovativo
                        </h3>
                        
                        <form action="{{ route('payment.reactivate') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="entity_type" value="{{ $entityInfo['type'] }}">
                            <input type="hidden" name="entity_id" value="{{ $entityInfo['id'] }}">
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="months" class="block text-sm font-medium text-gray-700 mb-2">
                                        Número de Meses a Pagar
                                    </label>
                                    <select name="months" id="months" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                        <option value="">Selecione...</option>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'mês' : 'meses' }} - {{ number_format($i * $entityInfo['monthly_price'], 0, ',', '.') }} Kz</option>
                                        @endfor
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-2">
                                        Comprovativo de Pagamento
                                    </label>
                                    <input type="file" name="payment_proof" id="payment_proof" 
                                           accept="image/*,.pdf" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                           required>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Formatos aceitos: JPG, PNG, PDF (máx. 5MB)
                                    </p>
                                </div>
                                
                                <button type="submit" 
                                        class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-md transition duration-200">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Enviar Comprovativo para Reativação
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- Informações de Contato -->
                <div class="bg-gray-50 rounded-lg p-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        <i class="fas fa-headset mr-2"></i>
                        Precisa de Ajuda?
                    </h3>
                    <p class="text-gray-600 mb-3">
                        Se tiver dúvidas ou problemas, entre em contato conosco:
                    </p>
                    <div class="space-y-2 text-sm">
                        <p>
                            <i class="fas fa-envelope text-blue-500 mr-2"></i>
                            <span class="font-medium">Email:</span> vambert_quaresma@gmail.com
                        </p>
                        <p>
                            <i class="fas fa-phone text-green-500 mr-2"></i>
                            <span class="font-medium">Telefone:</span> +244 939 424 288
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
