@extends('layouts.app')

@section('title', 'Pagamento Pendente')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                
                <h2 class="text-2xl font-bold text-gray-900 mb-4">
                    Pagamento em Análise
                </h2>
                
                <p class="text-gray-600 mb-6">
                    Olá, {{ $user->name }}! Seu comprovativo de pagamento foi enviado com sucesso e está sendo analisado pelo administrador.
                </p>

                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm text-blue-800">
                            <strong>Status:</strong> Aguardando validação
                        </p>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-gray-600">
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Comprovativo de pagamento enviado</span>
                    </div>
                    
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-yellow-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Análise pelo administrador em andamento</span>
                    </div>
                    
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-gray-400 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>Notificação por email após validação</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('login') }}" 
                       class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                        Voltar ao Login
                    </a>
                </div>

                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-500">
                        O tempo de análise é normalmente de 24 a 48 horas úteis. 
                        Em caso de dúvidas, entre em contacto conosco.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
