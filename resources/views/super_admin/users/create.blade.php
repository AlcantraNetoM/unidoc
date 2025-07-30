@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Novo Usuário</h1>
                <p class="text-gray-600 mt-2">Criar um novo usuário no sistema</p>
            </div>
            <a href="{{ route('super_admin.users.index') }}" 
               class="inline-block bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition-colors">
                ← Voltar aos Usuários
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <h4 class="font-medium mb-2">Erro de validação:</h4>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Estatísticas Rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Empresas Disponíveis</h3>
                        <p class="text-2xl font-bold text-blue-600">{{ $empresas->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Total de Usuários</h3>
                        <p class="text-2xl font-bold text-green-600">{{ \App\Models\User::count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.268 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Usuários Pendentes</h3>
                        <p class="text-2xl font-bold text-yellow-600">{{ \App\Models\User::whereNull('empresa_id')->where('role', '!=', 'super_admin')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulário de Criação -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                <h3 class="text-lg font-medium text-blue-800">Informações do Usuário</h3>
                <p class="text-sm text-blue-600">Preencha os dados para criar um novo usuário</p>
            </div>

            <form method="POST" action="{{ route('super_admin.users.store') }}" class="p-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nome -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome Completo *
                        </label>
                        <input type="text" name="name" id="name" 
                               value="{{ old('name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                               placeholder="Digite o nome completo" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email *
                        </label>
                        <input type="email" name="email" id="email" 
                               value="{{ old('email') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                               placeholder="Digite o email" required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Senha -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Senha *
                        </label>
                        <input type="password" name="password" id="password" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                               placeholder="Digite a senha (mín. 8 caracteres)" 
                               minlength="8" 
                               required>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div id="password-feedback" class="mt-1 text-sm"></div>
                        <div id="password-strength" class="mt-2" style="display: none;">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div id="strength-bar" class="h-2 rounded-full transition-all duration-300" style="width: 0%;"></div>
                            </div>
                            <div id="strength-text" class="text-xs mt-1"></div>
                        </div>
                    </div>

                    <!-- Confirmação da Senha -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmar Senha *
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Confirme a senha" 
                               minlength="8" 
                               required>
                        <div id="password-match-feedback" class="mt-1 text-sm"></div>
                    </div>

                    <!-- Empresa -->
                    <div>
                        <label for="empresa_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Empresa
                        </label>
                        <select name="empresa_id" id="empresa_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('empresa_id') border-red-500 @enderror"
                                onchange="loadCategories()">
                            <option value="">Selecione uma empresa (opcional)</option>
                            @foreach($empresas as $empresa)
                                <option value="{{ $empresa->id }}" {{ old('empresa_id') == $empresa->id ? 'selected' : '' }}>
                                    {{ $empresa->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('empresa_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Deixe em branco para criar usuário sem empresa</p>
                    </div>

                    <!-- Função -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                            Função *
                        </label>
                        <select name="role" id="role" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('role') border-red-500 @enderror"
                                onchange="toggleCategoryField()" required>
                            <option value="">Selecione uma função</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="general_technician" {{ old('role') == 'general_technician' ? 'selected' : '' }}>Técnico Geral</option>
                            <option value="normal_technician" {{ old('role') == 'normal_technician' ? 'selected' : '' }}>Técnico Normal</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Categoria (só para técnicos normais) -->
                <div id="category-field" class="mt-6 hidden">
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Categoria *
                    </label>
                    <select name="category_id" id="category_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category_id') border-red-500 @enderror">
                        <option value="">Primeiro selecione uma empresa e função</option>
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Obrigatório apenas para técnicos normais</p>
                </div>

                <!-- Botões de Ação -->
                <div class="mt-8 flex justify-between items-center pt-6 border-t border-gray-200">
                    <div class="text-sm text-gray-600">
                        * Campos obrigatórios
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('super_admin.users.index') }}" 
                           class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Criar Usuário
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Dicas -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h4 class="text-lg font-medium text-blue-800 mb-3">💡 Dicas Importantes</h4>
            <ul class="space-y-2 text-sm text-blue-700">
                <li class="flex items-start">
                    <svg class="w-4 h-4 mt-0.5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                    </svg>
                    <span><strong>Administradores:</strong> Têm acesso total às funções da empresa</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 mt-0.5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                    </svg>
                    <span><strong>Técnicos Gerais:</strong> Podem acessar todas as categorias da empresa</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 mt-0.5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                    </svg>
                    <span><strong>Técnicos Normais:</strong> Acessam apenas uma categoria específica</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 mt-0.5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                    </svg>
                    <span>Usuários sem empresa podem ser atribuídos posteriormente</span>
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
/* Password strength indicators */
#password-strength {
    margin-top: 8px;
}

#password-feedback,
#password-match-feedback {
    font-size: 12px;
    font-weight: 500;
    margin-top: 4px;
}

.w-full {
    width: 100%;
}

.bg-gray-200 {
    background-color: #e5e7eb;
}

.rounded-full {
    border-radius: 9999px;
}

.h-2 {
    height: 8px;
}

.transition-all {
    transition: all 0.3s ease;
}

.duration-300 {
    transition-duration: 300ms;
}

.text-xs {
    font-size: 11px;
}

.mt-1 {
    margin-top: 4px;
}

.mt-2 {
    margin-top: 8px;
}
</style>

<script>
    // Password validation functions
    function checkPasswordStrength(password) {
        let strength = 0;
        let feedback = [];
        
        if (password.length >= 8) {
            strength += 25;
        } else {
            feedback.push('Mínimo 8 caracteres');
        }
        
        if (/[a-z]/.test(password)) {
            strength += 25;
        } else {
            feedback.push('letras minúsculas');
        }
        
        if (/[A-Z]/.test(password)) {
            strength += 25;
        } else {
            feedback.push('letras maiúsculas');
        }
        
        if (/[0-9]/.test(password)) {
            strength += 25;
        } else {
            feedback.push('números');
        }
        
        return { strength, feedback };
    }

    function updatePasswordStrength(password) {
        const result = checkPasswordStrength(password);
        const strengthBar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');
        const strengthContainer = document.getElementById('password-strength');
        
        if (password.length > 0) {
            strengthContainer.style.display = 'block';
            strengthBar.style.width = result.strength + '%';
            
            if (result.strength < 50) {
                strengthBar.style.backgroundColor = '#ef4444'; // red
                strengthText.textContent = 'Fraca - Precisa: ' + result.feedback.join(', ');
                strengthText.style.color = '#ef4444';
            } else if (result.strength < 75) {
                strengthBar.style.backgroundColor = '#f59e0b'; // yellow
                strengthText.textContent = 'Média - Precisa: ' + result.feedback.join(', ');
                strengthText.style.color = '#f59e0b';
            } else {
                strengthBar.style.backgroundColor = '#10b981'; // green
                strengthText.textContent = 'Forte - Senha segura!';
                strengthText.style.color = '#10b981';
            }
        } else {
            strengthContainer.style.display = 'none';
        }
        
        return result.strength >= 50;
    }

    function validatePasswordMatch(password, confirmation) {
        const feedback = document.getElementById('password-match-feedback');
        
        if (confirmation.length > 0) {
            if (password === confirmation) {
                feedback.textContent = '✓ Senhas coincidem';
                feedback.style.color = '#10b981';
                return true;
            } else {
                feedback.textContent = '✗ Senhas não coincidem';
                feedback.style.color = '#ef4444';
                return false;
            }
        } else {
            feedback.textContent = '';
            return password.length === 0;
        }
    }

    function validatePasswordRequirements(password) {
        const feedback = document.getElementById('password-feedback');
        
        if (password.length === 0) {
            feedback.textContent = '';
            return false;
        }
        
        if (password.length < 8) {
            feedback.textContent = '✗ A senha deve ter pelo menos 8 caracteres';
            feedback.style.color = '#ef4444';
            return false;
        }
        
        feedback.textContent = '✓ Senha atende aos requisitos mínimos';
        feedback.style.color = '#10b981';
        return true;
    }

    function toggleCategoryField() {
        const role = document.getElementById('role').value;
        const categoryField = document.getElementById('category-field');
        const categorySelect = document.getElementById('category_id');
        
        if (role === 'normal_technician') {
            categoryField.classList.remove('hidden');
            categorySelect.required = true;
            loadCategories();
        } else {
            categoryField.classList.add('hidden');
            categorySelect.required = false;
            categorySelect.innerHTML = '<option value="">Primeiro selecione uma empresa e função</option>';
        }
    }

    function loadCategories() {
        const empresaId = document.getElementById('empresa_id').value;
        const role = document.getElementById('role').value;
        const categorySelect = document.getElementById('category_id');
        
        if (!empresaId || role !== 'normal_technician') {
            categorySelect.innerHTML = '<option value="">Primeiro selecione uma empresa e função</option>';
            return;
        }
        
        categorySelect.innerHTML = '<option value="">Carregando categorias...</option>';
        
        fetch(`/super-admin/categories/by-company/${empresaId}`)
            .then(response => response.json())
            .then(categories => {
                categorySelect.innerHTML = '<option value="">Selecione uma categoria</option>';
                categories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.nome;
                    categorySelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Erro ao carregar categorias:', error);
                categorySelect.innerHTML = '<option value="">Erro ao carregar categorias</option>';
            });
    }

    // Executar ao carregar a página
    document.addEventListener('DOMContentLoaded', function() {
        toggleCategoryField();
        
        // Password validation
        const passwordInput = document.getElementById('password');
        const passwordConfirmationInput = document.getElementById('password_confirmation');
        const form = document.querySelector('form');
        
        passwordInput.addEventListener('input', function() {
            updatePasswordStrength(this.value);
            validatePasswordRequirements(this.value);
            if (passwordConfirmationInput.value) {
                validatePasswordMatch(this.value, passwordConfirmationInput.value);
            }
        });
        
        passwordConfirmationInput.addEventListener('input', function() {
            validatePasswordMatch(passwordInput.value, this.value);
        });
        
        // Form validation before submit
        form.addEventListener('submit', function(e) {
            const password = passwordInput.value;
            const passwordConfirmation = passwordConfirmationInput.value;
            
            if (password.length < 8) {
                e.preventDefault();
                alert('A senha deve ter pelo menos 8 caracteres.');
                passwordInput.focus();
                return false;
            }
            
            if (password !== passwordConfirmation) {
                e.preventDefault();
                alert('As senhas não coincidem.');
                passwordConfirmationInput.focus();
                return false;
            }
            
            const result = checkPasswordStrength(password);
            if (result.strength < 50) {
                e.preventDefault();
                alert('A senha é muito fraca. Por favor, use uma senha mais forte que inclua: ' + result.feedback.join(', '));
                passwordInput.focus();
                return false;
            }
        });
    });
</script>
@endsection
