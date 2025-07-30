@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Aprovações Pendentes</h1>
        <p class="text-gray-600 mt-2">Gerir comprovativos de pagamento e aprovar contas</p>
    </div>

    <!-- Usuarios Pendentes -->
    @if($pendingUsers->count() > 0)
    <div class="mb-12">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-yellow-50 px-6 py-4 border-b border-yellow-200">
                <h2 class="text-xl font-semibold text-yellow-800 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Usuários Pendentes ({{ $pendingUsers->count() }})
                </h2>
            </div>

            <div class="divide-y divide-gray-200">
                @foreach($pendingUsers as $user)
                <div class="p-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- User Info -->
                        <div>
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-{{ $user->isPersonalAccount() ? 'green' : 'blue' }}-500 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($user->isPersonalAccount())
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        @endif
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $user->isPersonalAccount() ? 'green' : 'blue' }}-100 text-{{ $user->isPersonalAccount() ? 'green' : 'blue' }}-800">
                                        {{ $user->isPersonalAccount() ? 'Plano Pessoal' : 'Plano Empresarial' }}
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-2 text-sm text-gray-600">
                                <p><strong>Data de Registro:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                                @if($user->phone)
                                <p><strong>Telefone:</strong> {{ $user->phone }}</p>
                                @endif
                                @if($user->empresa)
                                <p><strong>Empresa:</strong> {{ $user->empresa->nome }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Payment Proof and Actions -->
                        <div>
                            @if($user->payment_proof_path)
                            <div class="mb-4">
                                <h4 class="font-medium text-gray-900 mb-2">Comprovativo de Pagamento</h4>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                                    <a href="{{ route('super_admin.payment.proof', ['type' => 'user', 'id' => $user->id]) }}" 
                                       target="_blank"
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Ver Comprovativo
                                    </a>
                                </div>
                            </div>
                            @endif

                            <!-- Aviso sobre período de teste automático -->
                            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-md">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm text-green-800">
                                        <strong>Período de teste automático:</strong> Ao aprovar, o usuário receberá automaticamente 15 dias gratuitos.
                                    </span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-2">
                                <form method="POST" action="{{ route('super_admin.users.approve', $user) }}" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                                        Aprovar
                                    </button>
                                </form>
                                
                                <form method="POST" action="{{ route('super_admin.users.reject', $user) }}" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors"
                                            onclick="return confirm('Tem certeza que deseja rejeitar este usuário?')">
                                        Rejeitar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Pagamentos Pendentes (após período de teste) -->
    @if($pendingPayments->count() > 0)
    <div class="mb-12">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-orange-50 px-6 py-4 border-b border-orange-200">
                <h2 class="text-xl font-semibold text-orange-800 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-3a2 2 0 00-2-2H9a2 2 0 00-2 2v3a2 2 0 002 2z"></path>
                    </svg>
                    Pagamentos Pendentes - Pós Período de Teste ({{ $pendingPayments->count() }})
                </h2>
            </div>

            <div class="divide-y divide-gray-200">
                @foreach($pendingPayments as $user)
                <div class="p-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- User Info -->
                        <div>
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($user->isPersonalAccount())
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        @endif
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        Pagamento após período de teste
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-2 text-sm text-gray-600">
                                <p><strong>Valor a pagar:</strong> {{ $user->isPersonalAccount() ? '5.000 Kz' : '15.000 Kz' }} (mensal)</p>
                                <p><strong>Período de teste expirou em:</strong> {{ $user->trial_end_date ? $user->trial_end_date->format('d/m/Y') : 'N/A' }}</p>
                                @if($user->empresa)
                                <p><strong>Empresa:</strong> {{ $user->empresa->nome }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Payment Proof and Actions -->
                        <div>
                            @if($user->payment_proof_path)
                            <div class="mb-4">
                                <h4 class="font-medium text-gray-900 mb-2">Comprovativo de Pagamento</h4>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                                    <a href="{{ route('super_admin.payment.proof', ['type' => 'user', 'id' => $user->id]) }}" 
                                       target="_blank"
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Ver Comprovativo
                                    </a>
                                </div>
                            </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="space-y-2">
                                <form method="POST" action="{{ route('super_admin.users.approve_payment', $user) }}">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                                        ✅ Aprovar Pagamento
                                    </button>
                                </form>
                                
                                <form method="POST" action="{{ route('super_admin.users.reject_payment', $user) }}" class="w-full">
                                    @csrf
                                    <input type="hidden" name="rejection_reason" value="Pagamento rejeitado pelo administrador">
                                    <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors"
                                            onclick="return confirm('Tem certeza que deseja reprovar o pagamento de {{ $user->name }}?')">
                                        ❌ Reprovar Pagamento
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Pagamentos Antecipados (durante período de teste) -->
    @if($anticipatedPayments->count() > 0)
    <div class="mb-12">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-green-50 px-6 py-4 border-b border-green-200">
                <h2 class="text-xl font-semibold text-green-800 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    Pagamentos Antecipados - Durante Período de Teste ({{ $anticipatedPayments->count() }})
                </h2>
                <p class="text-sm text-green-700 mt-1">Usuários que anteciparam o pagamento durante o período de teste de 15 dias</p>
            </div>

            <div class="divide-y divide-gray-200">
                @foreach($anticipatedPayments as $user)
                <div class="p-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- User Info -->
                        <div>
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($user->isPersonalAccount())
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        @endif
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Pagamento antecipado - {{ $user->getTrialDaysRemaining() }} dias restantes
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-2 text-sm text-gray-600">
                                <p><strong>Tipo de Conta:</strong> {{ $user->isPersonalAccount() ? 'Pessoal' : 'Empresarial' }}</p>
                                <p><strong>Valor:</strong> {{ $user->isPersonalAccount() ? '5.000 Kz' : '15.000 Kz' }}</p>
                                <p><strong>Período de Teste:</strong> Expira em {{ $user->trial_end_date ? $user->trial_end_date->format('d/m/Y H:i') : 'N/A' }}</p>
                                @if($user->empresa)
                                <p><strong>Empresa:</strong> {{ $user->empresa->nome }}</p>
                                @endif
                                <p><strong>Data do Upload:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col justify-center space-y-3">
                            @if($user->payment_proof_path)
                            <a href="{{ route('super_admin.payment.proof', ['type' => 'user', 'id' => $user->id]) }}" 
                               target="_blank"
                               class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-center">
                                📄 Ver Comprovativo
                            </a>
                            @endif
                            
                            <div class="grid grid-cols-2 gap-3">
                                <form method="POST" action="{{ route('super_admin.users.approve_payment', $user) }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors"
                                            onclick="return confirm('Tem certeza que deseja aprovar o pagamento antecipado de {{ $user->name }}?')">
                                        ✅ Aprovar Pagamento
                                    </button>
                                </form>
                                
                                <form method="POST" action="{{ route('super_admin.users.reject_payment', $user) }}" class="w-full">
                                    @csrf
                                    <input type="hidden" name="rejection_reason" value="Pagamento antecipado rejeitado pelo administrador">
                                    <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors"
                                            onclick="return confirm('Tem certeza que deseja reprovar o pagamento antecipado de {{ $user->name }}?')">
                                        ❌ Reprovar Pagamento
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Empresas Pendentes -->
    @if($pendingCompanies->count() > 0)
    <div class="mb-12">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-blue-50 px-6 py-4 border-b border-blue-200">
                <h2 class="text-xl font-semibold text-blue-800 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Empresas Pendentes ({{ $pendingCompanies->count() }})
                </h2>
            </div>

            <div class="divide-y divide-gray-200">
                @foreach($pendingCompanies as $empresa)
                <div class="p-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Company Info -->
                        <div>
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $empresa->nome }}</h3>
                                    <p class="text-sm text-gray-600">{{ $empresa->email }}</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Empresa
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-2 text-sm text-gray-600">
                                <p><strong>Data de Registro:</strong> {{ $empresa->registration_date ? $empresa->registration_date->format('d/m/Y H:i') : $empresa->created_at->format('d/m/Y H:i') }}</p>
                                @if($empresa->endereco)
                                <p><strong>Endereço:</strong> {{ $empresa->endereco }}</p>
                                @endif
                                @if($empresa->users->count() > 0)
                                <p><strong>Administrador:</strong> {{ $empresa->users->first()->name }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Payment Proof and Actions -->
                        <div>
                            @if($empresa->payment_proof_path)
                            <div class="mb-4">
                                <h4 class="font-medium text-gray-900 mb-2">Comprovativo de Pagamento</h4>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                                    <a href="{{ route('super_admin.payment.proof', ['type' => 'company', 'id' => $empresa->id]) }}" 
                                       target="_blank"
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Ver Comprovativo
                                    </a>
                                </div>
                            </div>
                            @endif

                            <!-- Aviso sobre período de teste automático -->
                            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-md">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm text-green-800">
                                        <strong>Período de teste automático:</strong> Ao aprovar, a empresa e seu administrador receberão automaticamente 15 dias gratuitos.
                                    </span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-2">
                                <form method="POST" action="{{ route('super_admin.companies.approve', $empresa) }}" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                                        Aprovar
                                    </button>
                                </form>
                                
                                <form method="POST" action="{{ route('super_admin.companies.reject', $empresa) }}" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors"
                                            onclick="return confirm('Tem certeza que deseja rejeitar esta empresa?')">
                                        Rejeitar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Novos Pagamentos Pendentes (Sistema Atual) -->
    @if($newPendingPayments->count() > 0)
    <div class="mb-12">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-blue-50 px-6 py-4 border-b border-blue-200">
                <h2 class="text-xl font-semibold text-blue-800 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    Pagamentos de Reativação/Renovação ({{ $newPendingPayments->count() }})
                </h2>
            </div>

            <div class="divide-y divide-gray-200">
                @foreach($newPendingPayments as $payment)
                @php
                    $isPersonalPayment = $payment->payment_type === 'user';
                    if ($isPersonalPayment) {
                        $entity = \App\Models\User::find($payment->entity_id);
                    } else {
                        $entity = \App\Models\Empresa::find($payment->entity_id);
                    }
                @endphp
                <div class="p-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Payment Info -->
                        <div>
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-{{ $isPersonalPayment ? 'green' : 'blue' }}-500 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($isPersonalPayment)
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        @endif
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $entity ? ($isPersonalPayment ? $entity->name : $entity->nome) : 'Entidade não encontrada' }}
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        {{ $entity ? $entity->email : 'Email não disponível' }}
                                    </p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $isPersonalPayment ? 'green' : 'blue' }}-100 text-{{ $isPersonalPayment ? 'green' : 'blue' }}-800">
                                        {{ $isPersonalPayment ? 'Conta Pessoal' : 'Conta Empresarial' }}
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-2 text-sm text-gray-600">
                                <p><strong>Data do Pagamento:</strong> {{ $payment->payment_date->format('d/m/Y H:i') }}</p>
                                <p><strong>Meses Pagos:</strong> {{ $payment->months_paid }} mês{{ $payment->months_paid > 1 ? 'es' : '' }}</p>
                                <p><strong>Valor:</strong> {{ number_format($payment->amount, 0, ',', '.') }} Kz</p>
                                @if($payment->notes)
                                    <p><strong>Observações:</strong> {{ $payment->notes }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Payment Actions -->
                        <div class="flex flex-col justify-between">
                            <div class="mb-4">
                                @if($payment->payment_proof_path)
                                    <a href="{{ route('super_admin.view_payment_proof', ['type' => 'payment', 'id' => $payment->id]) }}" 
                                       target="_blank"
                                       class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm text-gray-700 bg-white hover:bg-gray-50">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Ver Comprovativo
                                    </a>
                                @endif
                            </div>

                            <div class="flex gap-2">
                                <form action="{{ route('super_admin.approve_payment', ['type' => 'payment', 'id' => $payment->id]) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Aprovar
                                    </button>
                                </form>

                                <form action="{{ route('super_admin.reject_payment', ['type' => 'payment', 'id' => $payment->id]) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                                            onclick="return confirm('Tem certeza que deseja rejeitar este pagamento?')">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Rejeitar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @if($pendingUsers->count() === 0 && $pendingCompanies->count() === 0 && $pendingPayments->count() === 0 && $anticipatedPayments->count() === 0 && $newPendingPayments->count() === 0)
    <div class="bg-white rounded-xl shadow-lg p-12 text-center">
        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Tudo em Dia!</h3>
        <p class="text-gray-600">Não há aprovações pendentes no momento.</p>
    </div>
    @endif
</div>

@endsection
