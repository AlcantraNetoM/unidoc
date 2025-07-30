<x-app-layout>
    <div class="fade-in">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="card-title mb-1">{{ $file->titulo }}</h1>
                <p class="text-muted">Detalhes do arquivo</p>
            </div>
            <div class="d-flex gap-2">
                @if(auth()->user()->role === 'admin' || $file->user_id === auth()->user()->id)
                    <!-- Admin ou dono do arquivo pode fazer download -->
                    <a href="{{ route('files.download', $file) }}" class="btn btn-primary">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                            <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                        </svg>
                        Download
                    </a>
                @else
                    <!-- Outros usuários podem apenas visualizar no navegador -->
                    <a href="{{ route('files.view', $file) }}" class="btn btn-info">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12H5.145zm.182 2.472a6.696 6.696 0 0 1-.597-.933A9.268 9.268 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM3.82 11a13.652 13.652 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5H3.82zm6.853 3.472A7.024 7.024 0 0 0 13.745 12H11.91a9.27 9.27 0 0 1-.64 1.539 6.688 6.688 0 0 1-.597.933zM8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855.173-.324.33-.682.468-1.068H8.5zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm2.802-3.5a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5h2.49zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7.024 7.024 0 0 0-3.072-2.472c.218.284.418.598.597.933zM10.855 4a7.966 7.966 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4h2.355z"/>
                        </svg>
                        Visualizar no Navegador
                    </a>
                @endif
                @if($file->user_id === auth()->user()->id || auth()->user()->role === 'admin')
                    <a href="{{ route('files.edit', $file) }}" class="btn btn-warning">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708L4.5 15.207a.5.5 0 0 1-.146.146l-3.5 2a.5.5 0 0 1-.708-.708l2-3.5a.5.5 0 0 1 .146-.146L13.646.854a.5.5 0 0 1 .5-.708zm-11.4 11.4L2.5 14.5 5 12.5l-3.854-3.854z"/>
                        </svg>
                        Editar
                    </a>
                @endif
                <a href="{{ route('files.index') }}" class="btn btn-secondary">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 6L8 2.5 4.5 6 7 6v4h2V6h1.5z"/>
                    </svg>
                    Voltar
                </a>
            </div>
        </div>

        <div class="d-flex gap-4">
            <!-- Informações Principais -->
            <div class="card" style="flex: 2;">
                <div class="card-header">
                    <h3 class="card-title">Informações do Arquivo</h3>
                </div>

                <div class="file-info">
                    <!-- Ícone do tipo de arquivo -->
                    <div class="file-icon">
                        @php
                            $extension = strtolower(pathinfo($file->original_name, PATHINFO_EXTENSION));
                            $iconColor = match($extension) {
                                'pdf' => '#dc3545',
                                'doc', 'docx' => '#007bff',
                                'xls', 'xlsx' => '#28a745',
                                'jpg', 'jpeg', 'png', 'gif' => '#fd7e14',
                                'zip', 'rar' => '#6f42c1',
                                default => '#6c757d'
                            };
                        @endphp
                        
                        @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                            <svg width="48" height="48" fill="{{ $iconColor }}" viewBox="0 0 16 16">
                                <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
                            </svg>
                        @elseif($extension === 'pdf')
                            <svg width="48" height="48" fill="{{ $iconColor }}" viewBox="0 0 16 16">
                                <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                                <path d="M4.603 12.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.701 19.701 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.187-.012.395-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.065.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.716 5.716 0 0 1-.911-.95 11.642 11.642 0 0 0-1.997.406 11.311 11.311 0 0 1-1.021 1.51c-.29.35-.608.655-.926.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.27.27 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.647 12.647 0 0 1 1.01-.193 11.666 11.666 0 0 1-.51-.858 20.741 20.741 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.881 3.881 0 0 0-.612-.053zM8.078 5.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.089.346z"/>
                            </svg>
                        @else
                            <svg width="48" height="48" fill="{{ $iconColor }}" viewBox="0 0 16 16">
                                <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                            </svg>
                        @endif
                    </div>

                    <div class="file-details">
                        <div class="detail-row">
                            <span class="detail-label">Nome original:</span>
                            <span class="detail-value">{{ $file->original_name }}</span>
                        </div>

                        <div class="detail-row">
                            <span class="detail-label">Tamanho:</span>
                            <span class="detail-value">{{ $file->formatted_size }}</span>
                        </div>

                        <div class="detail-row">
                            <span class="detail-label">Tipo:</span>
                            <span class="detail-value">{{ $file->mime_type }}</span>
                        </div>

                        <div class="detail-row">
                            <span class="detail-label">Enviado por:</span>
                            <span class="detail-value">
                                {{ $file->user->name }}
                                <span class="badge bg-light text-dark">{{ ucfirst(str_replace('_', ' ', $file->user->role)) }}</span>
                            </span>
                        </div>

                        <div class="detail-row">
                            <span class="detail-label">Data de envio:</span>
                            <span class="detail-value">{{ $file->created_at->format('d/m/Y H:i:s') }}</span>
                        </div>

                        @if($file->updated_at != $file->created_at)
                        <div class="detail-row">
                            <span class="detail-label">Última modificação:</span>
                            <span class="detail-value">{{ $file->updated_at->format('d/m/Y H:i:s') }}</span>
                        </div>
                        @endif

                        @if($file->descricao)
                        <div class="detail-row">
                            <span class="detail-label">Descrição:</span>
                            <span class="detail-value">{{ $file->descricao }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Categorização -->
            <div class="card" style="flex: 1;">
                <div class="card-header">
                    <h3 class="card-title">Categorização</h3>
                </div>

                <div class="category-info">
                    <div class="category-item">
                        <div class="category-label">Categoria</div>
                        @if($file->category)
                        <div class="category-badge" style="background-color: var(--color-primary); color: white;">
                            {{ $file->category->nome }}
                        </div>
                        @else
                        <div class="text-muted">Não categorizado</div>
                        @endif
                    </div>

                    @if($file->subcategory)
                    <div class="category-item">
                        <div class="category-label">Subcategoria</div>
                        <div class="category-badge bg-light text-dark">
                            {{ $file->subcategory->nome }}
                        </div>
                    </div>
                    @else
                    <div class="category-item">
                        <div class="category-label">Subcategoria</div>
                        <div class="text-muted">Não categorizado</div>
                    </div>
                    @endif

                    @if($file->empresa)
                <div class="category-item">
                        <div class="category-label">Empresa</div>
                        <div class="d-flex align-items-center gap-2">
                            @if($file->empresa->logo_path)
                                <img src="{{ $file->empresa->logo_url }}" alt="{{ $file->empresa->nome }}" 
                                     style="width: 24px; height: 24px; border-radius: 4px; object-fit: cover;">
                            @endif
                            <span>{{ $file->empresa->nome }}</span>
                        </div>
                    </div>
                @endif
                </div>

                <!-- Ações de gerenciamento -->
                @if($file->user_id === auth()->user()->id || auth()->user()->role === 'admin')
                <div class="card-section">
                    <h4 style="font-size: 16px; margin-bottom: 12px;">Ações</h4>
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('files.edit', $file) }}" class="btn btn-sm btn-warning">
                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708L4.5 15.207a.5.5 0 0 1-.146.146l-3.5 2a.5.5 0 0 1-.708-.708l2-3.5a.5.5 0 0 1 .146-.146L13.646.854a.5.5 0 0 1 .5-.708zm-11.4 11.4L2.5 14.5 5 12.5l-3.854-3.854z"/>
                            </svg>
                            Editar Informações
                        </a>

                        <form method="POST" action="{{ route('files.destroy', $file) }}" 
                              onsubmit="return confirm('Tem certeza que deseja excluir este arquivo? Esta ação não pode ser desfeita.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger w-full">
                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84L13.962 3.5H14.5a.5.5 0 0 0 0-1h-1.004a.58.58 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                                </svg>
                                Excluir Arquivo
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Preview do arquivo (se aplicável) -->
        @if(in_array(strtolower(pathinfo($file->original_name, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Preview</h3>
            </div>
            <div class="text-center" style="padding: 20px;">
                <img src="{{ route('files.view', $file) }}" alt="{{ $file->titulo }}" 
                     style="max-width: 100%; max-height: 500px; border-radius: var(--radius-md); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            </div>
        </div>
        @endif
    </div>

    <style>
        .file-info {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        .file-icon {
            flex-shrink: 0;
            padding: 20px;
            background: var(--color-gray-50);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .file-details {
            flex: 1;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--color-gray-200);
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: var(--color-gray-700);
            min-width: 140px;
        }

        .detail-value {
            color: var(--color-gray-900);
            text-align: right;
            flex: 1;
        }

        .category-info {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .category-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .category-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--color-gray-600);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .category-badge {
            display: inline-block;
            padding: 8px 12px;
            border-radius: var(--radius-md);
            font-weight: 500;
            text-align: center;
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

        .card-section {
            border-top: 1px solid var(--color-gray-200);
            padding-top: 20px;
            margin-top: 20px;
        }

        .w-full {
            width: 100%;
        }

        .flex-column {
            flex-direction: column;
        }
    </style>
</x-app-layout> 