<x-app-layout>
    <div class="fade-in">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="card-title mb-1">Gestão de Arquivos</h1>
                <p class="text-muted">
                    @if(auth()->user()->isPersonalUser())
                        Meus arquivos pessoais
                    @elseif(auth()->user()->role === 'admin')
                        Todos os arquivos da {{ auth()->user()->empresa->nome }}
                    @elseif(auth()->user()->role === 'general_technician')
                        Arquivos de todas as categorias
                    @else
                        Meus arquivos em {{ auth()->user()->category->nome ?? 'categoria não atribuída' }}
                    @endif
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('files.create') }}" class="btn btn-primary">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                    Novo Arquivo
                </a>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card mb-4">
            <form method="GET" action="{{ route('files.index') }}" class="d-flex gap-3" style="flex-wrap: wrap;">
                <div class="form-group" style="min-width: 200px;">
                    <input type="text" name="search" class="form-input" placeholder="Buscar arquivos..." 
                           value="{{ request('search') }}">
                </div>

                @if(!auth()->user()->isPersonalUser() && auth()->user()->role !== 'normal_technician')
                <div class="form-group" style="min-width: 150px;">
                    <select name="category_id" class="form-select" id="category_filter">
                        <option value="">Todas as categorias</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                @if(!auth()->user()->isPersonalUser())
                <div class="form-group" style="min-width: 150px;">
                    <select name="subcategory_id" class="form-select" id="subcategory_filter">
                        <option value="">Todas as subcategorias</option>
                        @foreach($subcategories as $subcategory)
                            <option value="{{ $subcategory->id }}" {{ request('subcategory_id') == $subcategory->id ? 'selected' : '' }}>
                                {{ $subcategory->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="form-group" style="min-width: 120px;">
                    <input type="date" name="date_from" class="form-input" 
                           value="{{ request('date_from') }}" placeholder="Data inicial">
                </div>

                <div class="form-group" style="min-width: 120px;">
                    <input type="date" name="date_to" class="form-input" 
                           value="{{ request('date_to') }}" placeholder="Data final">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                        </svg>
                        Filtrar
                    </button>
                    <a href="{{ route('files.index') }}" class="btn btn-secondary">Limpar</a>
                </div>
            </form>
        </div>

        <!-- Lista de Arquivos -->
        <div class="card">
            @if($files->count() > 0)
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Arquivo</th>
                                @if(auth()->user()->role === 'admin')
                                    <th>Usuário</th>
                                @endif
                                @if(!auth()->user()->isPersonalUser() && auth()->user()->role !== 'normal_technician')
                                    <th>Categoria</th>
                                @endif
                                @if(!auth()->user()->isPersonalUser())
                                    <th>Subcategoria</th>
                                @endif
                                <th>Data</th>
                                <th>Tamanho</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($files as $file)
                            <tr>
                                <td>
                                    <div>
                                        <div class="font-weight-600">{{ $file->titulo }}</div>
                                        <div class="text-muted text-sm">{{ $file->original_name }}</div>
                                        @if($file->descricao)
                                            <div class="text-muted text-sm mt-1">{{ Str::limit($file->descricao, 80) }}</div>
                                        @endif
                                    </div>
                                </td>
                                
                                @if(auth()->user()->role === 'admin')
                                <td>
                                    <div class="font-weight-600">{{ $file->user->name }}</div>
                                    <div class="text-muted text-sm">{{ ucfirst(str_replace('_', ' ', $file->user->role)) }}</div>
                                </td>
                                @endif

                                @if(!auth()->user()->isPersonalUser() && auth()->user()->role !== 'normal_technician')
                                <td>
                                    @if($file->category)
                                        <span class="badge" style="background-color: var(--color-primary); color: white;">
                                            {{ $file->category->nome }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                @endif

                                @if(!auth()->user()->isPersonalUser())
                                <td>
                                    @if($file->subcategory)
                                        <span class="badge bg-light text-dark">{{ $file->subcategory->nome }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                @endif

                                <td>{{ $file->created_at->format('d/m/Y H:i') }}</td>
                                
                                <td>{{ $file->formatted_size }}</td>

                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('files.show', $file) }}" class="btn btn-sm btn-secondary" title="Visualizar">
                                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                            </svg>
                                        </a>

                                        @if(auth()->user()->role === 'admin' || $file->user_id === auth()->user()->id)
                                            <!-- Admin ou dono do arquivo pode fazer download -->
                                            <a href="{{ route('files.download', $file) }}" class="btn btn-sm btn-primary" title="Download">
                                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                                    <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                                </svg>
                                            </a>
                                        @else
                                            <!-- Outros usuários podem apenas visualizar no navegador -->
                                            <a href="{{ route('files.view', $file) }}" class="btn btn-sm btn-info" title="Visualizar no Navegador">
                                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12H5.145zm.182 2.472a6.696 6.696 0 0 1-.597-.933A9.268 9.268 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM3.82 11a13.652 13.652 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5H3.82zm6.853 3.472A7.024 7.024 0 0 0 13.745 12H11.91a9.27 9.27 0 0 1-.64 1.539 6.688 6.688 0 0 1-.597.933zM8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855.173-.324.33-.682.468-1.068H8.5zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm2.802-3.5a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5h2.49zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7.024 7.024 0 0 0-3.072-2.472c.218.284.418.598.597.933zM10.855 4a7.966 7.966 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4h2.355z"/>
                                                </svg>
                                            </a>
                                        @endif

                                        @if($file->user_id === auth()->user()->id || auth()->user()->role === 'admin')
                                            <a href="{{ route('files.edit', $file) }}" class="btn btn-sm btn-warning" title="Editar">
                                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708L4.5 15.207a.5.5 0 0 1-.146.146l-3.5 2a.5.5 0 0 1-.708-.708l2-3.5a.5.5 0 0 1 .146-.146L13.646.854a.5.5 0 0 1 .5-.708zm-11.4 11.4L2.5 14.5 5 12.5l-3.854-3.854z"/>
                                                </svg>
                                            </a>

                                            <form method="POST" action="{{ route('files.destroy', $file) }}" class="inline" 
                                                  onsubmit="return confirm('Tem certeza que deseja excluir este arquivo?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Excluir">
                                                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84L13.962 3.5H14.5a.5.5 0 0 0 0-1h-1.004a.58.58 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                @if($files->hasPages())
                    <div class="pagination">
                        {{ $files->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <div class="text-center text-muted" style="padding: 60px;">
                    <svg width="64" height="64" fill="currentColor" viewBox="0 0 16 16" style="margin-bottom: 20px; opacity: 0.3;">
                        <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                    </svg>
                    <h3 style="margin-bottom: 12px;">Nenhum arquivo encontrado</h3>
                    <p class="text-muted mb-4">
                        @if(request()->hasAny(['search', 'category_id', 'subcategory_id', 'date_from', 'date_to']))
                            Tente ajustar os filtros ou limpar a busca.
                        @else
                            Envie seu primeiro arquivo para começar.
                        @endif
                    </p>
                    @if(!request()->hasAny(['search', 'category_id', 'subcategory_id', 'date_from', 'date_to']))
                        <a href="{{ route('files.create') }}" class="btn btn-primary">Enviar Primeiro Arquivo</a>
                    @else
                        <a href="{{ route('files.index') }}" class="btn btn-secondary">Limpar Filtros</a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <style>
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
        .inline {
            display: inline;
        }
        .mt-1 {
            margin-top: 0.25rem;
        }
    </style>

    <script>
        // Carregar subcategorias quando categoria for selecionada (apenas para usuários de empresa)
        const categoryFilter = document.getElementById('category_filter');
        if (categoryFilter) {
            categoryFilter.addEventListener('change', function() {
                const categoryId = this.value;
                const subcategorySelect = document.getElementById('subcategory_filter');
                
                if (subcategorySelect) {
                    if (categoryId) {
                        loadSubcategories(categoryId, subcategorySelect);
                    } else {
                        subcategorySelect.innerHTML = '<option value="">Todas as subcategorias</option>';
                    }
                }
            });
        }
    </script>
</x-app-layout> 