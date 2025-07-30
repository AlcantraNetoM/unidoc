<x-app-layout>
    <div class="fade-in">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="card-title mb-1">Configurações da Empresa</h1>
                <p class="text-muted">Gerencie os dados e configurações da sua empresa</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                        <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/>
                        <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                    </svg>
                    Usuários
                </a>
                <a href="{{ route('empresa.reports') }}" class="btn btn-secondary">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1H1z"/>
                        <path d="M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                    </svg>
                    Relatórios
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Estatísticas -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Resumo da Empresa</h3>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon bg-primary">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                    <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/>
                                    <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                                </svg>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $stats['total_usuarios'] }}</div>
                                <div class="stat-label">Usuários</div>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon bg-success">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3zm-8.322.12C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139z"/>
                                </svg>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $stats['total_categorias'] }}</div>
                                <div class="stat-label">Categorias</div>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon bg-info">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                                </svg>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $stats['total_arquivos'] }}</div>
                                <div class="stat-label">Arquivos</div>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon bg-warning">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                                </svg>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $stats['admins'] }}</div>
                                <div class="stat-label">Administradores</div>
                            </div>
                        </div>
                    </div>

                    <div class="users-breakdown">
                        <h4>Distribuição de Usuários</h4>
                        <div class="breakdown-item">
                            <span class="breakdown-label">Administradores</span>
                            <span class="breakdown-value">{{ $stats['admins'] }}</span>
                        </div>
                        <div class="breakdown-item">
                            <span class="breakdown-label">Técnicos Gerais</span>
                            <span class="breakdown-value">{{ $stats['general_technicians'] }}</span>
                        </div>
                        <div class="breakdown-item">
                            <span class="breakdown-label">Técnicos Normais</span>
                            <span class="breakdown-value">{{ $stats['normal_technicians'] }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Informações de Pagamento -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-credit-card mr-2"></i>
                            Informações de Pagamento
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="stat-card">
                                    <div class="stat-content">
                                        <div class="stat-label">Status da Conta</div>
                                        <div class="stat-number">
                                            @if($paymentInfo['account_status'] === 'active')
                                                <span class="badge bg-success">Ativa</span>
                                            @elseif($paymentInfo['account_status'] === 'trial')
                                                <span class="badge bg-info">Período de Teste</span>
                                            @elseif($paymentInfo['account_status'] === 'suspended')
                                                <span class="badge bg-danger">Suspensa</span>
                                            @else
                                                <span class="badge bg-warning">{{ ucfirst($paymentInfo['account_status']) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="stat-card">
                                    <div class="stat-content">
                                        <div class="stat-label">Meses Pagos</div>
                                        <div class="stat-number">{{ $paymentInfo['total_months_paid'] }} mês(es)</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if($paymentInfo['subscription_end_date'])
                        <div class="mt-3">
                            <div class="stat-card">
                                <div class="stat-content">
                                    <div class="stat-label">Assinatura Expira em</div>
                                    <div class="stat-number">{{ $paymentInfo['subscription_end_date']->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="mt-4">
                            <a href="{{ route('payments.multi_month') }}" class="btn btn-primary">
                                <i class="fas fa-plus mr-2"></i>
                                Realizar Pagamento Multi-mês
                            </a>
                            <a href="{{ route('payments.status') }}" class="btn btn-secondary ml-2">
                                <i class="fas fa-list mr-2"></i>
                                Ver Histórico de Pagamentos
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulário de Configurações -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Dados da Empresa</h3>
                    </div>

                    <form method="POST" action="{{ route('empresa.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Nome da Empresa -->
                        <div class="form-group">
                            <label for="nome" class="form-label required">Nome da Empresa</label>
                            <input type="text" name="nome" id="nome" class="form-input @error('nome') error @enderror" 
                                   value="{{ old('nome', $empresa->nome) }}" required>
                            @error('nome')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email da Empresa -->
                        <div class="form-group">
                            <label for="email" class="form-label required">Email da Empresa</label>
                            <input type="email" name="email" id="email" class="form-input @error('email') error @enderror" 
                                   value="{{ old('email', $empresa->email) }}" required>
                            @error('email')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Endereço -->
                        <div class="form-group">
                            <label for="endereco" class="form-label required">Endereço</label>
                            <textarea name="endereco" id="endereco" class="form-textarea @error('endereco') error @enderror" 
                                      rows="3" required>{{ old('endereco', $empresa->endereco) }}</textarea>
                            @error('endereco')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Logo da Empresa -->
                        <div class="form-group">
                            <label class="form-label">Logo da Empresa</label>
                            
                            @if($empresa->logo_path)
                                <div class="current-logo mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ Storage::url($empresa->logo_path) }}" 
                                             alt="Logo da {{ $empresa->nome }}" 
                                             class="logo-preview">
                                        <div>
                                            <p class="mb-1 font-weight-medium">Logo atual</p>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="removeLogo()">
                                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84L13.962 3.5H14.5a.5.5 0 0 0 0-1h-1.004a.58.58 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                                                </svg>
                                                Remover
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="file-upload @error('logo') error @enderror" id="logo-upload-area">
                                <input type="file" name="logo" id="logo" class="file-input" 
                                       accept="image/jpeg,image/png,image/jpg,image/gif">
                                <div class="file-upload-content">
                                    <svg width="32" height="32" fill="var(--color-primary)" viewBox="0 0 16 16" style="margin-bottom: 8px; opacity: 0.5;">
                                        <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                        <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
                                    </svg>
                                    <p class="file-upload-text">
                                        @if($empresa->logo_path)
                                            Clique para alterar o logo
                                        @else
                                            Clique para adicionar logo
                                        @endif
                                    </p>
                                    <p class="text-muted text-sm">JPG, PNG, GIF - Máximo 2MB</p>
                                </div>
                            </div>
                            @error('logo')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Cores da Empresa -->
                        <div class="form-group">
                            <label for="primary_color" class="form-label">Cor Primária</label>
                            <input type="color" name="primary_color" id="primary_color" class="form-input" value="{{ old('primary_color', $empresa->primary_color ?? '#2563eb') }}">
                        </div>
                        <div class="form-group">
                            <label for="secondary_color" class="form-label">Cor Secundária</label>
                            <input type="color" name="secondary_color" id="secondary_color" class="form-input" value="{{ old('secondary_color', $empresa->secondary_color ?? '#64748b') }}">
                        </div>

                        <!-- Botões -->
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708L4.5 15.207a.5.5 0 0 1-.146.146l-3.5 2a.5.5 0 0 1-.708-.708l2-3.5a.5.5 0 0 1 .146-.146L13.646.854a.5.5 0 0 1 .5-.708zm-11.4 11.4L2.5 14.5 5 12.5l-3.854-3.854z"/>
                                </svg>
                                Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Hidden form for removing logo -->
                <form id="removeLogo" method="POST" action="{{ route('empresa.remove-logo') }}" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>

                <!-- Informações Adicionais -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informações do Sistema</h3>
                    </div>
                    
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Empresa registrada em:</span>
                            <span class="info-value">{{ $empresa->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        
                        @if($empresa->updated_at != $empresa->created_at)
                        <div class="info-item">
                            <span class="info-label">Última atualização:</span>
                            <span class="info-value">{{ $empresa->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @endif
                        
                        <div class="info-item">
                            <span class="info-label">ID da empresa:</span>
                            <span class="info-value">#{{ str_pad($empresa->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Status:</span>
                            <span class="info-value">
                                <span class="badge badge-success">Ativa</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .col-md-4 {
            flex: 0 0 320px;
        }
        
        .col-md-8 {
            flex: 1;
            min-width: 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            background: var(--color-gray-50);
            border-radius: var(--radius-md);
            border: 1px solid var(--color-gray-200);
        }

        .stat-icon {
            width: 40px;
            height: 40px;
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

        .stat-content {
            flex: 1;
        }

        .stat-number {
            font-size: 24px;
            font-weight: 700;
            color: var(--color-gray-900);
            line-height: 1;
        }

        .stat-label {
            font-size: 12px;
            color: var(--color-gray-600);
            margin-top: 2px;
        }

        .users-breakdown {
            padding-top: 20px;
            border-top: 1px solid var(--color-gray-200);
        }

        .users-breakdown h4 {
            font-size: 16px;
            font-weight: 600;
            color: var(--color-gray-900);
            margin-bottom: 16px;
        }

        .breakdown-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid var(--color-gray-200);
        }

        .breakdown-item:last-child {
            border-bottom: none;
        }

        .breakdown-label {
            font-size: 14px;
            color: var(--color-gray-700);
        }

        .breakdown-value {
            font-weight: 600;
            color: var(--color-gray-900);
        }

        .current-logo {
            padding: 16px;
            background: var(--color-gray-50);
            border-radius: var(--radius-md);
            border: 1px solid var(--color-gray-200);
        }

        .logo-preview {
            width: 64px;
            height: 64px;
            object-fit: cover;
            border-radius: var(--radius-md);
            border: 1px solid var(--color-gray-200);
        }

        .file-upload {
            border: 2px dashed var(--color-gray-300);
            border-radius: var(--radius-md);
            padding: 24px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            background: var(--color-gray-50);
        }

        .file-upload:hover {
            border-color: var(--color-primary);
            background: var(--color-primary-light);
        }

        .file-upload.error {
            border-color: var(--color-danger);
        }

        .file-input {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-upload-content {
            pointer-events: none;
        }

        .file-upload-text {
            margin: 0 0 4px 0;
            font-size: 14px;
            color: var(--color-gray-700);
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--color-gray-200);
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: var(--color-gray-700);
        }

        .info-value {
            color: var(--color-gray-900);
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            font-size: 12px;
            font-weight: 500;
            border-radius: var(--radius-sm);
        }

        .badge-success {
            background: var(--color-success-light);
            color: var(--color-success);
        }

        .required::after {
            content: ' *';
            color: var(--color-danger);
        }

        .justify-content-end {
            justify-content: flex-end;
        }

        @media (max-width: 768px) {
            .row {
                flex-direction: column;
            }
            
            .col-md-4,
            .col-md-8 {
                flex: none;
                width: 100%;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoInput = document.getElementById('logo');
            const logoUploadArea = document.getElementById('logo-upload-area');
            const logoUploadText = document.querySelector('.file-upload-text');

            // Atualizar texto quando logo for selecionado
            logoInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const fileName = this.files[0].name;
                    logoUploadText.textContent = `Logo selecionado: ${fileName}`;
                    logoUploadArea.style.borderColor = 'var(--color-success)';
                    logoUploadArea.style.backgroundColor = 'var(--color-success-light)';
                } else {
                    logoUploadText.textContent = '@if($empresa->logo_path) Clique para alterar o logo @else Clique para adicionar logo @endif';
                    logoUploadArea.style.borderColor = 'var(--color-gray-300)';
                    logoUploadArea.style.backgroundColor = 'var(--color-gray-50)';
                }
            });
        });

        // Function to remove logo
        function removeLogo() {
            if (confirm('Tem certeza que deseja remover o logo?')) {
                document.getElementById('removeLogo').submit();
            }
        }
    </script>

    <style>
        .rotating {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</x-app-layout> 