@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h1 class="text-2xl font-bold text-gray-900">{{ config('app.name', 'File Management') }}</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('plan.select', 'pessoal') }}" class="text-gray-600 hover:text-gray-900 px-4 py-2 rounded-lg transition duration-200">Voltar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Form -->
    <div class="py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Registro Pessoal</h1>
                <p class="text-xl text-gray-600">Complete o formulário para criar sua conta pessoal</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8 border">
                <!-- Plan Info -->
                <div class="bg-green-50 rounded-lg p-4 mb-8 border border-green-200">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Plano Pessoal Selecionado</h3>
                            <p class="text-sm text-gray-600">Acesso às funcionalidades básicas do sistema</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('register.store.pessoal') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <input type="hidden" name="account_type" value="personal">
                    <input type="hidden" name="plan_type" value="pessoal">

                    <!-- Personal Name -->
                    <div class="input-group">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome Completo *
                        </label>
                        <input 
                            id="name" 
                            name="name" 
                            type="text" 
                            required 
                            value="{{ old('name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                            placeholder="Digite seu nome completo"
                        >
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Personal Email -->
                    <div class="input-group">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Pessoal *
                        </label>
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            required 
                            value="{{ old('email') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                            placeholder="seuemail@exemplo.com"
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div class="input-group">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Número de Telefone
                        </label>
                        <input 
                            id="phone" 
                            name="phone" 
                            type="tel" 
                            value="{{ old('phone') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                            placeholder="+244 xxx xxx xxx"
                        >
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="input-group">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Palavra-passe *
                        </label>
                        <div class="relative">
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                placeholder="Crie uma senha forte"
                                minlength="8"
                            >
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

                    <!-- Confirm Password -->
                    <div class="input-group">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmar Palavra-passe *
                        </label>
                        <div class="relative">
                            <input 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                type="password" 
                                required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                placeholder="Confirme sua palavra-passe"
                                minlength="8"
                            >
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
                    </div>

                    <!-- Payment Proof Upload -->
                    <div class="input-group">
                        <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-2">
                            Comprovativo de Pagamento *
                        </label>
                        <div class="file-drop-zone border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-green-400 transition-colors duration-300 cursor-pointer" id="file-drop-zone">
                            <input type="file" id="payment_proof" name="payment_proof" accept="image/*,.pdf" required class="hidden" onchange="handleFileSelect(this)">
                            <div id="file-display">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-sm text-gray-600 mb-2">
                                    Clique para selecionar ou arraste o arquivo aqui
                                </p>
                                <p class="text-xs text-gray-500">
                                    Formatos aceitos: JPG, PNG, PDF (máx. 5MB)
                                </p>
                            </div>
                        </div>
                        @error('payment_proof')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Information -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Informações de Pagamento</h4>
                        <div class="space-y-2 text-sm text-gray-700">
                            <p><strong>IBAN:</strong> 0055.0000.9274.3910.1018.0</p>
                            <p><strong>Titular:</strong> Vambert Capita</p>
                            <p><strong>Referência:</strong> PESSOAL-{{ now()->format('Ymd') }}</p>
                        </div>
                        <div class="mt-4 p-3 bg-green-100 rounded-lg">
                            <p class="text-sm text-green-800">
                                <strong>Importante:</strong> Efetue o pagamento antes de enviar o formulário. 
                                Sua conta será ativada após validação do comprovativo.
                            </p>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input 
                                id="terms" 
                                name="terms" 
                                type="checkbox" 
                                required
                                class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500"
                            >
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="text-gray-700">
                                Concordo com os 
                                <a href="#" class="text-blue-600 hover:text-blue-800 underline">termos de serviço</a> 
                                e 
                                <a href="#" class="text-blue-600 hover:text-blue-800 underline">política de privacidade</a>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button 
                            type="submit" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white py-4 px-6 rounded-lg font-bold text-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                            Criar Conta Pessoal
                        </button>
                    </div>

                    <!-- Alternative Actions -->
                    <div class="text-center space-y-2 pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600">
                            Já tem uma conta? 
                            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                Faça login
                            </a>
                        </p>
                        <p class="text-sm text-gray-600">
                            <a href="{{ route('plan.select', 'empresa') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                Mudar para Plano Empresarial
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .file-drop-zone.dragover {
            border-color: #10b981;
            background-color: #ecfdf5;
        }
    </style>

    <script>
        // File upload handling
        function handleFileSelect(input) {
            const file = input.files[0];
            const fileDisplay = document.getElementById('file-display');
            
            if (file) {
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                const fileName = file.name;
                
                fileDisplay.innerHTML = `
                    <div class="flex items-center justify-center space-x-3">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="text-left">
                            <p class="text-sm font-medium text-gray-900">${fileName}</p>
                            <p class="text-xs text-gray-500">${fileSize} MB</p>
                        </div>
                    </div>
                    <button type="button" onclick="clearFile()" class="mt-2 text-xs text-red-600 hover:text-red-800">
                        Remover arquivo
                    </button>
                `;
            }
        }

        function clearFile() {
            document.getElementById('payment_proof').value = '';
            const fileDisplay = document.getElementById('file-display');
            fileDisplay.innerHTML = `
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                <p class="text-sm text-gray-600 mb-2">
                    Clique para selecionar ou arraste o arquivo aqui
                </p>
                <p class="text-xs text-gray-500">
                    Formatos aceitos: JPG, PNG, PDF (máx. 5MB)
                </p>
            `;
        }

        // Drag and drop functionality
        const dropZone = document.getElementById('file-drop-zone');
        const fileInput = document.getElementById('payment_proof');

        dropZone.addEventListener('click', () => fileInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('dragover');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect(fileInput);
            }
        });
        
        // Sistema de validação de senha forte
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
                if (element) {
                    const span = element.querySelector('span');
                    
                    if (reqElements[id]) {
                        element.className = 'flex items-center text-green-600';
                        span.textContent = '✓';
                    } else {
                        element.className = 'flex items-center text-red-500';
                        span.textContent = '✗';
                    }
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
        
        // Event listeners para senha
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                updatePasswordIndicators(this.value);
                if (passwordConfirmInput.value) {
                    checkPasswordMatch();
                }
            });
        }
        
        if (passwordConfirmInput) {
            passwordConfirmInput.addEventListener('input', checkPasswordMatch);
        }
        
        // Validação no submit do formulário
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
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
        }
    </script>
@endsection
