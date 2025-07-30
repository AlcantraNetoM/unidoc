<x-app-layout>
    <div class="fade-in">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="card-title mb-1">Gestão de Categorias</h1>
                <p class="text-muted">Organize os arquivos da {{ auth()->user()->empresa->nome }}</p>
            </div>
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
                Nova Categoria
            </a>
        </div>

        <!-- Lista de Categorias -->
        @if($categories->count() > 0)
            <div class="categories-grid">
                @foreach($categories as $category)
                <div class="category-card">
                    <div class="category-header">
                        <div class="category-icon">
                            <svg width="24" height="24" fill="var(--color-primary)" viewBox="0 0 16 16">
                                <path d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3zm-8.322.12C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139z"/>
                            </svg>
                        </div>
                        <h3 class="category-title">{{ $category->nome }}</h3>
                    </div>

                    <div class="category-stats">
                        <div class="stat-item">
                            <span class="stat-number">{{ $category->files_count }}</span>
                            <span class="stat-label">Arquivos</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ $category->subcategories_count }}</span>
                            <span class="stat-label">Subcategorias</span>
                        </div>
                    </div>

                    <div class="category-actions">
                        <a href="{{ route('categories.show', $category) }}" class="btn btn-sm btn-secondary">
                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                            </svg>
                            Ver
                        </a>
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning">
                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708L4.5 15.207a.5.5 0 0 1-.146.146l-3.5 2a.5.5 0 0 1-.708-.708l2-3.5a.5.5 0 0 1 .146-.146L13.646.854a.5.5 0 0 1 .5-.708zm-11.4 11.4L2.5 14.5 5 12.5l-3.854-3.854z"/>
                            </svg>
                            Editar
                        </a>
                        <form method="POST" action="{{ route('categories.destroy', $category) }}" class="inline"
                              onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84L13.962 3.5H14.5a.5.5 0 0 0 0-1h-1.004a.58.58 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                                </svg>
                                Excluir
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Paginação -->
            @if($categories->hasPages())
                <div class="pagination">
                    {{ $categories->links() }}
                </div>
            @endif
        @else
            <div class="text-center" style="padding: 80px 20px;">
                <svg width="64" height="64" fill="var(--color-gray-400)" viewBox="0 0 16 16" style="margin-bottom: 20px; opacity: 0.5;">
                    <path d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3zm-8.322.12C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139z"/>
                </svg>
                <h3 style="margin-bottom: 12px; color: var(--color-gray-600);">Nenhuma categoria criada</h3>
                <p class="text-muted mb-4">
                    Crie categorias para organizar melhor os arquivos da sua empresa.
                </p>
                <a href="{{ route('categories.create') }}" class="btn btn-primary">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                    Criar Primeira Categoria
                </a>
            </div>
        @endif
    </div>

    <style>
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .category-card {
            background: var(--color-white);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--color-gray-200);
            transition: all 0.3s ease;
        }

        .category-card:hover {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .category-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .category-icon {
            flex-shrink: 0;
            width: 48px;
            height: 48px;
            background: var(--color-primary-light);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .category-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--color-gray-900);
            margin: 0;
        }

        .category-stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 16px;
            background: var(--color-gray-50);
            border-radius: var(--radius-md);
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            display: block;
            font-size: 24px;
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

        .category-actions {
            display: flex;
            gap: 8px;
        }

        .inline {
            display: inline;
        }

        @media (max-width: 768px) {
            .categories-grid {
                grid-template-columns: 1fr;
            }
            
            .category-actions {
                flex-direction: column;
            }
            
            .category-actions .btn {
                justify-content: center;
            }
        }
    </style>
</x-app-layout> 