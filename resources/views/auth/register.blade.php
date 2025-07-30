@extends('layouts.guest')

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <!-- Header -->
            <div class="auth-header">
                <div class="auth-logo">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <h1 class="auth-title">Criar Conta</h1>
                <p class="auth-subtitle">Preencha os dados para se registrar</p>
            </div>

            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <!-- Account Type Selection -->
                <div class="form-group">
                    <label class="form-label">Tipo de Conta</label>
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="account_type" value="personal" checked onchange="toggleCompanyFields()">
                            <span>Conta Pessoal</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="account_type" value="company" onchange="toggleCompanyFields()">
                            <span>Conta Empresarial - Empresa Existente</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="account_type" value="new_company" onchange="toggleCompanyFields()">
                            <span>Conta Empresarial - Nova Empresa</span>
                        </label>
                    </div>
                    @error('account_type')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Company Selection (only for company accounts) -->
                <div id="companyFields" class="form-group hidden">
                    <label for="empresa_id" class="form-label">Empresa</label>
                    <select id="empresa_id" name="empresa_id" class="form-select">
                        <option value="">Selecione uma empresa</option>
                        @foreach($empresas as $empresa)
                            <option value="{{ $empresa->id }}" {{ old('empresa_id') == $empresa->id ? 'selected' : '' }}>
                                {{ $empresa->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('empresa_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- New Company Fields (only for new company accounts) -->
                <div id="newCompanyFields" class="hidden">
                    <div class="company-fields">
                        <h3>Dados da Nova Empresa</h3>
                        
                        <!-- Company Name -->
                        <div class="form-group">
                            <label for="company_nome" class="form-label">Nome da Empresa</label>
                            <input id="company_nome" type="text" name="company_nome" value="{{ old('company_nome') }}" class="form-input">
                            @error('company_nome')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Company Email -->
                        <div class="form-group">
                            <label for="company_email" class="form-label">Email da Empresa</label>
                            <input id="company_email" type="email" name="company_email" value="{{ old('company_email') }}" class="form-input">
                            @error('company_email')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Company Address -->
                        <div class="form-group">
                            <label for="company_endereco" class="form-label">Endereço da Empresa</label>
                            <textarea id="company_endereco" name="company_endereco" class="form-textarea">{{ old('company_endereco') }}</textarea>
                            @error('company_endereco')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="form-label">Nome</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="form-input" placeholder="Seu nome completo">
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="form-input" placeholder="seu@email.com">
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Senha</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password" class="form-input" placeholder="Sua senha">
                    <div class="password-strength" style="display: none;">
                        <div class="strength-bar" id="strengthBar"></div>
                    </div>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="form-input" placeholder="Confirme sua senha">
                    @error('password_confirmation')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Criar Conta
                </button>
            </form>

            <!-- Additional Links -->
            <div class="auth-links">
                <a href="{{ route('login') }}">Já tem uma conta? Entre aqui</a>
            </div>
        </div>
    </div>

    <script>
        function toggleCompanyFields() {
            const companyFields = document.getElementById('companyFields');
            const newCompanyFields = document.getElementById('newCompanyFields');
            const companyRadio = document.querySelector('input[name="account_type"][value="company"]');
            const newCompanyRadio = document.querySelector('input[name="account_type"][value="new_company"]');
            const empresaSelect = document.getElementById('empresa_id');
            const companyNome = document.getElementById('company_nome');
            const companyEmail = document.getElementById('company_email');
            const companyEndereco = document.getElementById('company_endereco');
            
            // Remove active styling from all radio options
            document.querySelectorAll('.radio-option').forEach(option => {
                option.classList.remove('checked');
            });
            
            // Add active styling to selected radio option
            const checkedRadio = document.querySelector('input[name="account_type"]:checked');
            if (checkedRadio) {
                checkedRadio.closest('.radio-option').classList.add('checked');
            }
            
            // Smooth transitions
            const hiddenElements = [companyFields, newCompanyFields];
            hiddenElements.forEach(el => {
                if (el) {
                    el.style.transition = 'all 0.3s ease';
                }
            });
            
            if (companyRadio.checked) {
                companyFields.classList.remove('hidden');
                newCompanyFields.classList.add('hidden');
                empresaSelect.required = true;
                companyNome.required = false;
                companyEmail.required = false;
                companyEndereco.required = false;
            } else if (newCompanyRadio.checked) {
                companyFields.classList.add('hidden');
                newCompanyFields.classList.remove('hidden');
                empresaSelect.required = false;
                empresaSelect.value = '';
                companyNome.required = true;
                companyEmail.required = true;
                companyEndereco.required = true;
            } else {
                companyFields.classList.add('hidden');
                newCompanyFields.classList.add('hidden');
                empresaSelect.required = false;
                empresaSelect.value = '';
                companyNome.required = false;
                companyEmail.required = false;
                companyEndereco.required = false;
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleCompanyFields();
            
            // Add smooth focus effects
            const inputs = document.querySelectorAll('.form-input, .form-select, .form-textarea');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'translateY(-1px)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'translateY(0)';
                });
            });
            
            // Add real-time validation feedback
            const emailInput = document.querySelector('input[name="email"]');
            const passwordInput = document.querySelector('input[name="password"]');
            const passwordConfirmInput = document.querySelector('input[name="password_confirmation"]');
            const strengthBar = document.getElementById('strengthBar');
            const strengthContainer = document.querySelector('.password-strength');
            
            if (emailInput) {
                emailInput.addEventListener('input', function() {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (this.value && !emailRegex.test(this.value)) {
                        this.style.borderColor = 'var(--color-warning)';
                    } else {
                        this.style.borderColor = '';
                    }
                });
            }
            
            if (passwordInput && strengthBar) {
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    let strength = 0;
                    
                    if (password.length >= 8) strength++;
                    if (/[a-z]/.test(password)) strength++;
                    if (/[A-Z]/.test(password)) strength++;
                    if (/[0-9]/.test(password)) strength++;
                    if (/[^A-Za-z0-9]/.test(password)) strength++;
                    
                    strengthContainer.style.display = password ? 'block' : 'none';
                    
                    // Remove all strength classes
                    strengthBar.className = 'strength-bar';
                    
                    if (strength <= 2) {
                        strengthBar.classList.add('strength-weak');
                    } else if (strength === 3) {
                        strengthBar.classList.add('strength-fair');
                    } else if (strength === 4) {
                        strengthBar.classList.add('strength-good');
                    } else if (strength === 5) {
                        strengthBar.classList.add('strength-strong');
                    }
                });
            }
            
            if (passwordConfirmInput && passwordInput) {
                passwordConfirmInput.addEventListener('input', function() {
                    if (this.value && this.value !== passwordInput.value) {
                        this.style.borderColor = 'var(--color-warning)';
                    } else {
                        this.style.borderColor = '';
                    }
                });
            }
        });
    </script>
@endsection
