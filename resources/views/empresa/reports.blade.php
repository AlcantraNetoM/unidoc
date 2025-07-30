<x-app-layout>
    <div class="fade-in">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="card-title mb-1">Relatórios da Empresa</h1>
                <p class="text-muted">Análises e estatísticas detalhadas da {{ $empresa->nome }}</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-secondary" onclick="window.print()">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M2.5 8a.5.5 0 1 0 0 1h11a.5.5 0 1 0 0-1h-11z"/>
                        <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v1H4V3z"/>
                        <path d="M2 6a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v-2a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v2h1a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2H2zm11 7H3v2a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-2z"/>
                    </svg>
                    Imprimir
                </button>
                <a href="{{ route('empresa.settings') }}" class="btn btn-secondary">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                        <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.292-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.292c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
                    </svg>
                    Configurações
                </a>
            </div>
        </div>

        <!-- Estatísticas Gerais -->
        <div class="stats-overview">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon bg-primary">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                            <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/>
                            <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                        </svg>
                    </div>
                    <h3>Total de Usuários</h3>
                </div>
                <div class="stat-number">{{ $stats['total_usuarios'] }}</div>
                <div class="stat-change positive">
                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L7.5 10.293V4.5A.5.5 0 0 1 8 4z"/>
                    </svg>
                    Ativos no sistema
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon bg-success">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3zm-8.322.12C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139z"/>
                        </svg>
                    </div>
                    <h3>Categorias</h3>
                </div>
                <div class="stat-number">{{ $stats['total_categorias'] }}</div>
                <div class="stat-change neutral">
                    {{ $stats['total_subcategorias'] }} subcategorias
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon bg-info">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                        </svg>
                    </div>
                    <h3>Total de Arquivos</h3>
                </div>
                <div class="stat-number">{{ $stats['total_arquivos'] }}</div>
                <div class="stat-change positive">
                    +{{ $stats['arquivos_mes'] }} este mês
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon bg-warning">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M9.5 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0zm3.5-9a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0z"/>
                        </svg>
                    </div>
                    <h3>Espaço Utilizado</h3>
                </div>
                <div class="stat-number">{{ number_format($stats['tamanho_total'] / 1024 / 1024, 1) }}</div>
                <div class="stat-change neutral">
                    MB de arquivos
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Arquivos por Categoria -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Arquivos por Categoria</h3>
                    </div>
                    
                    @if($arquivosPorCategoria->count() > 0)
                        <div class="category-chart">
                            @foreach($arquivosPorCategoria as $categoria)
                            <div class="category-item">
                                <div class="category-info">
                                    <span class="category-name">{{ $categoria->nome }}</span>
                                    <span class="category-count">{{ $categoria->files_count }} arquivos</span>
                                </div>
                                <div class="category-bar">
                                    <div class="category-progress" 
                                         data-percentage="{{ $stats['total_arquivos'] > 0 ? ($categoria->files_count / $stats['total_arquivos']) * 100 : 0 }}">
                                    </div>
                                </div>
                                <span class="category-percentage">
                                    {{ $stats['total_arquivos'] > 0 ? number_format(($categoria->files_count / $stats['total_arquivos']) * 100, 1) : 0 }}%
                                </span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-chart">
                            <p class="text-muted">Nenhuma categoria com arquivos ainda.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Usuários por Papel -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Distribuição de Usuários</h3>
                    </div>
                    
                    <div class="role-distribution">
                        @php
                            $totalUsers = $stats['total_usuarios'];
                            $roles = [
                                'admin' => ['name' => 'Administradores', 'color' => 'var(--color-warning)', 'count' => $usuariosPorPapel['admin'] ?? 0],
                                'general_technician' => ['name' => 'Técnicos Gerais', 'color' => 'var(--color-success)', 'count' => $usuariosPorPapel['general_technician'] ?? 0],
                                'normal_technician' => ['name' => 'Técnicos Normais', 'color' => 'var(--color-info)', 'count' => $usuariosPorPapel['normal_technician'] ?? 0],
                            ];
                        @endphp

                        @foreach($roles as $roleKey => $role)
                        <div class="role-item">
                            <div class="role-indicator" data-color="{{ $role['color'] }}"></div>
                            <div class="role-info">
                                <div class="role-name">{{ $role['name'] }}</div>
                                <div class="role-count">{{ $role['count'] }} usuários</div>
                            </div>
                            <div class="role-percentage">
                                {{ $totalUsers > 0 ? number_format(($role['count'] / $totalUsers) * 100, 1) : 0 }}%
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico de Arquivos por Mês -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Evolução de Arquivos (Últimos 12 Meses)</h3>
            </div>
            
            @if($arquivosPorMes->count() > 0)
                <div class="month-chart">
                    @php
                        $maxFiles = $arquivosPorMes->max('count');
                    @endphp
                    
                    <div class="chart-container">
                        @foreach($arquivosPorMes as $mes)
                        @php
                            $monthName = DateTime::createFromFormat('!m', $mes->month)->format('M');
                            $height = $maxFiles > 0 ? ($mes->count / $maxFiles) * 100 : 0;
                        @endphp
                        <div class="chart-bar">
                            <div class="bar-value">{{ $mes->count }}</div>
                            <div class="bar" data-height="{{ $height }}"></div>
                            <div class="bar-label">{{ $monthName }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="empty-chart">
                    <p class="text-muted">Dados insuficientes para exibir o gráfico.</p>
                </div>
            @endif
        </div>

        <!-- Tabela Detalhada -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Resumo Detalhado</h3>
            </div>
            
            <div class="detailed-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Métrica</th>
                            <th>Valor Atual</th>
                            <th>Este Mês</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="metric-info">
                                    <strong>Usuários Ativos</strong>
                                    <small>Total de usuários registrados</small>
                                </div>
                            </td>
                            <td>
                                <span class="metric-value">{{ $stats['total_usuarios'] }}</span>
                            </td>
                            <td>
                                <span class="metric-change">-</span>
                            </td>
                            <td>
                                <span class="status-badge status-active">Ativo</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="metric-info">
                                    <strong>Categorias Criadas</strong>
                                    <small>Organização do sistema</small>
                                </div>
                            </td>
                            <td>
                                <span class="metric-value">{{ $stats['total_categorias'] }}</span>
                            </td>
                            <td>
                                <span class="metric-change">-</span>
                            </td>
                            <td>
                                <span class="status-badge status-active">Ativo</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="metric-info">
                                    <strong>Arquivos Totais</strong>
                                    <small>Documentos gerenciados</small>
                                </div>
                            </td>
                            <td>
                                <span class="metric-value">{{ $stats['total_arquivos'] }}</span>
                            </td>
                            <td>
                                <span class="metric-change positive">+{{ $stats['arquivos_mes'] }}</span>
                            </td>
                            <td>
                                <span class="status-badge status-growing">Crescendo</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="metric-info">
                                    <strong>Espaço em Disco</strong>
                                    <small>Utilização de armazenamento</small>
                                </div>
                            </td>
                            <td>
                                <span class="metric-value">{{ number_format($stats['tamanho_total'] / 1024 / 1024, 1) }} MB</span>
                            </td>
                            <td>
                                <span class="metric-change">-</span>
                            </td>
                            <td>
                                <span class="status-badge status-stable">Estável</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }
        
        .col-md-6 {
            flex: 1;
            min-width: 300px;
        }

        .stats-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--color-white);
            border: 1px solid var(--color-gray-200);
            border-radius: var(--radius-lg);
            padding: 24px;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .stat-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .stat-icon.bg-primary { background: var(--color-primary); }
        .stat-icon.bg-success { background: var(--color-success); }
        .stat-icon.bg-info { background: var(--color-info); }
        .stat-icon.bg-warning { background: var(--color-warning); }

        .stat-header h3 {
            font-size: 16px;
            font-weight: 600;
            color: var(--color-gray-700);
            margin: 0;
        }

        .stat-number {
            font-size: 36px;
            font-weight: 700;
            color: var(--color-gray-900);
            line-height: 1;
            margin-bottom: 8px;
        }

        .stat-change {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 14px;
            font-weight: 500;
        }

        .stat-change.positive {
            color: var(--color-success);
        }

        .stat-change.neutral {
            color: var(--color-gray-600);
        }

        .category-chart {
            padding: 0;
        }

        .category-item {
            display: grid;
            grid-template-columns: 1fr 100px 60px;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid var(--color-gray-200);
        }

        .category-item:last-child {
            border-bottom: none;
        }

        .category-info {
            display: flex;
            flex-direction: column;
        }

        .category-name {
            font-weight: 600;
            color: var(--color-gray-900);
        }

        .category-count {
            font-size: 12px;
            color: var(--color-gray-600);
        }

        .category-bar {
            height: 8px;
            background: var(--color-gray-200);
            border-radius: var(--radius-sm);
            overflow: hidden;
        }

        .category-progress {
            height: 100%;
            background: var(--color-primary);
            border-radius: var(--radius-sm);
            transition: width 0.3s ease;
        }

        .category-percentage {
            text-align: right;
            font-size: 12px;
            font-weight: 600;
            color: var(--color-gray-700);
        }

        .role-distribution {
            padding: 0;
        }

        .role-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 0;
            border-bottom: 1px solid var(--color-gray-200);
        }

        .role-item:last-child {
            border-bottom: none;
        }

        .role-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .role-info {
            flex: 1;
        }

        .role-name {
            font-weight: 600;
            color: var(--color-gray-900);
            margin-bottom: 2px;
        }

        .role-count {
            font-size: 12px;
            color: var(--color-gray-600);
        }

        .role-percentage {
            font-weight: 600;
            color: var(--color-gray-700);
        }

        .month-chart {
            padding: 20px 0;
        }

        .chart-container {
            display: flex;
            align-items: end;
            gap: 8px;
            height: 200px;
            padding: 0 20px;
        }

        .chart-bar {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100%;
        }

        .bar-value {
            font-size: 12px;
            font-weight: 600;
            color: var(--color-gray-700);
            margin-bottom: 8px;
            min-height: 16px;
        }

        .bar {
            width: 100%;
            background: var(--color-primary);
            border-radius: var(--radius-sm) var(--radius-sm) 0 0;
            min-height: 4px;
            transition: height 0.3s ease;
        }

        .bar-label {
            font-size: 12px;
            color: var(--color-gray-600);
            margin-top: 8px;
        }

        .empty-chart {
            padding: 40px 20px;
            text-align: center;
        }

        .detailed-table {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid var(--color-gray-200);
        }

        .table th {
            background: var(--color-gray-50);
            font-weight: 600;
            color: var(--color-gray-700);
            font-size: 14px;
        }

        .metric-info strong {
            display: block;
            color: var(--color-gray-900);
            margin-bottom: 2px;
        }

        .metric-info small {
            color: var(--color-gray-600);
            font-size: 12px;
        }

        .metric-value {
            font-size: 18px;
            font-weight: 600;
            color: var(--color-gray-900);
        }

        .metric-change {
            font-size: 14px;
            font-weight: 500;
        }

        .metric-change.positive {
            color: var(--color-success);
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 500;
        }

        .status-active {
            background: var(--color-success-light);
            color: var(--color-success);
        }

        .status-growing {
            background: var(--color-primary-light);
            color: var(--color-primary);
        }

        .status-stable {
            background: var(--color-gray-200);
            color: var(--color-gray-700);
        }

        @media print {
            .btn {
                display: none;
            }
            
            .fade-in {
                animation: none;
            }
            
            .card {
                box-shadow: none;
                border: 1px solid #ccc;
                break-inside: avoid;
            }
        }

        @media (max-width: 768px) {
            .stats-overview {
                grid-template-columns: 1fr;
            }
            
            .row {
                flex-direction: column;
            }
            
            .col-md-6 {
                min-width: 100%;
            }
            
            .chart-container {
                padding: 0 10px;
            }
        }
    </style>

    <script>
        // Animação dos gráficos quando a página carrega
        document.addEventListener('DOMContentLoaded', function() {
            // Apply dynamic styles from data attributes
            document.querySelectorAll('.category-progress[data-percentage]').forEach(bar => {
                bar.style.width = bar.dataset.percentage + '%';
            });
            
            document.querySelectorAll('.role-indicator[data-color]').forEach(indicator => {
                indicator.style.background = indicator.dataset.color;
            });
            
            document.querySelectorAll('.bar[data-height]').forEach(bar => {
                bar.style.height = bar.dataset.height + '%';
            });

            // Animar barras de categoria
            const categoryBars = document.querySelectorAll('.category-progress');
            categoryBars.forEach((bar, index) => {
                setTimeout(() => {
                    bar.style.transition = 'width 1s ease-out';
                }, index * 100);
            });

            // Animar gráfico mensal
            const monthBars = document.querySelectorAll('.bar');
            monthBars.forEach((bar, index) => {
                const height = bar.style.height;
                bar.style.height = '0%';
                setTimeout(() => {
                    bar.style.height = height;
                    bar.style.transition = 'height 0.8s ease-out';
                }, 500 + (index * 50));
            });

            // Contador animado para estatísticas
            const statNumbers = document.querySelectorAll('.stat-number');
            statNumbers.forEach(stat => {
                const finalValue = parseInt(stat.textContent);
                let currentValue = 0;
                const increment = finalValue / 30;
                
                const timer = setInterval(() => {
                    currentValue += increment;
                    if (currentValue >= finalValue) {
                        stat.textContent = finalValue;
                        clearInterval(timer);
                    } else {
                        stat.textContent = Math.floor(currentValue);
                    }
                }, 50);
            });
        });
    </script>
</x-app-layout> 