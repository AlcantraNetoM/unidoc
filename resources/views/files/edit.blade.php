<x-app-layout>
    <div class="fade-in">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="card-title mb-1">Editar Arquivo</h1>
                <p class="text-muted">Altere as informações do arquivo "{{ $file->titulo }}"</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('files.show', $file) }}" class="btn btn-secondary">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                    </svg>
                    Ver Arquivo
                </a>
                <a href="{{ route('files.index') }}" class="btn btn-secondary">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 6L8 2.5 4.5 6 7 6v4h2V6h1.5z"/>
                    </svg>
                    Voltar para Lista
                </a>
            </div>
        </div>

        <!-- Formulário -->
        <div class="card">
            <form method="POST" action="{{ route('files.update', $file) }}">
                @csrf
                @method('PUT')

                <!-- Informações do Arquivo -->
                <div class="form-group">
                    <label for="titulo" class="form-label required">Título do Arquivo</label>
                    <input type="text" name="titulo" id="titulo" class="form-input @error('titulo') error @enderror" 
                           value="{{ old('titulo', $file->titulo) }}" required>
                    @error('titulo')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea name="descricao" id="descricao" class="form-textarea @error('descricao') error @enderror" 
                              rows="4" placeholder="Descrição opcional do arquivo">{{ old('descricao', $file->descricao) }}</textarea>
                    @error('descricao')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Categoria e Subcategoria (apenas para usuários da empresa) -->
                @if(!$isPersonalUser)
                <div class="d-flex gap-3">
                    <div class="form-group" style="flex: 1;">
                        <label for="category_id" class="form-label required">Categoria</label>
                        <select name="category_id" id="category_id" class="form-select @error('category_id') error @enderror" required>
                            <option value="">Selecione uma categoria</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $file->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="error-message">{{ $errors->first('category_id') }}</div>
                        @enderror
                    </div>

                    <div class="form-group" style="flex: 1;">
                        <label for="subcategory_id" class="form-label">Subcategoria</label>
                        <select name="subcategory_id" id="subcategory_id" class="form-select @error('subcategory_id') error @enderror">
                            <option value="">Selecione uma subcategoria</option>
                            @if($subcategories)
                                @foreach($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}" {{ old('subcategory_id', $file->subcategory_id) == $subcategory->id ? 'selected' : '' }}>
                                        {{ $subcategory->nome }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('subcategory_id')
                            <div class="error-message">{{ $errors->first('subcategory_id') }}</div>
                        @enderror
                    </div>
                </div>
                @endif

                <!-- Informações do arquivo atual -->
                <div class="current-file-info">
                    <h3 style="font-size: 18px; margin-bottom: 16px; color: var(--color-gray-900);">
                        Arquivo Atual
                    </h3>
                    
                    <div class="file-preview">
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
                                <svg width="32" height="32" fill="{{ $iconColor }}" viewBox="0 0 16 16">
                                    <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                    <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
                                </svg>
                            @elseif($extension === 'pdf')
                                <svg width="32" height="32" fill="{{ $iconColor }}" viewBox="0 0 16 16">
                                    <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                                </svg>
                            @else
                                <svg width="32" height="32" fill="{{ $iconColor }}" viewBox="0 0 16 16">
                                    <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                                </svg>
                            @endif
                        </div>
                        
                        <div class="file-info">
                            <div class="file-name">{{ $file->original_name }}</div>
                            <div class="file-details">
                                <span class="file-size">{{ $file->formatted_size }}</span>
                                <span class="file-type">{{ strtoupper($extension) }}</span>
                                <span class="file-date">{{ $file->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                        
                        <div class="file-actions">
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

                    <div class="note">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <svg width="16" height="16" fill="var(--color-warning)" viewBox="0 0 16 16">
                                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                            </svg>
                            <strong style="color: var(--color-warning);">Nota</strong>
                        </div>
                        <p style="margin: 0; font-size: 14px; color: var(--color-gray-600);">
                            Esta operação edita apenas as informações do arquivo (título, descrição, categoria). 
                            O arquivo físico permanece o mesmo. Para substituir o arquivo, exclua este e envie um novo.
                        </p>
                    </div>
                </div>

                <!-- Botões -->
                <div class="d-flex gap-3 justify-content-end">
                    <a href="{{ route('files.show', $file) }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708L4.5 15.207a.5.5 0 0 1-.146.146l-3.5 2a.5.5 0 0 1-.708-.708l2-3.5a.5.5 0 0 1 .146-.146L13.646.854a.5.5 0 0 1 .5-.708zm-11.4 11.4L2.5 14.5 5 12.5l-3.854-3.854z"/>
                        </svg>
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>

        <!-- Informações adicionais -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informações de Controle</h3>
            </div>
            
            <div class="control-info">
                <div class="info-item">
                    <span class="info-label">Enviado por:</span>
                    <span class="info-value">
                        {{ $file->user->name }}
                        <span class="badge bg-light text-dark">{{ ucfirst(str_replace('_', ' ', $file->user->role)) }}</span>
                    </span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Data de envio:</span>
                    <span class="info-value">{{ $file->created_at->format('d/m/Y H:i:s') }}</span>
                </div>
                
                @if($file->updated_at != $file->created_at)
                <div class="info-item">
                    <span class="info-label">Última edição:</span>
                    <span class="info-value">{{ $file->updated_at->format('d/m/Y H:i:s') }}</span>
                </div>
                @endif
                
                @if(!$isPersonalUser && $file->empresa)
                <div class="info-item">
                    <span class="info-label">Empresa:</span>
                    <span class="info-value">
                        @if($file->empresa->logo_path)
                            <img src="{{ $file->empresa->logo_url }}" alt="{{ $file->empresa->nome }}" 
                                 style="width: 20px; height: 20px; border-radius: 4px; object-fit: cover; margin-right: 8px;">
                        @endif
                        {{ $file->empresa->nome }}
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .required::after {
            content: ' *';
            color: var(--color-danger);
        }

        .current-file-info {
            border-top: 1px solid var(--color-gray-200);
            padding-top: 20px;
            margin-top: 20px;
        }

        .file-preview {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: var(--color-gray-50);
            border-radius: var(--radius-md);
            margin-bottom: 16px;
        }

        .file-icon {
            flex-shrink: 0;
            padding: 12px;
            background: var(--color-white);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .file-info {
            flex: 1;
        }

        .file-name {
            font-weight: 600;
            color: var(--color-gray-900);
            margin-bottom: 4px;
        }

        .file-details {
            display: flex;
            gap: 12px;
            font-size: 14px;
            color: var(--color-gray-600);
        }

        .file-actions {
            flex-shrink: 0;
        }

        .note {
            padding: 16px;
            background: var(--color-warning-light);
            border: 1px solid var(--color-warning);
            border-radius: var(--radius-md);
            margin-bottom: 20px;
        }

        .control-info {
            display: flex;
            flex-direction: column;
            gap: 12px;
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
            min-width: 120px;
        }

        .info-value {
            color: var(--color-gray-900);
            text-align: right;
            flex: 1;
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

        .justify-content-end {
            justify-content: flex-end;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Carregar subcategorias quando categoria mudar (apenas para usuários da empresa)
            const categorySelect = document.getElementById('category_id');
            const subcategorySelect = document.getElementById('subcategory_id');

            if (categorySelect && subcategorySelect) {
                categorySelect.addEventListener('change', function() {
                    const categoryId = this.value;
                    subcategorySelect.innerHTML = '<option value="">Carregando...</option>';
                    subcategorySelect.disabled = true;

                    if (categoryId) {
                        loadSubcategories(categoryId, subcategorySelect);
                    } else {
                        subcategorySelect.innerHTML = '<option value="">Selecione uma subcategoria</option>';
                    }
                });

                // Se já tem categoria selecionada, carregar subcategorias
                const selectedCategoryId = categorySelect.value;
                if (selectedCategoryId) {
                    loadSubcategories(selectedCategoryId, subcategorySelect);
                }
            }

            // Validação antes do envio
            document.querySelector('form').addEventListener('submit', function(e) {
                const titulo = document.getElementById('titulo').value.trim();
                if (!titulo) {
                    e.preventDefault();
                    alert('O título do arquivo é obrigatório.');
                    return false;
                }

                // Mostrar loading no botão
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="rotating">
                        <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
                        <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
                    </svg>
                    Salvando...
                `;
            });
        });
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