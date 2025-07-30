@extends('layouts.app')

@section('content')
    <!-- Debug: Verificar se componente está carregando -->
    <script>console.log('View awaiting-assignment carregada');</script>
    
    <div class="fade-in">
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-2xl mx-auto">
                <div class="card">
                    <div class="card-body text-center p-8">
                        <div class="mb-6">
                            <div class="w-24 h-24 bg-yellow-100 rounded-full mx-auto flex items-center justify-center">
                                <svg class="w-12 h-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <h2 class="text-2xl font-bold mb-4">Aguardando Atribuição</h2>
                        
                        <p class="text-gray-600 mb-6">
                            Olá <strong>{{ $user->name }}</strong>, seu cadastro foi realizado com sucesso!
                        </p>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <p class="text-yellow-800">
                                Você está aguardando ser atribuído a uma empresa pelo administrador do sistema.
                                Em breve você receberá acesso às funcionalidades do sistema.
                            </p>
                        </div>

                        <div class="bg-gray-100 rounded-lg p-4">
                            <h3 class="font-semibold mb-2">Seus Dados:</h3>
                            <p class="text-sm text-gray-600">
                                <strong>Nome:</strong> {{ $user->name }}<br>
                                <strong>Email:</strong> {{ $user->email }}<br>
                                <strong>Status:</strong> Aguardando atribuição
                            </p>
                        </div>

                        <div class="mt-8">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-secondary">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Sair
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 