@extends('layouts.guest')

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <!-- Header -->
            <div class="auth-header">
                <div class="auth-logo">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="auth-title">Conta Criada com Sucesso!</h1>
                <p class="auth-subtitle">Aguardando aprovação do administrador</p>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="status-message success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Main Content -->
            <div class="pending-content">
                <div class="pending-icon">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>

                <div class="pending-info">
                    <h2 class="pending-title">{{ __('Aguardando Aprovação') }}</h2>
                    <p class="pending-description">
                        {{ __('Um administrador irá revisar sua solicitação de conta. Você receberá um email quando sua conta for aprovada.') }}
                    </p>

                    <div class="pending-steps">
                        <div class="step completed">
                            <div class="step-icon">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span>Conta criada</span>
                        </div>
                        <div class="step pending">
                            <div class="step-icon">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span>Aguardando aprovação</span>
                        </div>
                        <div class="step future">
                            <div class="step-icon">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </div>
                            <span>Acesso liberado</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Links -->
            <div class="auth-links">
                <a href="{{ route('login') }}">Voltar ao Login</a>
            </div>
        </div>
    </div>
@endsection
