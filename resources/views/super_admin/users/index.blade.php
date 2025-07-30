@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Gerenciar Usuários</h1>
                <p class="text-gray-600 mt-2">Visualizar usuários e suas atribuições</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('super_admin.users.assign.page') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Atribuir Usuários
                </a>
                <a href="{{ route('super_admin.dashboard') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700">
                    ← Voltar ao Dashboard
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <!-- Estatísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-red-800 text-sm font-medium">Aguardando Aprovação</p>
                        <p class="text-red-900 text-2xl font-bold">{{ $usuariosPendentes ? $usuariosPendentes->count() : 0 }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.268 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <div>
                        <p class="text-yellow-800 text-sm font-medium">Aguardando Atribuição</p>
                        <p class="text-yellow-900 text-2xl font-bold">{{ $usuariosSemEmpresa ? $usuariosSemEmpresa->count() : 0 }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-green-800 text-sm font-medium">Usuários Ativos</p>
                        <p class="text-green-900 text-2xl font-bold">{{ $users->where('empresa_id', '!=', null)->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <div>
                        <p class="text-blue-800 text-sm font-medium">Total de Usuários</p>
                        <p class="text-blue-900 text-2xl font-bold">{{ $users->total() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs para alternar entre pendentes e ativos -->
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button onclick="showTab('approval')" id="approval-tab" 
                            class="tab-button py-2 px-1 border-b-2 font-medium text-sm border-red-500 text-red-600">
                        Aguardando Aprovação
                        @if($usuariosPendentes && $usuariosPendentes->count() > 0)
                            <span class="ml-2 bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">
                                {{ $usuariosPendentes->count() }}
                            </span>
                        @endif
                    </button>
                    <button onclick="showTab('pending')" id="pending-tab" 
                            class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Aguardando Atribuição
                        @if($usuariosSemEmpresa && $usuariosSemEmpresa->count() > 0)
                            <span class="ml-2 bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">
                                {{ $usuariosSemEmpresa->count() }}
                            </span>
                        @endif
                    </button>
                    <button onclick="showTab('active')" id="active-tab" 
                            class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Todos os Usuários
                    </button>
                </nav>
            </div>
        </div>

                <!-- Usuários Aguardando Aprovação -->
        <div id="approval-content" class="tab-content">
            @if($usuariosPendentes && $usuariosPendentes->count() > 0)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-red-50">
                        <h3 class="text-lg font-medium text-red-800">Usuários Aguardando Aprovação</h3>
                        <p class="text-sm text-red-600">Estes usuários precisam ser aprovados para acessar o sistema</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo de Conta</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data de Registro</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($usuariosPendentes as $usuario)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-red-700">{{ substr($usuario->name, 0, 1) }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $usuario->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $usuario->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium {{ $usuario->account_type === 'personal' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }} rounded-full">
                                            {{ $usuario->account_type === 'personal' ? 'Pessoal' : 'Empresa' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($usuario->account_type === 'company' && $usuario->empresa)
                                            <div class="text-sm text-gray-900">{{ $usuario->empresa->nome }}</div>
                                        @elseif($usuario->account_type === 'personal')
                                            <span class="text-sm text-gray-500">N/A</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Não selecionada</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $usuario->created_at->format('d/m/Y H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <form action="{{ route('super_admin.users.approve', $usuario->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        onclick="return confirm('Tem certeza que deseja aprovar este usuário?')"
                                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs font-medium transition-colors">
                                                    Aprovar
                                                </button>
                                            </form>
                                            <form action="{{ route('super_admin.users.reject', $usuario->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        onclick="return confirm('Tem certeza que deseja rejeitar este usuário? Esta ação não pode ser desfeita.')"
                                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs font-medium transition-colors">
                                                    Rejeitar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Não há usuários aguardando aprovação</h3>
                    <p class="mt-1 text-sm text-gray-500">Todos os usuários foram aprovados ou não há novos registros.</p>
                </div>
            @endif
        </div>

        <!-- Usuários Pendentes -->
        <div id="pending-content" class="tab-content hidden">
            @if($usuariosSemEmpresa && $usuariosSemEmpresa->count() > 0)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-yellow-50">
                        <h3 class="text-lg font-medium text-yellow-800">Usuários Aguardando Atribuição</h3>
                        <p class="text-sm text-yellow-600">Estes usuários precisam ser atribuídos a uma empresa</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data de Registro</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($usuariosSemEmpresa as $usuario)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-yellow-700">{{ substr($usuario->name, 0, 1) }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $usuario->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $usuario->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <span class="text-sm text-gray-500">Aguardando atribuição</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $usuario->created_at->format('d/m/Y H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button data-user-id="{{ $usuario->id }}" data-user-name="{{ $usuario->name }}" 
                                                onclick="openAssignModal(this.dataset.userId, this.dataset.userName)"
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                            Atribuir Empresa
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Todos os usuários estão atribuídos</h3>
                    <p class="mt-1 text-sm text-gray-500">Não há usuários aguardando atribuição no momento.</p>
                </div>
            @endif
        </div>

        <!-- Todos os Usuários -->
        <div id="active-content" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Todos os Usuários</h3>
                </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Função</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->empresa)
                                    <div class="text-sm text-gray-900">{{ $user->empresa->nome }}</div>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Não atribuído</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if($user->role === 'admin') bg-purple-100 text-purple-800
                                    @elseif($user->role === 'general_technician') bg-blue-100 text-blue-800
                                    @elseif($user->role === 'normal_technician') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->empresa_id)
                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Ativo</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Aguardando</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="editUser('{{ $user->id }}')" 
                                        class="text-blue-600 hover:text-blue-900 mr-2">
                                    Editar
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Nenhum usuário encontrado.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Paginação -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $users->links() }}
            </div>
        </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active classes from all tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-red-500', 'text-red-600', 'border-yellow-500', 'text-yellow-600', 'border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected tab content
            if (tabName === 'approval') {
                document.getElementById('approval-content').classList.remove('hidden');
                document.getElementById('approval-tab').classList.remove('border-transparent', 'text-gray-500');
                document.getElementById('approval-tab').classList.add('border-red-500', 'text-red-600');
            } else if (tabName === 'pending') {
                document.getElementById('pending-content').classList.remove('hidden');
                document.getElementById('pending-tab').classList.remove('border-transparent', 'text-gray-500');
                document.getElementById('pending-tab').classList.add('border-yellow-500', 'text-yellow-600');
            } else if (tabName === 'active') {
                document.getElementById('active-content').classList.remove('hidden');
                document.getElementById('active-tab').classList.remove('border-transparent', 'text-gray-500');
                document.getElementById('active-tab').classList.add('border-blue-500', 'text-blue-600');
            }
        }

        function editUser(userId) {
            // Redirecionar para a página de edição do usuário
            window.location.href = `/super-admin/users/${userId}/edit`;
        }

        function openAssignModal(userId, userName) {
            // Redirecionar para a página de atribuição de empresa
            window.location.href = '/super-admin/users/assign';
        }

        // Mostrar a primeira aba por padrão
        document.addEventListener('DOMContentLoaded', function() {
            showTab('approval');
        });
    </script>
@endsection 