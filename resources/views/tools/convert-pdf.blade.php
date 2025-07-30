<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Converter PDF - UNIDOC</title>
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
                    <h2 class="ml-4 text-lg font-semibold text-gray-700">Converter PDF</h2>
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
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Converter PDF</h1>
                <p class="text-gray-600">Converta seus arquivos para PDF</p>
            </div>

            <!-- Converter -->
            <div x-data="pdfConverter()" class="space-y-6">
                <!-- File Upload -->
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors">
                    <input type="file" id="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.txt" @change="handleFileSelect" class="hidden">
                    <label for="file" class="cursor-pointer">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Selecione um arquivo</h3>
                        <p class="text-gray-500">PDF, DOC, DOCX, JPG, PNG, TXT (máx. 10MB)</p>
                    </label>
                </div>

                <!-- File Info -->
                <div x-show="selectedFile" class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-600 rounded flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900" x-text="selectedFile?.name"></p>
                                <p class="text-sm text-gray-500" x-text="formatFileSize(selectedFile?.size)"></p>
                            </div>
                        </div>
                        <button @click="clearSelection()" class="text-red-600 hover:text-red-800 p-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Convert Button -->
                <div x-show="selectedFile" class="text-center">
                    <button @click="convertFile" :disabled="processing" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!processing">Converter Arquivo</span>
                        <span x-show="processing" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Convertendo...
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
                            Baixar Arquivo Convertido (<span x-text="result?.size"></span>)
                        </a>
                        <p class="text-sm text-gray-500 mt-1">
                            Formato: <span x-text="result?.format?.toUpperCase()"></span> | 
                            Arquivo: <span x-text="result?.filename"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Supported Formats -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 text-center mb-8">Formatos Suportados</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-lg p-6 shadow-md">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Entrada</h3>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <span class="w-12 h-8 bg-red-100 text-red-800 text-xs font-bold rounded flex items-center justify-center mr-3">PDF</span>
                            <span class="text-gray-700">Portable Document Format</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-12 h-8 bg-blue-100 text-blue-800 text-xs font-bold rounded flex items-center justify-center mr-3">DOC</span>
                            <span class="text-gray-700">Microsoft Word</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-12 h-8 bg-green-100 text-green-800 text-xs font-bold rounded flex items-center justify-center mr-3">IMG</span>
                            <span class="text-gray-700">JPG, PNG</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-12 h-8 bg-yellow-100 text-yellow-800 text-xs font-bold rounded flex items-center justify-center mr-3">TXT</span>
                            <span class="text-gray-700">Texto simples</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg p-6 shadow-md">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Saída</h3>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <span class="w-12 h-8 bg-red-100 text-red-800 text-xs font-bold rounded flex items-center justify-center mr-3">PDF</span>
                            <span class="text-gray-700">Portable Document Format</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function pdfConverter() {
            return {
                selectedFile: null,
                processing: false,
                result: null,

                init() {
                    // Forçar limpeza completa na inicialização
                    this.forceReset();
                    
                    // Também limpar após um pequeno delay para garantir
                    setTimeout(() => {
                        this.forceReset();
                    }, 100);
                },

                forceReset() {
                    // Limpar todas as variáveis
                    this.selectedFile = null;
                    this.processing = false;
                    this.result = null;
                    
                    // Limpar input file
                    const fileInput = document.getElementById('file');
                    if (fileInput) {
                        fileInput.value = '';
                        fileInput.files = null;
                    }
                    
                    // Forçar refresh do Alpine.js
                    this.$nextTick(() => {
                        this.selectedFile = null;
                    });
                },

                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file && file.size > 0) {
                        this.selectedFile = file;
                        this.result = null;
                        this.processing = false;
                        console.log('Arquivo selecionado:', file.name, file.size);
                    } else {
                        this.clearSelection();
                    }
                },

                formatFileSize(bytes) {
                    if (!bytes || bytes === 0) return '';
                    const units = ['B', 'KB', 'MB', 'GB'];
                    let size = bytes;
                    let unitIndex = 0;
                    while (size >= 1024 && unitIndex < units.length - 1) {
                        size /= 1024;
                        unitIndex++;
                    }
                    return `${size.toFixed(1)} ${units[unitIndex]}`;
                },

                clearSelection() {
                    console.log('Limpando seleção...');
                    this.forceReset();
                },

                async convertFile() {
                    if (!this.selectedFile || this.selectedFile.size === 0) {
                        console.error('Nenhum arquivo válido selecionado');
                        return;
                    }

                    this.processing = true;
                    this.result = null;

                    const formData = new FormData();
                    formData.append('file', this.selectedFile);
                    formData.append('output_format', 'pdf');

                    try {
                        const response = await fetch('{{ route("tools.convert-pdf.process") }}', {
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
                            message: 'Erro ao converter o arquivo. Tente novamente.'
                        };
                    } finally {
                        this.processing = false;
                    }
                }
            }
        }
        
        // Múltiplas garantias de limpeza
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM carregado, limpando inputs...');
            const fileInput = document.getElementById('file');
            if (fileInput) {
                fileInput.value = '';
                fileInput.files = null;
            }
        });
        
        // Limpeza quando a página fica visível
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                console.log('Página visível, limpando inputs...');
                const fileInput = document.getElementById('file');
                if (fileInput) {
                    fileInput.value = '';
                    fileInput.files = null;
                }
            }
        });
        
        // Limpeza ao carregar a página
        window.addEventListener('load', function() {
            console.log('Página carregada, limpeza final...');
            const fileInput = document.getElementById('file');
            if (fileInput) {
                fileInput.value = '';
                fileInput.files = null;
            }
        });
    </script>
</body>
</html>
