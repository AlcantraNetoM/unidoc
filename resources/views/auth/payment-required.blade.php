@extends('layouts.app')

@section('title', 'UNIDOC - Pagamento Necessário')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg w-full space-y-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-6">
                <!-- Logo UNIDOC -->
                <div class="mx-auto flex items-center justify-center mb-4">
                    <img src="{{ asset('logo.png') }}" alt="UNIDOC Logo" class="w-16 h-16 object-contain">
                </div>
                
                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                    UNIDOC - Pagamento Necessário
                </h2>
                
                <p class="text-gray-600">
                    Olá, {{ $user->name }}! Seu período de teste expirou. Envie o comprovativo de pagamento para reativar sua conta.
                </p>
            </div>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Erro no formulário</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                <h3 class="text-sm font-medium text-blue-800 mb-2">Informações de Pagamento UNIDOC</h3>
                <div class="text-xs text-blue-700 space-y-1">
                    <p><strong>Valor:</strong> {{ $user->empresa_id ? '15.000 Kz' : '5.000 Kz' }} (mensal)</p>
                    <p><strong>IBAN:</strong> AO06 0055. 0000. 9274. 3910. 1018. 0</p>
                    <p><strong>Banco:</strong> Atlântico</p>
                    <p><strong>Titular:</strong> Vambert Capita</p>
                </div>
            </div>

            <form method="POST" action="{{ route('payment.upload') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <div>
                    <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-2">
                        Comprovativo de Pagamento <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                        <input type="file" 
                               id="payment_proof" 
                               name="payment_proof" 
                               accept=".jpg,.jpeg,.png,.pdf"
                               required
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-300 rounded-md">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        Formatos aceitos: JPG, PNG, PDF (máx. 5MB)
                    </p>
                </div>

                <div class="space-y-4">
                    <button type="submit" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                        Enviar Comprovativo
                    </button>
                    
                    <a href="{{ route('login') }}" 
                       class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                        Cancelar
                    </a>
                </div>
            </form>

            <div class="mt-6 text-center">
                <p class="text-xs text-gray-500">
                    Após o envio, aguarde a validação do administrador. Você receberá um email quando sua conta for reativada.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
