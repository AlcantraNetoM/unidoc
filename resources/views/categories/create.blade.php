<x-app-layout>
    <div class="fade-in">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="card-title mb-1">Nova Categoria</h1>
                <p class="text-muted">Crie uma categoria para organizar os arquivos</p>
            </div>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 6L8 2.5 4.5 6 7 6v4h2V6h1.5z"/>
                </svg>
                Voltar para Categorias
            </a>
        </div>

        <!-- Formulário -->
        <div class="card">
            <form method="POST" action="{{ route('categories.store') }}">
                @csrf

                <!-- Nome da Categoria -->
                <div class="form-group">
                    <label for="nome" class="form-label required">Nome da Categoria</label>
                    <input type="text" name="nome" id="nome" class="form-input @error('nome') error @enderror" 
                           value="{{ old('nome') }}" required autofocus placeholder="Ex: Engenharia, Recursos Humanos, Financeiro">
                    @error('nome')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <div class="help-text">
                        <svg width="14" height="14" fill="var(--color-primary)" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                        </svg>
                        O nome da categoria deve ser único dentro da sua empresa.
                    </div>
                </div>

                <!-- Subcategorias -->
                <div class="form-group">
                    <label class="form-label">Subcategorias <span class="text-muted">(opcional)</span></label>
                    <div class="subcategories-container">
                        <div class="subcategories-list" id="subcategories-list">
                            <!-- Subcategorias serão adicionadas aqui dinamicamente -->
                        </div>
                        
                        <div class="add-subcategory">
                            <div class="d-flex gap-2">
                                <input type="text" id="new-subcategory" class="form-input" 
                                       placeholder="Nome da subcategoria">
                                <button type="button" class="btn btn-primary" onclick="addSubcategory()">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                    </svg>
                                    Adicionar
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="help-text">
                        <svg width="14" height="14" fill="var(--color-primary)" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                        </svg>
                        Subcategorias ajudam a organizar melhor os arquivos dentro da categoria principal.
                    </div>
                </div>

                <!-- Botões -->
                <div class="d-flex gap-3 justify-content-end">
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3zm-8.322.12C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139z"/>
                        </svg>
                        Criar Categoria
                    </button>
                </div>
            </form>
        </div>

        <!-- Preview Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Preview da Categoria</h3>
            </div>
            <div class="category-preview">
                <div class="preview-category">
                    <div class="category-icon">
                        <svg width="24" height="24" fill="var(--color-primary)" viewBox="0 0 16 16">
                            <path d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3zm-8.322.12C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139z"/>
                        </svg>
                    </div>
                    <h4 id="preview-name">Nome da Categoria</h4>
                </div>
                <div class="preview-subcategories" id="preview-subcategories">
                    <p class="text-muted">Nenhuma subcategoria adicionada</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .required::after {
            content: ' *';
            color: var(--color-danger);
        }

        .help-text {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            color: var(--color-gray-600);
            margin-top: 6px;
        }

        .subcategories-container {
            border: 1px solid var(--color-gray-300);
            border-radius: var(--radius-md);
            padding: 16px;
            background: var(--color-gray-50);
        }

        .subcategories-list {
            margin-bottom: 16px;
        }

        .subcategory-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            background: var(--color-white);
            border: 1px solid var(--color-gray-200);
            border-radius: var(--radius-sm);
            margin-bottom: 8px;
        }

        .subcategory-item:last-child {
            margin-bottom: 0;
        }

        .subcategory-name {
            font-weight: 500;
            color: var(--color-gray-900);
        }

        .subcategory-remove {
            background: none;
            border: none;
            color: var(--color-danger);
            cursor: pointer;
            padding: 4px;
            border-radius: var(--radius-sm);
            transition: background-color 0.2s;
        }

        .subcategory-remove:hover {
            background: var(--color-danger-light);
        }

        .add-subcategory {
            padding-top: 12px;
            border-top: 1px solid var(--color-gray-200);
        }

        .category-preview {
            text-align: center;
            padding: 20px;
        }

        .preview-category {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .category-icon {
            width: 48px;
            height: 48px;
            background: var(--color-primary-light);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .preview-subcategories {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
        }

        .preview-subcategory {
            background: var(--color-primary);
            color: white;
            padding: 4px 12px;
            border-radius: var(--radius-sm);
            font-size: 14px;
        }

        .justify-content-end {
            justify-content: flex-end;
        }

        .empty-subcategories {
            text-align: center;
            padding: 20px;
            color: var(--color-gray-500);
            font-style: italic;
        }
    </style>

    <script>
        let subcategoryIndex = 0;

        function addSubcategory() {
            const input = document.getElementById('new-subcategory');
            const name = input.value.trim();
            
            if (!name) {
                alert('Por favor, digite o nome da subcategoria.');
                return;
            }

            // Verificar se já existe
            const existing = document.querySelectorAll('.subcategory-name');
            for (let item of existing) {
                if (item.textContent === name) {
                    alert('Esta subcategoria já foi adicionada.');
                    return;
                }
            }

            const list = document.getElementById('subcategories-list');
            const item = document.createElement('div');
            item.className = 'subcategory-item';
            item.innerHTML = `
                <span class="subcategory-name">${name}</span>
                <input type="hidden" name="subcategorias[]" value="${name}">
                <button type="button" class="subcategory-remove" onclick="removeSubcategory(this)">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                    </svg>
                </button>
            `;

            list.appendChild(item);
            input.value = '';
            updatePreview();
        }

        function removeSubcategory(button) {
            button.parentElement.remove();
            updatePreview();
        }

        function updatePreview() {
            const categoryName = document.getElementById('nome').value || 'Nome da Categoria';
            const subcategoryNames = Array.from(document.querySelectorAll('.subcategory-name')).map(el => el.textContent);
            
            // Atualizar nome da categoria
            document.getElementById('preview-name').textContent = categoryName;
            
            // Atualizar subcategorias
            const previewContainer = document.getElementById('preview-subcategories');
            if (subcategoryNames.length === 0) {
                previewContainer.innerHTML = '<p class="text-muted">Nenhuma subcategoria adicionada</p>';
            } else {
                previewContainer.innerHTML = subcategoryNames
                    .map(name => `<span class="preview-subcategory">${name}</span>`)
                    .join('');
            }
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            const nomeInput = document.getElementById('nome');
            const newSubcategoryInput = document.getElementById('new-subcategory');

            // Atualizar preview quando nome mudar
            nomeInput.addEventListener('input', updatePreview);

            // Adicionar subcategoria com Enter
            newSubcategoryInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    addSubcategory();
                }
            });

            // Validação do formulário
            document.querySelector('form').addEventListener('submit', function(e) {
                const nome = document.getElementById('nome').value.trim();
                if (!nome) {
                    e.preventDefault();
                    alert('O nome da categoria é obrigatório.');
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
                    Criando...
                `;
            });

            // Preview inicial
            updatePreview();
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