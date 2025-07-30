<x-app-layout>
    <div class="fade-in">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="card-title mb-1">{{ $category->nome }}</h1>
                <p class="text-muted">Detalhes da categoria</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708L4.5 15.207a.5.5 0 0 1-.146.146l-3.5 2a.5.5 0 0 1-.708-.708l2-3.5a.5.5 0 0 1 .146-.146L13.646.854a.5.5 0 0 1 .5-.708zm-11.4 11.4L2.5 14.5 5 12.5l-3.854-3.854z"/>
                    </svg>
                    Editar
                </a>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 6L8 2.5 4.5 6 7 6v4h2V6h1.5z"/>
                    </svg>
                    Voltar para Categorias
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Informações da Categoria -->
            <div class="col-md-4">
                <div class="card">
                    <div class="category-header">
                        <div class="category-icon">
                            <svg width="32" height="32" fill="var(--color-primary)" viewBox="0 0 16 16">
                                <path d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3zm-8.322.12C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139z"/>
                            </svg>
                        </div>
                        <h3 class="category-title">{{ $category->nome }}</h3>
                    </div>

                    <div class="category-stats">
                        <div class="stat-item">
                            <span class="stat-number">{{ $category->files->count() }}</span>
                            <span class="stat-label">Arquivos</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ $category->subcategories->count() }}</span>
                            <span class="stat-label">Subcategorias</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ $category->users->count() }}</span>
                            <span class="stat-label">Usuários</span>
                        </div>
                    </div>

                    <div class="category-info">
                        <div class="info-item">
                            <span class="info-label">Criada em:</span>
                            <span class="info-value">{{ $category->created_at->format('d/m/Y') }}</span>
                        </div>
                        @if($category->updated_at != $category->created_at)
                        <div class="info-item">
                            <span class="info-label">Atualizada em:</span>
                            <span class="info-value">{{ $category->updated_at->format('d/m/Y') }}</span>
                        </div>
                        @endif
                        <div class="info-item">
                            <span class="info-label">Empresa:</span>
                            <span class="info-value">{{ $category->empresa->nome }}</span>
                        </div>
                    </div>

                    <div class="category-actions">
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning w-full">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708L4.5 15.207a.5.5 0 0 1-.146.146l-3.5 2a.5.5 0 0 1-.708-.708l2-3.5a.5.5 0 0 1 .146-.146L13.646.854a.5.5 0 0 1 .5-.708zm-11.4 11.4L2.5 14.5 5 12.5l-3.854-3.854z"/>
                            </svg>
                            Editar Categoria
                        </a>
                        
                        @if($category->files->count() == 0 && $category->users->count() == 0)
                        <form method="POST" action="{{ route('categories.destroy', $category) }}" 
                              onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?')" 
                              style="margin-top: 8px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-full">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84L13.962 3.5H14.5a.5.5 0 0 0 0-1h-1.004a.58.58 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                                </svg>
                                Excluir Categoria
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Subcategorias e Arquivos -->
            <div class="col-md-8">
                <!-- Subcategorias -->
                @if($category->subcategories->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Subcategorias</h3>
                    </div>
                    <div class="subcategories-grid">
                        @foreach($category->subcategories as $subcategory)
                        <div class="subcategory-card">
                            <div class="subcategory-header">
                                <h4 class="subcategory-name">{{ $subcategory->nome }}</h4>
                                <span class="subcategory-count">{{ $subcategory->files->count() }} arquivo(s)</span>
                            </div>
                            <div class="subcategory-actions">
                                <button class="btn btn-sm btn-secondary" onclick="filterBySubcategory('{{ $subcategory->id }}')">
                                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                    </svg>
                                    Filtrar
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Arquivos -->
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Arquivos da Categoria</h3>
                            <div class="d-flex gap-2">
                                <select id="subcategory-filter" class="form-select" style="width: 200px;">
                                    <option value="">Todas as subcategorias</option>
                                    @foreach($category->subcategories as $subcategory)
                                        <option value="{{ $subcategory->id }}">{{ $subcategory->nome }}</option>
                                    @endforeach
                                </select>
                                <a href="{{ route('files.create') }}" class="btn btn-primary">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                    </svg>
                                    Novo Arquivo
                                </a>
                            </div>
                        </div>
                    </div>

                    @if($files->count() > 0)
                        <div class="files-list">
                            @foreach($files as $file)
                            <div class="file-item" data-subcategory="{{ $file->subcategory_id }}">
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
                                        <svg width="24" height="24" fill="{{ $iconColor }}" viewBox="0 0 16 16">
                                            <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                            <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
                                        </svg>
                                    @elseif($extension === 'pdf')
                                        <svg width="24" height="24" fill="{{ $iconColor }}" viewBox="0 0 16 16">
                                            <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                                        </svg>
                                    @else
                                        <svg width="24" height="24" fill="{{ $iconColor }}" viewBox="0 0 16 16">
                                            <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                                        </svg>
                                    @endif
                                </div>

                                <div class="file-info">
                                    <h4 class="file-title">{{ $file->titulo }}</h4>
                                    <p class="file-name">{{ $file->original_name }}</p>
                                    @if($file->descricao)
                                        <p class="file-description">{{ Str::limit($file->descricao, 80) }}</p>
                                    @endif
                                    <div class="file-meta">
                                        @if($file->subcategory)
                                            <span class="file-subcategory">{{ $file->subcategory->nome }}</span>
                                        @endif
                                        <span class="file-size">{{ $file->formatted_size }}</span>
                                        <span class="file-date">{{ $file->created_at->format('d/m/Y') }}</span>
                                        <span class="file-user">{{ $file->user->name }}</span>
                                    </div>
                                </div>

                                <div class="file-actions">
                                    <a href="{{ route('files.show', $file) }}" class="btn btn-sm btn-secondary" title="Visualizar">
                                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                        </svg>
                                    </a>

                                    @if(auth()->user()->role === 'admin')
                                        <!-- Admin can download files -->
                                        <a href="{{ route('files.download', $file) }}" class="btn btn-sm btn-primary" title="Download">
                                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                                <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                            </svg>
                                        </a>
                                    @else
                                        <!-- Non-admin users can only view files in browser -->
                                        <a href="{{ route('files.view', $file) }}" class="btn btn-sm btn-info" title="Visualizar no Navegador">
                                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12H5.145zm.182 2.472a6.696 6.696 0 0 1-.597-.933A9.268 9.268 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM3.82 11a13.652 13.652 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5H3.82zm6.853 3.472A7.024 7.024 0 0 0 13.745 12H11.91a9.27 9.27 0 0 1-.64 1.539 6.688 6.688 0 0 1-.597.933zM8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855.173-.324.33-.682.468-1.068H8.5zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm2.802-3.5a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5h2.49zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7.024 7.024 0 0 0-3.072-2.472c.218.284.418.598.597.933zM10.855 4a7.966 7.966 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4h2.355z"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Paginação -->
                        @if($files->hasPages())
                            <div class="pagination">
                                {{ $files->links() }}
                            </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <svg width="64" height="64" fill="var(--color-gray-400)" viewBox="0 0 16 16" style="margin-bottom: 20px; opacity: 0.5;">
                                <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                            </svg>
                            <h3>Nenhum arquivo nesta categoria</h3>
                            <p class="text-muted">Esta categoria ainda não possui arquivos. Comece enviando seu primeiro arquivo.</p>
                            <a href="{{ route('files.create') }}" class="btn btn-primary">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                </svg>
                                Enviar Primeiro Arquivo
                            </a>
                        </div>
                    @endif
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
            flex: 0 0 300px;
        }
        
        .col-md-8 {
            flex: 1;
            min-width: 0;
        }

        .category-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }

        .category-icon {
            width: 64px;
            height: 64px;
            background: var(--color-primary-light);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .category-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--color-gray-900);
            margin: 0;
        }

        .category-stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 24px;
            padding: 20px;
            background: var(--color-gray-50);
            border-radius: var(--radius-md);
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            display: block;
            font-size: 28px;
            font-weight: 700;
            color: var(--color-primary);
            line-height: 1;
        }

        .stat-label {
            display: block;
            font-size: 12px;
            color: var(--color-gray-600);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
        }

        .category-info {
            margin-bottom: 24px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
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

        .category-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .w-full {
            width: 100%;
        }

        .subcategories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
            padding: 0;
        }

        .subcategory-card {
            padding: 16px;
            background: var(--color-gray-50);
            border-radius: var(--radius-md);
            border: 1px solid var(--color-gray-200);
            transition: all 0.2s ease;
        }

        .subcategory-card:hover {
            background: var(--color-white);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .subcategory-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .subcategory-name {
            font-weight: 600;
            color: var(--color-gray-900);
            margin: 0;
            font-size: 16px;
        }

        .subcategory-count {
            font-size: 12px;
            color: var(--color-gray-600);
            background: var(--color-white);
            padding: 2px 8px;
            border-radius: var(--radius-sm);
        }

        .files-list {
            display: flex;
            flex-direction: column;
            gap: 1px;
            background: var(--color-gray-200);
            border-radius: var(--radius-md);
            overflow: hidden;
        }

        .file-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: var(--color-white);
            transition: background-color 0.2s ease;
        }

        .file-item:hover {
            background: var(--color-gray-50);
        }

        .file-icon {
            flex-shrink: 0;
            width: 48px;
            height: 48px;
            background: var(--color-gray-50);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .file-info {
            flex: 1;
            min-width: 0;
        }

        .file-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--color-gray-900);
            margin: 0 0 4px 0;
        }

        .file-name {
            font-size: 14px;
            color: var(--color-gray-600);
            margin: 0 0 4px 0;
        }

        .file-description {
            font-size: 14px;
            color: var(--color-gray-600);
            margin: 0 0 8px 0;
        }

        .file-meta {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .file-meta > span {
            font-size: 12px;
            color: var(--color-gray-500);
        }

        .file-subcategory {
            background: var(--color-primary);
            color: white !important;
            padding: 2px 6px;
            border-radius: var(--radius-sm);
        }

        .file-actions {
            display: flex;
            gap: 8px;
            flex-shrink: 0;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state h3 {
            margin-bottom: 12px;
            color: var(--color-gray-700);
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
            
            .subcategories-grid {
                grid-template-columns: 1fr;
            }
            
            .file-item {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .file-actions {
                align-self: stretch;
                justify-content: center;
            }
        }
    </style>

    <script>
        function filterBySubcategory(subcategoryId) {
            const filter = document.getElementById('subcategory-filter');
            filter.value = subcategoryId;
            filterFiles();
        }

        function filterFiles() {
            const selectedSubcategory = document.getElementById('subcategory-filter').value;
            const fileItems = document.querySelectorAll('.file-item');

            fileItems.forEach(item => {
                const itemSubcategory = item.getAttribute('data-subcategory');
                
                if (!selectedSubcategory || itemSubcategory === selectedSubcategory) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const subcategoryFilter = document.getElementById('subcategory-filter');
            if (subcategoryFilter) {
                subcategoryFilter.addEventListener('change', filterFiles);
            }
        });
    </script>
</x-app-layout> 