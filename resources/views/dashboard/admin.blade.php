@extends('layouts.app')

@section('content')
    <!-- Debug: Verificar se view está carregando -->
    <script>console.log('Dashboard admin carregado');</script>
    
    <div class="fade-in" style="min-height: 500px; background: white; padding: 20px;">
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
                        <div class="d-flex gap-2">
                            <a href="{{ route('payments.multi_month') }}" class="btn btn-{{ $trialNotification['type'] == 'warning' ? 'warning' : ($trialNotification['type'] == 'success' ? 'success' : 'primary') }}">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1H2zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V7z"/>
                                    <path d="M2 10a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1z"/>
                                </svg>
                                @if($trialNotification['days_remaining'] > 7)
                                    Pagar Multi-Mês
                                @elseif($trialNotification['days_remaining'] > 0)
                                    Realizar Pagamento
                                @else
                                    Realizar Pagamento
                                @endif
                            </a>
                            @if(isset($trialNotification['show_payment_history']) && $trialNotification['show_payment_history'])
                                <a href="{{ route('payments.status') }}" class="btn btn-outline-secondary">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                        <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                                    </svg>
                                    Status
                                </a>
                            @endif
                        </div>
                    @elseif(isset($trialNotification['show_retry_payment']))
                        <a href="{{ route('payments.multi_month') }}" class="btn btn-danger">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
                                <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
                            </svg>
                            Tentar Novamente
                        </a>
                    @elseif(isset($trialNotification['show_payment_history']) && $trialNotification['show_payment_history'])
                        <div class="d-flex gap-2">
                            <a href="{{ route('payments.status') }}" class="btn btn-outline-primary">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                    <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                                </svg>
                                Ver Status
                            </a>
                            <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                    <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                                </svg>
                                Histórico
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="card-title mb-1">Dashboard Administrativo</h1>
                <p class="text-muted">Visão geral do sistema de {{ $empresa->nome }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('files.create') }}" class="btn btn-primary">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                    Novo Arquivo
                </a>
                <a href="{{ route('categories.create') }}" class="btn btn-secondary">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3zm-8.322.12C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139z"/>
                    </svg>
                    Nova Categoria
                </a>
            </div>
        </div>        <!-- Estatísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $stats['total_usuarios'] }}</div>
                <div class="stat-label">Usuários Aprovados</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number text-warning">{{ $stats['usuarios_pendentes'] }}</div>
                <div class="stat-label">Aguardando Aprovação</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number">{{ $stats['total_categorias'] }}</div>
                <div class="stat-label">Categorias</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number">{{ $stats['total_subcategorias'] }}</div>
                <div class="stat-label">Subcategorias</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number">{{ $stats['total_arquivos'] }}</div>
                <div class="stat-label">Total de Arquivos</div>
            </div>

            <div class="stat-card">
                <div class="stat-number">{{ $stats['arquivos_hoje'] }}</div>
                <div class="stat-label">Arquivos Hoje</div>
            </div>
        </div>

        <!-- Seção de Aprovação de Usuários -->
        @if($usuariosPendentes->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 8px;">
                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                        <path d="M14.5 3a.5.5 0 0 1 .5.5V4h.5a.5.5 0 0 1 0 1H15v.5a.5.5 0 0 1-1 0V5h-.5a.5.5 0 0 1 0-1h.5v-.5a.5.5 0 0 1 .5-.5z"/>
                    </svg>
                    Usuários Aguardando Aprovação ({{ $usuariosPendentes->count() }})
                </h3>
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">Ver Todos os Usuários</a>
            </div>

            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Função</th>
                            <th>Data de Registro</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuariosPendentes as $usuario)
                        <tr>
                            <td>
                                <div class="font-weight-600">{{ $usuario->name }}</div>
                            </td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                <span class="badge badge-secondary">
                                    {{ ucfirst(str_replace('_', ' ', $usuario->role)) }}
                                </span>
                            </td>
                            <td>{{ $usuario->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <form action="{{ route('empresa.users.approve', $usuario) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Aprovar este usuário?')">
                                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                            </svg>
                                            Aprovar
                                        </button>
                                    </form>
                                    <form action="{{ route('empresa.users.reject', $usuario) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Rejeitar este usuário? Esta ação não pode ser desfeita.')">
                                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                            </svg>
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
        @endif

        <div class="d-flex gap-3" style="grid-template-columns: 2fr 1fr;">
            <!-- Arquivos Recentes -->
            <div class="card" style="flex: 2;">
                <div class="card-header">
                    <h3 class="card-title">Arquivos Recentes</h3>
                    <a href="{{ route('files.index') }}" class="btn btn-sm btn-secondary">Ver Todos</a>
                </div>

                @if($arquivos_recentes->count() > 0)
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Arquivo</th>
                                    <th>Usuário</th>
                                    <th>Categoria</th>
                                    <th>Data</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($arquivos_recentes as $arquivo)
                                <tr>
                                    <td>
                                        <div>
                                            <div class="font-weight-600">{{ $arquivo->titulo }}</div>
                                            <div class="text-muted text-sm">{{ $arquivo->original_name }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $arquivo->user->name }}</td>
                                    <td>{{ $arquivo->category->nome }}</td>
                                    <td>{{ $arquivo->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('files.show', $arquivo) }}" class="btn btn-sm btn-secondary">Ver</a>
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
                            <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                        </svg>
                        <p>Nenhum arquivo encontrado</p>
                        <a href="{{ route('files.create') }}" class="btn btn-primary">Enviar Primeiro Arquivo</a>
                    </div>
                @endif
            </div>

            <!-- Categorias -->
            <div class="card" style="flex: 1;">
                <div class="card-header">
                    <h3 class="card-title">Categorias</h3>
                    <a href="{{ route('categories.create') }}" class="btn btn-sm btn-primary">Adicionar</a>
                </div>

                @if($categorias->count() > 0)
                    <div class="space-y-3">
                        @foreach($categorias as $categoria)
                        <div class="d-flex justify-content-between align-items-center p-3" style="background-color: var(--color-gray-50); border-radius: var(--radius-md);">
                            <div>
                                <div class="font-weight-600">{{ $categoria->nome }}</div>
                                <div class="text-muted text-sm">
                                    {{ $categoria->files_count }} arquivos, {{ $categoria->subcategories_count }} subcategorias
                                </div>
                            </div>
                            <div class="d-flex gap-1">
                                <a href="{{ route('categories.edit', $categoria) }}" class="btn btn-sm btn-secondary">Editar</a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted" style="padding: 40px;">
                        <svg width="48" height="48" fill="currentColor" viewBox="0 0 16 16" style="margin-bottom: 16px; opacity: 0.5;">
                            <path d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3zm-8.322.12C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139z"/>
                        </svg>
                        <p>Nenhuma categoria criada</p>
                        <a href="{{ route('categories.create') }}" class="btn btn-primary">Criar Primeira Categoria</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Ações Rápidas -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ações Rápidas</h3>
            </div>
            
            <div class="d-flex gap-3">
                <a href="{{ route('users.create') }}" class="btn btn-lg btn-primary">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                        <path d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
                    </svg>
                    Adicionar Usuário
                </a>
                
                <a href="{{ route('categories.index') }}" class="btn btn-lg btn-secondary">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z"/>
                    </svg>
                    Gerenciar Categorias
                </a>
                
                <a href="{{ route('empresa.show') }}" class="btn btn-lg btn-secondary">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383-4.708 2.825L15 11.105V5.383zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741zM1 11.105l4.708-2.897L1 5.383v5.722z"/>
                    </svg>
                    Configurações da Empresa
                </a>
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
        .text-warning {
            color: #f39c12 !important;
        }
        .badge {
            display: inline-block;
            padding: 0.25em 0.4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
        }
        .badge-secondary {
            color: #fff;
            background-color: #6c757d;
        }
        .btn-success {
            color: #fff;
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .btn-danger {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .card .card-header {
            display: flex;
            justify-content: between;
            align-items: center;
        }
    </style>
@endsection 