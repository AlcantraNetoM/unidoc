@extends('layouts.register')

@section('title', 'Registro de Empresa')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <!-- Logo UNIDOC -->
                    <div class="w-14 h-14 mr-4 flex items-center justify-center">
                        <img src="{{ asset('logo.png') }}" alt="UNIDOC Logo" class="w-14 h-14 object-contain">
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-blue-600">UNIDOC</h1>
                        <p class="text-sm text-gray-600 -mt-1">Único local para documentos</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('landing') }}" class="text-gray-600 hover:text-gray-900 px-4 py-2 rounded-lg transition duration-200">Voltar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Form -->
    <div class="flex items-center justify-center py-20">
        <div class="max-w-md w-full mx-4">
            <div class="bg-white rounded-xl shadow-lg p-8 border">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">UNIDOC Empresarial</h1>
                    <p class="text-gray-600">Crie sua conta para transformar a gestão documental da sua empresa</p>
                </div>

                <form method="POST" action="{{ route('register.store.empresa') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Nome da Empresa -->
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome da Empresa *
                        </label>
                        <input id="company_name" name="company_name" type="text" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition duration-200 @error('company_name') border-red-300 @enderror" 
                               placeholder="Nome da sua empresa"
                               value="{{ old('company_name') }}">
                        @error('company_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email *
                        </label>
                        <input id="email" name="email" type="email" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition duration-200 @error('email') border-red-300 @enderror" 
                               placeholder="email@empresa.com"
                               value="{{ old('email') }}">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nome do Administrador -->
                    <div>
                        <label for="admin_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome do Administrador *
                        </label>
                        <input id="admin_name" name="admin_name" type="text" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition duration-200 @error('admin_name') border-red-300 @enderror" 
                               placeholder="Nome completo do administrador"
                               value="{{ old('admin_name') }}">
                        @error('admin_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Endereço da Empresa -->
                    <div>
                        <label for="endereco" class="block text-sm font-medium text-gray-700 mb-2">
                            Endereço da Empresa *
                        </label>
                        <textarea id="endereco" name="endereco" required rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition duration-200 @error('endereco') border-red-300 @enderror" 
                                  placeholder="Endereço completo da empresa">{{ old('endereco') }}</textarea>
                        @error('endereco')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Senha -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Palavra-passe *
                        </label>
                        <div class="relative">
                            <input id="password" name="password" type="password" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition duration-200 @error('password') border-red-300 @enderror" 
                                   placeholder="Crie uma senha forte"
                                   minlength="8">
                            <button type="button" id="togglePassword" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <svg id="eyeIcon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Força da Senha -->
                        <div id="passwordStrength" class="mt-3" style="display: none;">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Força da senha:</span>
                                <span id="strengthText" class="font-medium"></span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div id="strengthBar" class="h-2 rounded-full transition-all duration-300" style="width: 0%;"></div>
                            </div>
                        </div>
                        
                        <!-- Requisitos da Senha -->
                        <div id="passwordRequirements" class="mt-3 space-y-1" style="display: none;">
                            <div class="text-sm text-gray-600 font-medium">Requisitos da senha:</div>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div id="req-length" class="flex items-center text-red-500">
                                    <span class="mr-1">✗</span> Mínimo 8 caracteres
                                </div>
                                <div id="req-lowercase" class="flex items-center text-red-500">
                                    <span class="mr-1">✗</span> Letra minúscula
                                </div>
                                <div id="req-uppercase" class="flex items-center text-red-500">
                                    <span class="mr-1">✗</span> Letra maiúscula
                                </div>
                                <div id="req-number" class="flex items-center text-red-500">
                                    <span class="mr-1">✗</span> Número
                                </div>
                                <div id="req-special" class="flex items-center text-red-500">
                                    <span class="mr-1">✗</span> Caractere especial
                                </div>
                                <div id="req-no-common" class="flex items-center text-red-500">
                                    <span class="mr-1">✗</span> Não comum
                                </div>
                            </div>
                        </div>
                        
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmação da Senha -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmar Palavra-passe *
                        </label>
                        <div class="relative">
                            <input id="password_confirmation" name="password_confirmation" type="password" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent transition duration-200" 
                                   placeholder="Confirme sua palavra-passe"
                                   minlength="8">
                            <button type="button" id="togglePasswordConfirm" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <svg id="eyeIconConfirm" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        <div id="passwordMatch" class="mt-2 text-sm" style="display: none;"></div>
                    </div>

                    <!-- Termos e Condições -->
                    <div class="flex items-start">
                        <input id="terms" name="terms" type="checkbox" required 
                               class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded @error('terms') border-red-300 @enderror">
                        <label for="terms" class="ml-3 block text-sm text-gray-700">
                            Aceito os <a href="#" id="openTermsModal" class="text-blue-600 hover:text-blue-800 underline">termos e condições</a> *
                        </label>
                    </div>
                    @error('terms')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Aviso sobre período de teste -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-green-900 mb-2">🎉 Período de Teste Gratuito!</h4>
                        <div class="space-y-2 text-sm text-green-800">
                            <p>✅ <strong>15 dias gratuitos</strong> para testar todas as funcionalidades</p>
                            <p>✅ Acesso completo ao sistema após aprovação</p>
                            <p>✅ Pagamento apenas no final do período de teste</p>
                            <p class="text-xs text-green-700 mt-3">
                                <em>Após aprovação da sua conta, você terá 15 dias para usar o sistema gratuitamente.</em>
                            </p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Criar Conta Empresarial
                    </button>

                    <!-- Links -->
                    <div class="text-center space-y-2 pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600">
                            Já tem uma conta? 
                            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 underline font-medium">
                                Fazer login
                            </a>
                        </p>
                        <p class="text-sm text-gray-600">
                            <a href="{{ route('landing') }}" class="text-blue-600 hover:text-blue-800 underline font-medium">
                                ← Voltar à página inicial
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const togglePassword = document.getElementById('togglePassword');
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    const eyeIcon = document.getElementById('eyeIcon');
    const eyeIconConfirm = document.getElementById('eyeIconConfirm');
    
    // Senhas comuns para verificar
    const commonPasswords = [
        'password', '123456', '123456789', 'qwerty', 'abc123', 'monkey', 
        '1234567890', 'letmein', 'trustno1', 'dragon', 'baseball', 'iloveyou',
        'senha', 'admin', 'administrador', '123123', 'password123', 'senha123'
    ];
    
    // Toggle visibility da senha
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        if (type === 'text') {
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
            `;
        } else {
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            `;
        }
    });
    
    togglePasswordConfirm.addEventListener('click', function() {
        const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirmInput.setAttribute('type', type);
        
        if (type === 'text') {
            eyeIconConfirm.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
            `;
        } else {
            eyeIconConfirm.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            `;
        }
    });
    
    // Função para verificar força da senha
    function checkPasswordStrength(password) {
        let score = 0;
        const requirements = {
            length: false,
            lowercase: false,
            uppercase: false,
            number: false,
            special: false,
            notCommon: false
        };
        
        // Verificar comprimento
        if (password.length >= 8) {
            score += 20;
            requirements.length = true;
        }
        
        // Verificar letra minúscula
        if (/[a-z]/.test(password)) {
            score += 15;
            requirements.lowercase = true;
        }
        
        // Verificar letra maiúscula
        if (/[A-Z]/.test(password)) {
            score += 15;
            requirements.uppercase = true;
        }
        
        // Verificar número
        if (/[0-9]/.test(password)) {
            score += 15;
            requirements.number = true;
        }
        
        // Verificar caractere especial
        if (/[^A-Za-z0-9]/.test(password)) {
            score += 20;
            requirements.special = true;
        }
        
        // Verificar se não é senha comum
        if (!commonPasswords.includes(password.toLowerCase())) {
            score += 15;
            requirements.notCommon = true;
        } else {
            score = Math.max(0, score - 30); // Penalizar senhas comuns
        }
        
        return { score, requirements };
    }
    
    // Função para atualizar indicadores visuais
    function updatePasswordIndicators(password) {
        const { score, requirements } = checkPasswordStrength(password);
        const strengthDiv = document.getElementById('passwordStrength');
        const requirementsDiv = document.getElementById('passwordRequirements');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');
        
        if (password.length > 0) {
            strengthDiv.style.display = 'block';
            requirementsDiv.style.display = 'block';
        } else {
            strengthDiv.style.display = 'none';
            requirementsDiv.style.display = 'none';
            return;
        }
        
        // Atualizar barra de progresso
        strengthBar.style.width = score + '%';
        
        // Definir cor e texto baseado na pontuação
        if (score < 40) {
            strengthBar.style.backgroundColor = '#ef4444'; // red
            strengthText.textContent = 'Muito Fraca';
            strengthText.className = 'font-medium text-red-600';
        } else if (score < 60) {
            strengthBar.style.backgroundColor = '#f59e0b'; // yellow
            strengthText.textContent = 'Fraca';
            strengthText.className = 'font-medium text-yellow-600';
        } else if (score < 80) {
            strengthBar.style.backgroundColor = '#3b82f6'; // blue
            strengthText.textContent = 'Média';
            strengthText.className = 'font-medium text-blue-600';
        } else {
            strengthBar.style.backgroundColor = '#10b981'; // green
            strengthText.textContent = 'Forte';
            strengthText.className = 'font-medium text-green-600';
        }
        
        // Atualizar requisitos individuais
        const reqElements = {
            'req-length': requirements.length,
            'req-lowercase': requirements.lowercase,
            'req-uppercase': requirements.uppercase,
            'req-number': requirements.number,
            'req-special': requirements.special,
            'req-no-common': requirements.notCommon
        };
        
        Object.keys(reqElements).forEach(id => {
            const element = document.getElementById(id);
            const span = element.querySelector('span');
            
            if (reqElements[id]) {
                element.className = 'flex items-center text-green-600';
                span.textContent = '✓';
            } else {
                element.className = 'flex items-center text-red-500';
                span.textContent = '✗';
            }
        });
    }
    
    // Função para verificar se as senhas coincidem
    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirmation = passwordConfirmInput.value;
        const matchDiv = document.getElementById('passwordMatch');
        
        if (confirmation.length > 0) {
            matchDiv.style.display = 'block';
            
            if (password === confirmation) {
                matchDiv.innerHTML = '<span class="text-green-600">✓ As senhas coincidem</span>';
            } else {
                matchDiv.innerHTML = '<span class="text-red-500">✗ As senhas não coincidem</span>';
            }
        } else {
            matchDiv.style.display = 'none';
        }
    }
    
    // Event listeners
    passwordInput.addEventListener('input', function() {
        updatePasswordIndicators(this.value);
        if (passwordConfirmInput.value) {
            checkPasswordMatch();
        }
    });
    
    passwordConfirmInput.addEventListener('input', checkPasswordMatch);
    
    // Validação no submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const password = passwordInput.value;
        const confirmation = passwordConfirmInput.value;
        const { score } = checkPasswordStrength(password);
        
        if (score < 60) {
            e.preventDefault();
            alert('Por favor, crie uma senha mais forte. Sua senha deve ter pelo menos 60% de força para garantir a segurança da sua conta.');
            passwordInput.focus();
            return false;
        }
        
        if (password !== confirmation) {
            e.preventDefault();
            alert('As senhas não coincidem. Por favor, verifique e tente novamente.');
            passwordConfirmInput.focus();
            return false;
        }
    });
    
    // Modal de Termos e Condições
    const termsModal = document.getElementById('termsModal');
    const openTermsModal = document.getElementById('openTermsModal');
    const closeTermsModal = document.getElementById('closeTermsModal');
    const closeTermsModalBtn = document.getElementById('closeTermsModalBtn');
    const acceptTermsBtn = document.getElementById('acceptTermsBtn');
    const termsCheckbox = document.getElementById('terms');
    
    // Abrir modal
    openTermsModal.addEventListener('click', function(e) {
        e.preventDefault();
        termsModal.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevenir scroll da página
    });
    
    // Fechar modal
    function closeModal() {
        termsModal.style.display = 'none';
        document.body.style.overflow = 'auto'; // Restaurar scroll da página
    }
    
    closeTermsModal.addEventListener('click', closeModal);
    closeTermsModalBtn.addEventListener('click', closeModal);
    
    // Fechar modal clicando fora dele
    termsModal.addEventListener('click', function(e) {
        if (e.target === termsModal) {
            closeModal();
        }
    });
    
    // Aceitar termos
    acceptTermsBtn.addEventListener('click', function() {
        termsCheckbox.checked = true;
        closeModal();
    });
    
    // Fechar modal com ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && termsModal.style.display === 'block') {
            closeModal();
        }
    });
});
</script>

<!-- Modal de Termos e Condições -->
<div id="termsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" style="display: none;">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Header do Modal -->
            <div class="flex items-center justify-between pb-4 border-b">
                <h3 class="text-lg font-bold text-gray-900">Termos e Condições de Uso</h3>
                <button id="closeTermsModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Conteúdo dos Termos -->
            <div class="mt-4 max-h-96 overflow-y-auto text-sm text-gray-700 space-y-4">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">1. Aceitação dos Termos</h4>
                    <p>Ao utilizar o UNIDOC, você concorda com estes Termos e Condições de Uso. Se não concordar com qualquer parte destes termos, não utilize nosso serviço.</p>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">2. Descrição do Serviço</h4>
                    <p>O UNIDOC é uma plataforma digital para gestão e organização de documentos, oferecendo funcionalidades de armazenamento, categorização e acesso seguro aos seus documentos pessoais ou empresariais.</p>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">3. Registro e Conta</h4>
                    <p>Para utilizar nossos serviços, você deve:</p>
                    <ul class="list-disc ml-4 mt-2 space-y-1">
                        <li>Fornecer informações verdadeiras e atualizadas</li>
                        <li>Manter a segurança de sua senha</li>
                        <li>Ser responsável por todas as atividades em sua conta</li>
                        <li>Notificar-nos imediatamente sobre uso não autorizado</li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">4. Período de Teste Gratuito</h4>
                    <p>Oferecemos um período de teste gratuito de 15 dias. Durante este período:</p>
                    <ul class="list-disc ml-4 mt-2 space-y-1">
                        <li>Você tem acesso completo a todas as funcionalidades</li>
                        <li>Não há cobrança até o final do período</li>
                        <li>Você pode cancelar a qualquer momento sem custos</li>
                        <li>Após o período, será cobrada a taxa mensal aplicável</li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">5. Privacidade e Segurança</h4>
                    <p>Comprometemo-nos a:</p>
                    <ul class="list-disc ml-4 mt-2 space-y-1">
                        <li>Proteger seus dados pessoais conforme nossa Política de Privacidade</li>
                        <li>Implementar medidas de segurança adequadas</li>
                        <li>Não compartilhar seus documentos sem autorização</li>
                        <li>Realizar backups regulares de seus dados</li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">6. Uso Aceitável</h4>
                    <p>Você concorda em não usar nosso serviço para:</p>
                    <ul class="list-disc ml-4 mt-2 space-y-1">
                        <li>Atividades ilegais ou fraudulentas</li>
                        <li>Armazenar conteúdo ofensivo ou prejudicial</li>
                        <li>Violar direitos de propriedade intelectual</li>
                        <li>Comprometer a segurança do sistema</li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">7. Limitação de Responsabilidade</h4>
                    <p>O UNIDOC não se responsabiliza por:</p>
                    <ul class="list-disc ml-4 mt-2 space-y-1">
                        <li>Perda de dados devido a falhas técnicas imprevistas</li>
                        <li>Interrupções temporárias do serviço</li>
                        <li>Uso inadequado da plataforma pelo usuário</li>
                        <li>Danos indiretos ou consequenciais</li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">8. Cancelamento</h4>
                    <p>Você pode cancelar sua conta a qualquer momento. Nós podemos suspender ou cancelar sua conta em caso de violação destes termos.</p>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">9. Alterações nos Termos</h4>
                    <p>Reservamo-nos o direito de alterar estes termos a qualquer momento. As alterações serão comunicadas através de nossa plataforma.</p>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">10. Contato</h4>
                    <p>Para dúvidas sobre estes termos, entre em contato conosco através do suporte ao cliente em nossa plataforma.</p>
                </div>
                
                <div class="border-t pt-4 mt-6">
                    <p class="text-xs text-gray-500">
                        <strong>Última atualização:</strong> 3 de Julho de 2025<br>
                        <strong>UNIDOC</strong> - Único local para documentos
                    </p>
                </div>
            </div>
            
            <!-- Botões do Modal -->
            <div class="flex items-center justify-end pt-4 border-t space-x-3">
                <button id="closeTermsModalBtn" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">
                    Fechar
                </button>
                <button id="acceptTermsBtn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                    Aceitar Termos
                </button>
            </div>
        </div>
    </div>
</div>

@endsection