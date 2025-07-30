@extends('layouts.app')

@section('content')
    <!-- Debug: Verificar se view está carregando -->
    <script>console.log('Dashboard personal user carregado');</script>
    
    <div class="fade-in" style="min-height: 500px; background: var(--color-gray-50); padding: 20px;">
        <!-- Notificação do Período de Teste -->
        @if($trialNotification)
        <div class="alert alert-{{ $trialNotification['type'] == 'warning' ? 'warning' : ($trialNotification['type'] == 'info' ? 'info' : ($trialNotification['type'] == 'danger' ? 'danger' : 'success')) }} mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        @if($trialNotification['type'] == 'warning')
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-2.008 0L.127 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/>
                                <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/>
                            </svg>
                        @elseif($trialNotification['type'] == 'danger')
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                            </svg>
                        @elseif($trialNotification['type'] == 'success')
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.061L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                            </svg>
                        @else
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <h5 class="mb-1">
                            @if($trialNotification['type'] == 'danger')
                                ❌ Pagamento Rejeitado
                            @elseif($trialNotification['type'] == 'success' && $trialNotification['days_remaining'] == 0)
                                ✅ Pagamento Aprovado
                            @elseif($trialNotification['type'] == 'info' && $trialNotification['days_remaining'] == 0)
                                ⏳ Pagamento em Análise
                            @elseif($trialNotification['days_remaining'] <= 3 && $trialNotification['days_remaining'] > 0)
                                ⏰ Período de Teste Expirando!
                            @elseif(isset($trialNotification['show_payment_button']))
                                💳 Pagamento Necessário
                            @else
                                📅 Período de Teste Ativo
                            @endif
                        </h5>
                        <p class="mb-0">{{ $trialNotification['message'] }}</p>
                    </div>
                </div>
                <div>
                    @if(isset($trialNotification['show_payment_button']) && $trialNotification['show_payment_button'])
                        <a href="{{ route('payments.multi_month') }}" class="btn btn-{{ $trialNotification['type'] == 'warning' ? 'warning' : ($trialNotification['type'] == 'success' ? 'success' : 'primary') }}">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1H2zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V7z"/>
                                <path d="M2 10a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1z"/>
                            </svg>
                            @if($trialNotification['days_remaining'] > 7)
                                Pagar Antecipadamente
                            @elseif($trialNotification['days_remaining'] > 0)
                                Realizar Pagamento
                            @else
                                Realizar Pagamento
                            @endif
                        </a>
                    @elseif(isset($trialNotification['show_retry_payment']))
                        <a href="{{ route('payments.multi_month') }}" class="btn btn-danger">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
                                <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
                            </svg>
                            Tentar Novamente
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="card-title mb-1">Dashboard Pessoal</h1>
                <p class="text-muted">Bem-vindo(a), {{ $user->name }}!</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('files.create') }}" class="btn btn-primary">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                    Novo Arquivo
                </a>
                <a href="{{ route('payments.multi_month') }}" class="btn btn-success">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1H2zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V7z"/>
                        <path d="M2 10a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1z"/>
                    </svg>
                    Pagamentos
                </a>
                <a href="{{ route('payments.status') }}" class="btn btn-info">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"/>
                    </svg>
                    Histórico
                </a>
                <a href="{{ route('profile.edit') }}" class="btn btn-secondary">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                    </svg>
                    Editar Perfil
                </a>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $stats['total_files'] ?? 0 }}</div>
                <div class="stat-label">Total de Arquivos</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number">{{ $stats['files_this_month'] ?? 0 }}</div>
                <div class="stat-label">Arquivos Este Mês</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number">{{ $stats['storage_used'] ?? '0 MB' }}</div>
                <div class="stat-label">Espaço Usado</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number">{{ $stats['account_type'] ?? 'Pessoal' }}</div>
                <div class="stat-label">Tipo de Conta</div>
            </div>
        </div>

        <div class="d-flex gap-3" style="grid-template-columns: 2fr 1fr;">
            <!-- Arquivos Recentes -->
            <div class="card" style="flex: 2;">
                <div class="card-header">
                    <h3 class="card-title">Arquivos Recentes</h3>
                    <a href="{{ route('files.index') }}" class="btn btn-sm btn-secondary">Ver Todos</a>
                </div>

                @if(isset($recentFiles) && $recentFiles->count() > 0)
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Arquivo</th>
                                    <th>Data de Upload</th>
                                    <th>Tamanho</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentFiles as $arquivo)
                                <tr>
                                    <td>
                                        <div>
                                            <div class="font-weight-600">{{ $arquivo->titulo }}</div>
                                            <div class="text-muted text-sm">{{ $arquivo->original_name }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $arquivo->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($arquivo->size)
                                            {{ number_format($arquivo->size / 1024, 1) }} KB
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('files.view', $arquivo) }}" class="btn btn-sm btn-secondary">Ver</a>
                                            <a href="{{ route('files.download', $arquivo) }}" class="btn btn-sm btn-primary">Download</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted" style="padding: 40px;">
                        <svg width="48" height="48" fill="currentColor" viewBox="0 0 16 16" style="margin-bottom: 16px; opacity: 0.5;">
                            <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                        </svg>
                        <p>Nenhum arquivo encontrado</p>
                        <a href="{{ route('files.create') }}" class="btn btn-primary mt-2">Enviar Primeiro Arquivo</a>
                    </div>
                @endif
            </div>

            <!-- Informações da Conta -->
            <div class="card" style="flex: 1;">
                <div class="card-header">
                    <h3 class="card-title">Informações da Conta</h3>
                </div>
                <div class="card-body space-y-3">
                    <div>
                        <div class="text-sm text-muted">Nome</div>
                        <div class="font-weight-600">{{ $user->name }}</div>
                    </div>
                    
                    <div>
                        <div class="text-sm text-muted">Email</div>
                        <div class="font-weight-600">{{ $user->email }}</div>
                    </div>
                    
                    <div>
                        <div class="text-sm text-muted">Tipo de Conta</div>
                        <span class="badge bg-light text-dark">{{ $stats['account_type'] ?? 'Pessoal' }}</span>
                    </div>
                    
                    <div>
                        <div class="text-sm text-muted">Status</div>
                        <span class="badge bg-success">{{ $stats['status'] ?? 'Ativo' }}</span>
                    </div>
                    
                    <div>
                        <div class="text-sm text-muted">Membro desde</div>
                        <div class="font-weight-600">{{ $stats['created_at'] ?? $user->created_at->format('d/m/Y') }}</div>
                    </div>

                    <hr>

                    <div class="d-grid gap-2">
                        <a href="{{ route('files.index') }}" class="btn btn-primary btn-sm">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                            </svg>
                            Gerenciar Arquivos
                        </a>
                        
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-sm">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708L14.5 5.207l-8 8A.5.5 0 0 1 6.146 13.5l-.043-.5L6 13h.5a.5.5 0 0 1 .354.146l.146.147-.708.708z"/>
                                <path d="M10.854 2.146a.5.5 0 0 0-.708.708L11.793 4.5 10.5 5.793l-.707-.707a.5.5 0 0 0-.708.708l1 1a.5.5 0 0 0 .708 0l2-2z"/>
                            </svg>
                            Editar Perfil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .space-y-3 > * + * {
            margin-top: 0.75rem;
        }
        .font-weight-600 {
            font-weight: 600;
        }
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 0.25rem;
        }
        .bg-light {
            background-color: var(--color-gray-200) !important;
        }
        .text-dark {
            color: var(--color-gray-800) !important;
        }
    </style>
                <div class="card h-100 dashboard-card">
                    <div class="card-header">
                        <h5 class="mb-0">Informações da Conta</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Nome:</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ $user->name }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Email:</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ $user->email }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Tipo:</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ $stats['account_type'] }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Status:</strong>
                            </div>
                            <div class="col-sm-8">
                                <span class="badge bg-success">{{ $stats['status'] }}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <strong>Membro desde:</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ $stats['created_at'] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100 dashboard-card">
                    <div class="card-header">
                        <h5 class="mb-0">Ações Rápidas</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            {{-- File Management Actions --}}
                            <a href="{{ route('files.index') }}" class="btn btn-primary d-flex align-items-center action-btn">
                                <svg width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                    <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                                </svg>
                                Meus Arquivos
                            </a>
                            
                            <a href="{{ route('files.create') }}" class="btn btn-success d-flex align-items-center action-btn">
                                <svg width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                    <path d="M8.5 6a.5.5 0 0 0-1 0v1.5H6a.5.5 0 0 0 0 1h1.5V10a.5.5 0 0 0 1 0V8.5H10a.5.5 0 0 0 0-1H8.5V6z"/>
                                    <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z"/>
                                </svg>
                                Enviar Arquivo
                            </a>
                            
                            <hr class="my-2">
                            
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary d-flex align-items-center action-btn">
                                <svg width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708L14.5 5.207l-8 8A.5.5 0 0 1 6.146 13.5l-.043-.5L6 13h.5a.5.5 0 0 1 .354.146l.146.147-.708.708z"/>
                                    <path d="M10.854 2.146a.5.5 0 0 0-.708.708L11.793 4.5 10.5 5.793l-.707-.707a.5.5 0 0 0-.708.708l1 1a.5.5 0 0 0 .708 0l2-2z"/>
                                </svg>
                                Editar Perfil
                            </a>
                            
                            <a href="{{ route('password.request') }}" class="btn btn-outline-secondary d-flex align-items-center action-btn">
                                <svg width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                    <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5zm.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1h-4zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2z"/>
                                </svg>
                                Alterar Senha
                            </a>

                            <a href="#" class="btn btn-outline-info d-flex align-items-center action-btn" onclick="showHelp()">
                                <svg width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
                                </svg>
                                Ajuda e Suporte
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informações Adicionais -->
        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Arquivos Recentes</h5>
                        <a href="{{ route('files.index') }}" class="btn btn-sm btn-outline-primary">Ver Todos</a>
                    </div>
                    <div class="card-body">
                        @if(isset($recentFiles) && $recentFiles->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentFiles as $file)
                                    <div class="list-group-item d-flex justify-content-between align-items-center file-item">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                @php
                                                    $extension = strtolower(pathinfo($file->original_name, PATHINFO_EXTENSION));
                                                    $iconClass = match($extension) {
                                                        'pdf' => 'text-danger',
                                                        'doc', 'docx' => 'text-primary',
                                                        'xls', 'xlsx' => 'text-success',
                                                        'jpg', 'jpeg', 'png', 'gif' => 'text-warning',
                                                        default => 'text-secondary'
                                                    };
                                                @endphp
                                                <svg width="20" height="20" fill="currentColor" class="{{ $iconClass }}" viewBox="0 0 16 16">
                                                    <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $file->titulo }}</h6>
                                                <small class="text-muted">{{ $file->created_at->format('d/m/Y H:i') }}</small>
                                            </div>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('files.view', $file) }}" class="btn btn-outline-primary" title="Visualizar">
                                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                                </svg>
                                            </a>
                                            <a href="{{ route('files.download', $file) }}" class="btn btn-outline-success" title="Download">
                                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                                    <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <svg width="48" height="48" fill="currentColor" class="text-muted mb-3" viewBox="0 0 16 16">
                                    <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                                </svg>
                                <p class="text-muted mb-2">Nenhum arquivo enviado ainda</p>
                                <a href="{{ route('files.create') }}" class="btn btn-primary">Enviar Primeiro Arquivo</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Bem-vindo ao Sistema</h5>
                        <p class="card-text">
                            Como usuário pessoal, você tem acesso aos recursos básicos do sistema. 
                            Sua conta está ativa e pronta para uso.
                        </p>
                        <div class="alert alert-info">
                            <strong>Recursos Disponíveis:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Upload de arquivos</li>
                                <li>Visualização e download</li>
                                <li>Gerenciamento pessoal</li>
                                <li>Perfil customizável</li>
                            </ul>
                        </div>
                        <div class="alert alert-success">
                            <strong>Dica:</strong> Use a navegação superior para acessar rapidamente seus arquivos.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showHelp() {
            alert('Funcionalidade de ajuda em desenvolvimento. Entre em contato com o administrador do sistema para suporte.');
        }
    </script>
@endsection
