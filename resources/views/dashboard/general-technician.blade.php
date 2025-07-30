<x-app-layout>
    <div class="fade-in">
        <!-- Notificação do Período de Teste -->
        @if($trialNotification)
        <div class="alert alert-{{ $trialNotification['type'] == 'warning' ? 'warning' : ($trialNotification['type'] == 'info' ? 'info' : 'success') }} mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        @if($trialNotification['type'] == 'warning')
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-2.008 0L.127 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/>
                                <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/>
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
                            @if($trialNotification['days_remaining'] <= 3)
                                ⏰ Período de Teste Expirando!
                            @else
                                📅 Período de Teste Ativo
                            @endif
                        </h5>
                        <p class="mb-0">{{ $trialNotification['message'] }}</p>
                    </div>
                </div>
                {{-- Técnicos gerais não podem realizar pagamentos --}}
            </div>
        </div>
        @endif

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="card-title mb-1">Meu Dashboard</h1>
                <p class="text-muted">Bem-vindo, {{ auth()->user()->name }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('files.create') }}" class="btn btn-primary">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                    Novo Arquivo
                </a>
                <a href="{{ route('files.index') }}" class="btn btn-secondary">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                    </svg>
                    Ver Todos os Arquivos
                </a>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $stats['meus_arquivos'] }}</div>
                <div class="stat-label">Meus Arquivos</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number">{{ $stats['arquivos_hoje'] }}</div>
                <div class="stat-label">Enviados Hoje</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number">{{ $stats['categorias_disponiveis'] }}</div>
                <div class="stat-label">Categorias Disponíveis</div>
            </div>
        </div>

        <div class="d-flex gap-3">
            <!-- Meus Arquivos Recentes -->
            <div class="card" style="flex: 2;">
                <div class="card-header">
                    <h3 class="card-title">Meus Arquivos Recentes</h3>
                    <a href="{{ route('files.index') }}" class="btn btn-sm btn-secondary">Ver Todos</a>
                </div>

                @if($meus_arquivos->count() > 0)
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Arquivo</th>
                                    <th>Categoria</th>
                                    <th>Subcategoria</th>
                                    <th>Data</th>
                                    <th>Tamanho</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($meus_arquivos as $arquivo)
                                <tr>
                                    <td>
                                        <div>
                                            <div class="font-weight-600">{{ $arquivo->titulo }}</div>
                                            <div class="text-muted text-sm">{{ $arquivo->original_name }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $arquivo->category->nome }}</td>
                                    <td>{{ $arquivo->subcategory->nome ?? '-' }}</td>
                                    <td>{{ $arquivo->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $arquivo->formatted_size }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('files.show', $arquivo) }}" class="btn btn-sm btn-secondary">Ver</a>
                                            <a href="{{ route('files.edit', $arquivo) }}" class="btn btn-sm btn-warning">Editar</a>
                                            <a href="{{ route('files.view', $arquivo) }}" class="btn btn-sm btn-info">Visualizar</a>
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
                        <p>Você ainda não enviou nenhum arquivo</p>
                        <a href="{{ route('files.create') }}" class="btn btn-primary">Enviar Primeiro Arquivo</a>
                    </div>
                @endif
            </div>

            <!-- Categorias Disponíveis -->
            <div class="card" style="flex: 1;">
                <div class="card-header">
                    <h3 class="card-title">Categorias Disponíveis</h3>
                    <span class="text-muted text-sm">Você pode enviar arquivos para todas</span>
                </div>

                @if($categorias->count() > 0)
                    <div class="space-y-3">
                        @foreach($categorias as $categoria)
                        <div class="p-3" style="background-color: var(--color-gray-50); border-radius: var(--radius-md);">
                            <div>
                                <div class="font-weight-600">{{ $categoria->nome }}</div>
                                <div class="text-muted text-sm mb-2">
                                    {{ $categoria->subcategories->count() }} subcategorias
                                </div>
                                
                                @if($categoria->subcategories->count() > 0)
                                    <div class="d-flex gap-1" style="flex-wrap: wrap;">
                                        @foreach($categoria->subcategories as $subcategoria)
                                            <span class="badge bg-light text-dark">{{ $subcategoria->nome }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted" style="padding: 40px;">
                        <svg width="48" height="48" fill="currentColor" viewBox="0 0 16 16" style="margin-bottom: 16px; opacity: 0.5;">
                            <path d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3zm-8.322.12C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139z"/>
                        </svg>
                        <p>Nenhuma categoria disponível</p>
                        <p class="text-sm">Entre em contato com o administrador</p>
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
                <a href="{{ route('files.create') }}" class="btn btn-lg btn-primary">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M6 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V2zm2-1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H8z"/>
                        <path d="M8 4a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 8 4zm0 2a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 8 6zm0 2a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 8 8zm0 2a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5z"/>
                    </svg>
                    Enviar Novo Arquivo
                </a>
                
                <a href="{{ route('files.index') }}" class="btn btn-lg btn-secondary">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                    </svg>
                    Navegar nos Arquivos
                </a>
                
                <a href="{{ route('profile.edit') }}" class="btn btn-lg btn-secondary">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                    </svg>
                    Editar Perfil
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
</x-app-layout> 