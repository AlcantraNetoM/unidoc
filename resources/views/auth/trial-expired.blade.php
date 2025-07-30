@extends('layouts.app')

@section('title', 'Período de Teste Expirado')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.346 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                
                <h2 class="text-2xl font-bold text-gray-900 mb-4">
                    Período de Teste Expirado
                </h2>
                
                <p class="text-gray-600 mb-6">
                    Seu período de teste de 15 dias expirou. Para continuar usando o sistema, realize o pagamento e envie o comprovativo.
                </p>

                <div class="space-y-4">
                    <a href="{{ route('payment.required') }}" 
                       class="w-full inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                        Realizar Pagamento
                    </a>
                    
                    <a href="{{ route('login') }}" 
                       class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                        Voltar ao Login
                    </a>
                </div>

                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <h3 class="text-sm font-medium text-blue-800 mb-2">Informações de Pagamento</h3>
                    <div class="text-xs text-blue-700 space-y-1">
                        <p><strong>Valor:</strong> 5.000 Kz (mensal)</p>
                        <p><strong>IBAN:</strong> AO06 0055. 0000. 9274. 3910. 1018. 0</p>
                        <p><strong>Banco:</strong> Atlântico</p>
                        <p><strong>Titular:</strong> Vambert Capita</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
