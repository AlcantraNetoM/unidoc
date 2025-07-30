<x-app-layout>
    <div class="fade-in">
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-2xl mx-auto">
                <div class="card">
                    <div class="card-body text-center p-8">
                        <div class="mb-6">
                            <div class="w-24 h-24 bg-red-100 rounded-full mx-auto flex items-center justify-center">
                                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                        </div>

                        <h2 class="text-2xl font-bold mb-4 text-red-600">Erro no Dashboard</h2>
                        
                        <p class="text-gray-600 mb-6">
                            Olá <strong>{{ $user->name }}</strong>, ocorreu um erro ao carregar seu dashboard.
                        </p>

                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                            <p class="text-red-800 text-sm">
                                <strong>Erro:</strong> {{ $error }}
                            </p>
                        </div>

                        <div class="bg-gray-100 rounded-lg p-4 mb-6">
                            <h3 class="font-semibold mb-2">Seus Dados:</h3>
                            <p class="text-sm text-gray-600">
                                <strong>Nome:</strong> {{ $user->name }}<br>
                                <strong>Email:</strong> {{ $user->email }}<br>
                                <strong>Role:</strong> {{ $user->role }}<br>
                                <strong>Empresa ID:</strong> {{ $user->empresa_id ?? 'Não atribuída' }}
                            </p>
                        </div>

                        <div class="flex gap-4 justify-center">
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                Tentar Novamente
                            </a>
                            
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="btn btn-secondary">
                                    Sair
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
