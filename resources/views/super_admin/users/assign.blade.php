@extends('layouts.app')

@section('content')
    <!-- Meta tag para passar dados do PHP para JavaScript -->
    <meta name="old-category-id" content="{{ old('category_id') }}">
    
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Atribuir Usuários a Empresas</h1>
                    <p class="text-gray-600 mt-2">Selecione usuários e atribua-os a empresas com suas respectivas funções</p>
                </div>
                <a href="{{ route('super_admin.users.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
                    ← Voltar aos Usuários
                </a>
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

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Estatísticas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.268 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <div>
                            <p class="text-yellow-800 text-sm font-medium">Usuários Pendentes</p>
                            <p class="text-yellow-900 text-2xl font-bold">{{ $usuariosDisponiveis->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <div>
                            <p class="text-blue-800 text-sm font-medium">Total de Empresas</p>
                            <p class="text-blue-900 text-2xl font-bold">{{ $empresas->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-green-800 text-sm font-medium">Atribuições Hoje</p>
                            <p class="text-green-900 text-2xl font-bold" id="assignmentCount">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulário de Atribuição -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                    <h3 class="text-lg font-medium text-blue-800">Nova Atribuição</h3>
                    <p class="text-sm text-blue-600">Selecione um usuário, empresa e função para criar uma nova atribuição</p>
                </div>

                <form method="POST" action="{{ route('super_admin.users.assign') }}" class="p-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Seleção de Usuário -->
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Usuário *
                            </label>
                            <select name="user_id" id="user_id" 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                    required>
                                <option value="">Selecione um usuário</option>
                                @foreach($usuariosDisponiveis as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Seleção de Empresa -->
                        <div>
                            <label for="empresa_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Empresa *
                            </label>
                            <select name="empresa_id" id="empresa_id" 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                    required onchange="loadCategories()">
                                <option value="">Selecione uma empresa</option>
                                @foreach($empresas as $empresa)
                                    <option value="{{ $empresa->id }}" {{ old('empresa_id') == $empresa->id ? 'selected' : '' }}>
                                        {{ $empresa->nome }}
                                    </option>
                                @endforeach
                            </select>
                            @error('empresa_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Seleção de Função -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                Função *
                            </label>
                            <select name="role" id="role" 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                    required onchange="toggleCategoryField()">
                                <option value="">Selecione uma função</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                                <option value="general_technician" {{ old('role') == 'general_technician' ? 'selected' : '' }}>Técnico Geral</option>
                                <option value="normal_technician" {{ old('role') == 'normal_technician' ? 'selected' : '' }}>Técnico Normal</option>
                            </select>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Seleção de Categoria (apenas para técnicos normais) -->
                        <div id="categoryField" class="{{ old('role') == 'normal_technician' ? '' : 'hidden' }}">
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Categoria *
                                <span class="text-xs text-gray-500">(Obrigatório para técnicos normais)</span>
                            </label>
                            <select name="category_id" id="category_id" 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Selecione uma categoria</option>
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                        <div class="text-sm text-gray-500">
                            * Campos obrigatórios
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" onclick="resetForm()" 
                                    class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                                Limpar
                            </button>
                            <button type="submit" 
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Atribuir Usuário
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Lista de Usuários Pendentes -->
            @if($usuariosDisponiveis->count() > 0)
                <div class="mt-8 bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Usuários Aguardando Atribuição</h3>
                        <p class="text-sm text-gray-600">{{ $usuariosDisponiveis->count() }} usuário(s) sem empresa atribuída</p>
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
                                @foreach($usuariosDisponiveis as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-yellow-700">{{ substr($user->name, 0, 1) }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button data-user-id="{{ $user->id }}" 
                                                data-user-name="{{ $user->name }}" 
                                                data-user-email="{{ $user->email }}"
                                                onclick="selectUser(this)"
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                            Selecionar
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="mt-8 bg-white rounded-lg shadow-md p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Todos os usuários estão atribuídos</h3>
                    <p class="mt-1 text-sm text-gray-500">Não há usuários aguardando atribuição no momento.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Selecionar usuário da tabela
        function selectUser(button) {
            const userId = button.getAttribute('data-user-id');
            const userName = button.getAttribute('data-user-name');
            const userEmail = button.getAttribute('data-user-email');
            
            document.getElementById('user_id').value = userId;
            // Scroll para o formulário
            document.querySelector('form').scrollIntoView({ behavior: 'smooth' });
            // Highlight do formulário
            document.querySelector('form').style.border = '2px solid #3B82F6';
            setTimeout(() => {
                document.querySelector('form').style.border = '';
            }, 2000);
        }

        // Carregar categorias baseado na empresa selecionada
        function loadCategories() {
            const empresaId = document.getElementById('empresa_id').value;
            const categorySelect = document.getElementById('category_id');
            
            categorySelect.innerHTML = '<option value="">Carregando...</option>';
            
            if (empresaId) {
                fetch(`/super-admin/categories/by-company/${empresaId}`)
                    .then(response => response.json())
                    .then(categories => {
                        categorySelect.innerHTML = '<option value="">Selecione uma categoria</option>';
                        categories.forEach(category => {
                            categorySelect.innerHTML += `<option value="${category.id}">${category.nome}</option>`;
                        });
                    })
                    .catch(error => {
                        console.error('Erro ao carregar categorias:', error);
                        categorySelect.innerHTML = '<option value="">Erro ao carregar categorias</option>';
                    });
            } else {
                categorySelect.innerHTML = '<option value="">Selecione uma categoria</option>';
            }
        }

        // Mostrar/esconder campo de categoria baseado na função
        function toggleCategoryField() {
            const role = document.getElementById('role').value;
            const categoryField = document.getElementById('categoryField');
            const categorySelect = document.getElementById('category_id');
            
            if (role === 'normal_technician') {
                categoryField.classList.remove('hidden');
                categorySelect.required = true;
            } else {
                categoryField.classList.add('hidden');
                categorySelect.required = false;
                categorySelect.value = '';
            }
        }

        // Resetar formulário
        function resetForm() {
            document.querySelector('form').reset();
            document.getElementById('categoryField').classList.add('hidden');
            document.getElementById('category_id').innerHTML = '<option value="">Selecione uma categoria</option>';
        }

        // Carregar categorias se empresa já estiver selecionada (old values)
        document.addEventListener('DOMContentLoaded', function() {
            const empresaId = document.getElementById('empresa_id').value;
            const oldCategoryId = document.querySelector('meta[name="old-category-id"]')?.getAttribute('content');
            
            if (empresaId) {
                loadCategories();
                
                // Se tiver categoria selecionada anteriormente, selecionar após carregar
                if (oldCategoryId) {
                    setTimeout(() => {
                        document.getElementById('category_id').value = oldCategoryId;
                    }, 500);
                }
            }

            // Contar atribuições feitas hoje
            updateAssignmentCount();
        });

        function updateAssignmentCount() {
            // Esta função seria implementada com uma chamada AJAX para contar atribuições do dia
            // Por simplicidade, manteremos como 0 por enquanto
            document.getElementById('assignmentCount').textContent = '0';
        }
    </script>
@endsection
