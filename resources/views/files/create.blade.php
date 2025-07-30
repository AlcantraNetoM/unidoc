<x-app-layout>
    <div class="fade-in">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="card-title mb-1">Enviar Novos Arquivos</h1>
                <p class="text-muted">Adicione um ou mais arquivos ao sistema</p>
            </div>
            <a href="{{ route('files.index') }}" class="btn btn-secondary">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 6L8 2.5 4.5 6 7 6v4h2V6h1.5z"/>
                </svg>
                Voltar para Arquivos
            </a>
        </div>

        <!-- Formulário -->
        <div class="card">
            <form method="POST" action="{{ route('files.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Informações do Arquivo -->
                <div class="form-group">
                    <label for="titulo" class="form-label required">Título do Arquivo</label>
                    <input type="text" name="titulo" id="titulo" class="form-input @error('titulo') error @enderror" 
                           value="{{ old('titulo') }}" required>
                    @error('titulo')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea name="descricao" id="descricao" class="form-textarea @error('descricao') error @enderror" 
                              rows="3" placeholder="Descrição opcional do arquivo">{{ old('descricao') }}</textarea>
                    @error('descricao')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                @if(!$isPersonalUser)
                <!-- Categoria e Subcategoria -->
                <div class="d-flex gap-3">
                    <div class="form-group" style="flex: 1;">
                        <label for="category_id" class="form-label required">Categoria</label>
                        <select name="category_id" id="category_id" class="form-select @error('category_id') error @enderror" required>
                            <option value="">Selecione uma categoria</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                        <select name="subcategory_id" id="subcategory_id" class="form-select @error('subcategory_id') error @enderror" disabled>
                            <option value="">Selecione uma subcategoria</option>
                        </select>
                        @error('subcategory_id')
                            <div class="error-message">{{ $errors->first('subcategory_id') }}</div>
                        @enderror
                    </div>
                </div>
                @endif

                <!-- Upload de Arquivos (Múltiplos) -->
                <div class="form-group">
                    <label for="files" class="form-label required">Arquivos</label>
                    <div class="file-upload @error('files') error @enderror @error('files.*') error @enderror" id="file-upload-area">
                        <input type="file" name="files[]" id="files" class="file-input" required multiple
                               @if($isPersonalUser)
                                   accept="*/*"
                               @else
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif,.zip,.rar"
                               @endif>
                        <div class="file-upload-content">
                            <svg width="48" height="48" fill="var(--color-primary)" viewBox="0 0 16 16" style="margin-bottom: 12px; opacity: 0.5;">
                                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/>
                            </svg>
                            <p class="file-upload-text">Arraste e solte seus arquivos aqui ou <strong>clique para selecionar</strong></p>
                            @if($isPersonalUser)
                                <p class="text-muted text-sm">Todos os tipos de arquivo são permitidos</p>
                            @else
                                <p class="text-muted text-sm">Tipos permitidos: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, JPG, PNG, GIF, ZIP, RAR</p>
                            @endif
                            <p class="text-muted text-sm">Tamanho máximo por arquivo: 10MB</p>
                        </div>
                    </div>
                    
                    <!-- Lista de arquivos selecionados -->
                    <div id="selected-files-list" class="selected-files-list" style="display: none;">
                        <h4 style="margin: 16px 0 8px 0; font-size: 14px; font-weight: 600;">Arquivos Selecionados:</h4>
                        <div id="files-container"></div>
                    </div>
                    
                    @error('files')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    @error('files.*')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Botões -->
                <div class="d-flex gap-3 justify-content-end">
                    <a href="{{ route('files.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                            <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/>
                        </svg>
                        Enviar Arquivos
                    </button>
                </div>
            </form>
        </div>

        @if(auth()->user()->role === 'normal_technician' && !auth()->user()->category_id)
        <!-- Aviso para técnico sem categoria -->
        <div class="card" style="border-left: 4px solid var(--color-warning); background: linear-gradient(135deg, #fff8e1 0%, #ffffff 100%);">
            <div class="d-flex align-items-center gap-3">
                <svg width="24" height="24" fill="var(--color-warning)" viewBox="0 0 16 16">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
                <div>
                    <h4 style="margin: 0; font-size: 16px; color: var(--color-gray-900);">Categoria Não Atribuída</h4>
                    <p style="margin: 4px 0 0 0; color: var(--color-gray-600); font-size: 14px;">
                        Você não pode enviar arquivos até que o administrador atribua uma categoria à sua conta.
                    </p>
                </div>
            </div>
        </div>
        @endif

        @if(auth()->user()->role === 'normal_technician' && auth()->user()->category_id)
        <!-- Informações para técnico normal -->
        <div class="card" style="border-left: 4px solid var(--color-primary); background: linear-gradient(135deg, #f0f9ff 0%, #ffffff 100%);">
            <div class="d-flex align-items-center gap-3">
                <svg width="24" height="24" fill="var(--color-primary)" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                </svg>
                <div>
                    <h4 style="margin: 0; font-size: 16px; color: var(--color-gray-900);">Categoria Atribuída</h4>
                    <p style="margin: 4px 0 0 0; color: var(--color-gray-600); font-size: 14px;">
                        Você pode enviar arquivos apenas para a categoria <strong>{{ auth()->user()->category->nome }}</strong>.
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <style>
        .required::after {
            content: ' *';
            color: var(--color-danger);
        }
        
        .file-upload {
            border: 2px dashed var(--color-gray-300);
            border-radius: var(--radius-md);
            padding: 40px;
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
        
        .file-upload.dragover {
            border-color: var(--color-primary);
            background: var(--color-primary-light);
            transform: scale(1.02);
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
            margin: 0 0 8px 0;
            font-size: 16px;
            color: var(--color-gray-700);
        }
        
        .file-selected .file-upload {
            border-color: var(--color-success);
            background: var(--color-success-light);
        }
        
        .selected-files-list {
            margin-top: 16px;
            padding: 16px;
            background: var(--color-gray-50);
            border-radius: var(--radius-md);
            border: 1px solid var(--color-gray-200);
        }
        
        .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 12px;
            margin-bottom: 8px;
            background: white;
            border: 1px solid var(--color-gray-200);
            border-radius: var(--radius-sm);
        }
        
        .file-item:last-child {
            margin-bottom: 0;
        }
        
        .file-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .file-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }
        
        .file-details {
            display: flex;
            flex-direction: column;
        }
        
        .file-name {
            font-size: 14px;
            font-weight: 500;
            color: var(--color-gray-900);
        }
        
        .file-size {
            font-size: 12px;
            color: var(--color-gray-600);
        }
        
        .remove-file {
            background: var(--color-danger);
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            line-height: 1;
        }
        
        .remove-file:hover {
            background: #dc2626;
        }
        
        .justify-content-end {
            justify-content: flex-end;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('files');
            const fileUploadArea = document.getElementById('file-upload-area');
            const fileUploadText = document.querySelector('.file-upload-text');
            const selectedFilesList = document.getElementById('selected-files-list');
            const filesContainer = document.getElementById('files-container');
            
            let selectedFiles = [];

            // Atualizar lista quando arquivos forem selecionados
            fileInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    selectedFiles = Array.from(this.files);
                    updateFilesList();
                    fileUploadArea.classList.add('file-selected');
                } else {
                    selectedFiles = [];
                    selectedFilesList.style.display = 'none';
                    fileUploadArea.classList.remove('file-selected');
                    fileUploadText.innerHTML = 'Arraste e solte seus arquivos aqui ou <strong>clique para selecionar</strong>';
                }
            });

            // Função para atualizar a lista de arquivos
            function updateFilesList() {
                if (selectedFiles.length === 0) {
                    selectedFilesList.style.display = 'none';
                    fileUploadText.innerHTML = 'Arraste e solte seus arquivos aqui ou <strong>clique para selecionar</strong>';
                    return;
                }

                selectedFilesList.style.display = 'block';
                filesContainer.innerHTML = '';

                const totalSize = selectedFiles.reduce((total, file) => total + file.size, 0);
                const totalSizeMB = (totalSize / 1024 / 1024).toFixed(2);
                
                fileUploadText.innerHTML = `<strong>${selectedFiles.length} arquivo(s) selecionado(s)</strong> (${totalSizeMB} MB total)`;

                selectedFiles.forEach((file, index) => {
                    const fileItem = createFileItem(file, index);
                    filesContainer.appendChild(fileItem);
                });
            }

            // Função para criar item de arquivo
            function createFileItem(file, index) {
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                const fileExtension = file.name.split('.').pop().toLowerCase();
                
                const fileItem = document.createElement('div');
                fileItem.className = 'file-item';
                
                const fileIcon = getFileIcon(fileExtension);
                
                fileItem.innerHTML = `
                    <div class="file-info">
                        <svg class="file-icon" fill="currentColor" viewBox="0 0 16 16">
                            ${fileIcon}
                        </svg>
                        <div class="file-details">
                            <span class="file-name">${file.name}</span>
                            <span class="file-size">${fileSize} MB</span>
                        </div>
                    </div>
                    <button type="button" class="remove-file" onclick="removeFile(${index})" title="Remover arquivo">
                        ×
                    </button>
                `;
                
                return fileItem;
            }

            // Função para obter ícone do arquivo baseado na extensão
            function getFileIcon(extension) {
                const icons = {
                    'pdf': '<path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/><path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.799.024.1.049.185.089.369z"/>',
                    'doc': '<path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>',
                    'docx': '<path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>',
                    'xls': '<path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>',
                    'xlsx': '<path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>',
                    'ppt': '<path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>',
                    'pptx': '<path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>',
                    'txt': '<path d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5z"/><path d="M9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5L9.5 0zm0 1v2A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/>',
                    'jpg': '<path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/><path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>',
                    'jpeg': '<path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/><path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>',
                    'png': '<path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/><path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>',
                    'gif': '<path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/><path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>',
                    'zip': '<path d="M6.5 7.5a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v.938l.4 1.599a1 1 0 0 1-.416 1.074l-.93.62a1 1 0 0 1-1.109 0l-.93-.62a1 1 0 0 1-.415-1.074l.4-1.599V7.5zm2 0h-1v.938a1 1 0 0 1-.03.243l-.4 1.598.93.62.93-.62-.4-1.598a1 1 0 0 1-.03-.243V7.5z"/><path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm5.5-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H7.5v1z"/>',
                    'rar': '<path d="M6.5 7.5a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v.938l.4 1.599a1 1 0 0 1-.416 1.074l-.93.62a1 1 0 0 1-1.109 0l-.93-.62a1 1 0 0 1-.415-1.074l.4-1.599V7.5zm2 0h-1v.938a1 1 0 0 1-.03.243l-.4 1.598.93.62.93-.62-.4-1.598a1 1 0 0 1-.03-.243V7.5z"/><path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm5.5-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H7.5v1z"/>'
                };
                return icons[extension] || '<path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>';
            }

            // Função global para remover arquivo
            window.removeFile = function(index) {
                selectedFiles.splice(index, 1);
                
                // Criar nova FileList
                const dt = new DataTransfer();
                selectedFiles.forEach(file => dt.items.add(file));
                fileInput.files = dt.files;
                
                updateFilesList();
                
                if (selectedFiles.length === 0) {
                    fileUploadArea.classList.remove('file-selected');
                }
            };

            // Drag and drop functionality
            fileUploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                fileUploadArea.classList.add('dragover');
            });

            fileUploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                fileUploadArea.classList.remove('dragover');
            });

            fileUploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                fileUploadArea.classList.remove('dragover');
                
                const files = Array.from(e.dataTransfer.files);
                selectedFiles = files;
                
                // Criar nova FileList
                const dt = new DataTransfer();
                selectedFiles.forEach(file => dt.items.add(file));
                fileInput.files = dt.files;
                
                updateFilesList();
                if (selectedFiles.length > 0) {
                    fileUploadArea.classList.add('file-selected');
                }
            });

            // Carregar subcategorias quando categoria mudar
            const categorySelect = document.getElementById('category_id');
            const subcategorySelect = document.getElementById('subcategory_id');

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

            // Validação adicional antes do envio
            document.querySelector('form').addEventListener('submit', function(e) {
                const fileInput = document.getElementById('files');
                if (!fileInput.files || fileInput.files.length === 0) {
                    e.preventDefault();
                    alert('Por favor, selecione pelo menos um arquivo.');
                    return false;
                }

                let hasOversizedFile = false;
                const maxSize = 10 * 1024 * 1024; // 10MB

                Array.from(fileInput.files).forEach(file => {
                    if (file.size > maxSize) {
                        hasOversizedFile = true;
                    }
                });

                if (hasOversizedFile) {
                    e.preventDefault();
                    alert('Um ou mais arquivos excedem o tamanho máximo de 10MB.');
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
                    Enviando...
                `;
            });
        });

        // Função global para carregar subcategorias
        window.loadSubcategories = function(categoryId, subcategorySelect) {
            fetch(`/api/categories/${categoryId}/subcategories`)
                .then(response => response.json())
                .then(subcategories => {
                    subcategorySelect.innerHTML = '<option value="">Selecione uma subcategoria</option>';
                    subcategories.forEach(subcategory => {
                        const option = document.createElement('option');
                        option.value = subcategory.id;
                        option.textContent = subcategory.nome;
                        subcategorySelect.appendChild(option);
                    });
                    subcategorySelect.disabled = false;
                })
                .catch(error => {
                    console.error('Erro ao carregar subcategorias:', error);
                    subcategorySelect.innerHTML = '<option value="">Erro ao carregar subcategorias</option>';
                });
        };
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