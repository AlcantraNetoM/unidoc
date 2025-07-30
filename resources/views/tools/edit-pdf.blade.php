<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Editar PDF - UNIDOC</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="{{ route('landing') }}" class="flex items-center">
                        <img src="{{ asset('logo.png') }}" alt="UNIDOC Logo" class="w-10 h-10 mr-3">
                        <h1 class="text-xl font-bold text-blue-600">UNIDOC</h1>
                    </a>
                    <span class="ml-4 text-gray-400">|</span>
                    <h2 class="ml-4 text-lg font-semibold text-gray-700">Editar PDF</h2>
                </div>
                <a href="{{ route('landing') }}" class="text-gray-600 hover:text-blue-600 transition-colors">
                    ← Voltar ao início
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Editar PDF</h1>
                <p class="text-gray-600">Faça modificações em seus arquivos PDF de forma simples e gratuita</p>
            </div>

            <!-- Upload Area -->
            <div x-data="pdfEditor()" class="space-y-6">
                <!-- File Upload -->
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors">
                    <input type="file" id="pdfFile" accept=".pdf" @change="handleFileSelect" class="hidden">
                    <label for="pdfFile" class="cursor-pointer">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Selecione um arquivo PDF</h3>
                        <p class="text-gray-500">Clique aqui ou arraste seu arquivo PDF (máx. 10MB)</p>
                    </label>
                </div>

                <!-- File Info -->
                <div x-show="selectedFile" class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-900" x-text="selectedFile?.name"></p>
                            <p class="text-sm text-gray-500" x-text="formatFileSize(selectedFile?.size)"></p>
                        </div>
                    </div>
                </div>

                <!-- Edit Options -->
                <div x-show="selectedFile" class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Opções de Edição</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="flex items-center p-3 bg-white rounded-lg border hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" x-model="editOptions.addText" class="mr-3 text-blue-600">
                            <div>
                                <div class="font-medium">Adicionar Texto</div>
                                <div class="text-sm text-gray-500">Inserir texto no documento</div>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-3 bg-white rounded-lg border hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" x-model="editOptions.watermark" class="mr-3 text-blue-600">
                            <div>
                                <div class="font-medium">Marca d'Água</div>
                                <div class="text-sm text-gray-500">Adicionar marca d'água</div>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-3 bg-white rounded-lg border hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" x-model="editOptions.stamp" class="mr-3 text-blue-600">
                            <div>
                                <div class="font-medium">Carimbo</div>
                                <div class="text-sm text-gray-500">Adicionar carimbo oficial</div>
                            </div>
                        </label>

                        <label class="flex items-center p-3 bg-white rounded-lg border hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" x-model="editOptions.annotation" class="mr-3 text-blue-600">
                            <div>
                                <div class="font-medium">Anotação</div>
                                <div class="text-sm text-gray-500">Adicionar anotação destacada</div>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-3 bg-white rounded-lg border hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" x-model="editOptions.highlight" class="mr-3 text-blue-600">
                            <div>
                                <div class="font-medium">Destacar Texto</div>
                                <div class="text-sm text-gray-500">Marcar texto importante</div>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-3 bg-white rounded-lg border hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" x-model="editOptions.removePages" class="mr-3 text-blue-600">
                            <div>
                                <div class="font-medium">Remover Páginas</div>
                                <div class="text-sm text-gray-500">Excluir páginas do documento</div>
                            </div>
                        </label>
                    </div>

                    <!-- Opções específicas -->
                    <div class="mt-4 space-y-4">
                        <!-- Texto a adicionar -->
                        <div x-show="editOptions.addText" class="bg-white p-4 rounded-lg border">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Texto a adicionar:</label>
                            <input type="text" x-model="textToAdd" placeholder="Digite o texto aqui..." 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <div class="flex gap-4 mt-2">
                                <div>
                                    <label class="block text-xs text-gray-500">Posição X:</label>
                                    <input type="number" x-model="textX" value="50" min="0" max="500" 
                                           class="w-20 px-2 py-1 text-sm border rounded">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500">Posição Y:</label>
                                    <input type="number" x-model="textY" value="50" min="0" max="700" 
                                           class="w-20 px-2 py-1 text-sm border rounded">
                                </div>
                            </div>
                        </div>

                        <!-- Anotação -->
                        <div x-show="editOptions.annotation" class="bg-white p-4 rounded-lg border">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Texto da anotação:</label>
                            <input type="text" x-model="annotationText" placeholder="Digite a anotação..." 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <div class="flex gap-4 mt-2">
                                <div>
                                    <label class="block text-xs text-gray-500">Posição X:</label>
                                    <input type="number" x-model="annotationX" value="20" min="0" max="500" 
                                           class="w-20 px-2 py-1 text-sm border rounded">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500">Posição Y:</label>
                                    <input type="number" x-model="annotationY" value="20" min="0" max="700" 
                                           class="w-20 px-2 py-1 text-sm border rounded">
                                </div>
                            </div>
                        </div>

                        <!-- Highlight -->
                        <div x-show="editOptions.highlight" class="bg-white p-4 rounded-lg border">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Área a destacar:</label>
                            <div class="grid grid-cols-4 gap-2">
                                <div>
                                    <label class="block text-xs text-gray-500">X:</label>
                                    <input type="number" x-model="highlightX" value="30" min="0" max="500" 
                                           class="w-full px-2 py-1 text-sm border rounded">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500">Y:</label>
                                    <input type="number" x-model="highlightY" value="70" min="0" max="700" 
                                           class="w-full px-2 py-1 text-sm border rounded">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500">Largura:</label>
                                    <input type="number" x-model="highlightW" value="100" min="10" max="400" 
                                           class="w-full px-2 py-1 text-sm border rounded">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500">Altura:</label>
                                    <input type="number" x-model="highlightH" value="10" min="5" max="50" 
                                           class="w-full px-2 py-1 text-sm border rounded">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Process Button -->
                <div x-show="selectedFile" class="text-center">
                    <button @click="processFile" :disabled="processing" 
                            class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!processing">Processar PDF</span>
                        <span x-show="processing" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processando...
                        </span>
                    </button>
                </div>

                <!-- Result -->
                <div x-show="result" class="mt-6 p-4 rounded-lg" :class="result?.success ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'">
                    <p class="font-medium" :class="result?.success ? 'text-green-800' : 'text-red-800'" x-text="result?.message"></p>
                    <div x-show="result?.success && result?.download_url" class="mt-3">
                        <a :href="result?.download_url" :download="result?.filename" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-4-4m4 4l4-4m-6 6h12a2 2 0 002-2v-9a2 2 0 00-2-2H6a2 2 0 00-2 2v9a2 2 0 002 2z"/>
                            </svg>
                            Baixar PDF Editado (<span x-text="result?.size"></span>)
                        </a>
                        <p class="text-sm text-gray-500 mt-1">Arquivo: <span x-text="result?.filename"></span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-6">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">100% Seguro</h3>
                <p class="text-gray-600">Seus arquivos são processados com segurança e excluídos automaticamente</p>
            </div>
            
            <div class="text-center p-6">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Rápido</h3>
                <p class="text-gray-600">Processamento rápido e eficiente de seus documentos PDF</p>
            </div>
            
            <div class="text-center p-6">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Gratuito</h3>
                <p class="text-gray-600">Use gratuitamente sem necessidade de registro ou login</p>
            </div>
        </div>
    </div>

    <script>
        function pdfEditor() {
            return {
                selectedFile: null,
                processing: false,
                result: null,
                editOptions: {
                    addText: false,
                    watermark: false,
                    stamp: false,
                    annotation: false,
                    highlight: false,
                    removePages: false
                },
                textToAdd: '',
                textX: 50,
                textY: 50,
                annotationText: '',
                annotationX: 20,
                annotationY: 20,
                highlightX: 30,
                highlightY: 70,
                highlightW: 100,
                highlightH: 10,

                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file && file.type === 'application/pdf') {
                        this.selectedFile = file;
                        this.result = null;
                    } else {
                        alert('Por favor, selecione um arquivo PDF válido.');
                    }
                },

                formatFileSize(bytes) {
                    if (!bytes) return '';
                    const units = ['B', 'KB', 'MB', 'GB'];
                    let size = bytes;
                    let unitIndex = 0;
                    while (size >= 1024 && unitIndex < units.length - 1) {
                        size /= 1024;
                        unitIndex++;
                    }
                    return `${size.toFixed(1)} ${units[unitIndex]}`;
                },

                async processFile() {
                    if (!this.selectedFile) return;

                    this.processing = true;
                    this.result = null;

                    const formData = new FormData();
                    formData.append('pdf_file', this.selectedFile);

                    // Adicionar opções de edição
                    Object.keys(this.editOptions).forEach(key => {
                        if (this.editOptions[key]) {
                            formData.append(key, '1');
                        }
                    });

                    // Adicionar dados específicos
                    if (this.editOptions.addText && this.textToAdd) {
                        formData.append('text', this.textToAdd);
                        formData.append('text_x', this.textX);
                        formData.append('text_y', this.textY);
                    }

                    if (this.editOptions.annotation && this.annotationText) {
                        formData.append('annotation_text', this.annotationText);
                        formData.append('annotation_x', this.annotationX);
                        formData.append('annotation_y', this.annotationY);
                    }

                    if (this.editOptions.highlight) {
                        formData.append('highlight_x', this.highlightX);
                        formData.append('highlight_y', this.highlightY);
                        formData.append('highlight_w', this.highlightW);
                        formData.append('highlight_h', this.highlightH);
                    }

                    try {
                        const response = await fetch('{{ route("tools.edit-pdf.process") }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        const data = await response.json();
                        this.result = data;

                        if (!data.success) {
                            console.error('Erro:', data.message);
                        }
                    } catch (error) {
                        console.error('Erro:', error);
                        this.result = {
                            success: false,
                            message: 'Erro ao processar o arquivo. Tente novamente.'
                        };
                    } finally {
                        this.processing = false;
                    }
                }
            }
        }
    </script>
</body>
</html>
