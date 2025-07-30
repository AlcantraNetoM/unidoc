<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprimir PDF - UNIDOC</title>
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
                    <h2 class="ml-4 text-lg font-semibold text-gray-700">Comprimir PDF</h2>
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
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Comprimir PDF</h1>
                <p class="text-gray-600">Reduza o tamanho dos seus arquivos PDF mantendo a qualidade</p>
            </div>

            <!-- PDF Compressor -->
            <div x-data="pdfCompressor()" class="space-y-6">
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
                        <p class="text-gray-500">Clique aqui ou arraste seu arquivo PDF (máx. 50MB)</p>
                    </label>
                </div>

                <!-- File Info -->
                <div x-show="selectedFile" class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <div>
                                <p class="font-medium text-gray-900" x-text="selectedFile?.name"></p>
                                <p class="text-sm text-gray-500" x-text="formatFileSize(selectedFile?.size)"></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Tamanho original</p>
                            <p class="font-bold text-lg text-gray-900" x-text="formatFileSize(selectedFile?.size)"></p>
                        </div>
                    </div>
                </div>

                <!-- Compression Level -->
                <div x-show="selectedFile" class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Nível de Compressão</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="flex flex-col p-4 bg-white rounded-lg border cursor-pointer hover:bg-gray-50 transition-colors"
                               :class="compressionLevel === 'low' ? 'border-orange-500 bg-orange-50' : 'border-gray-200'">
                            <input type="radio" value="low" x-model="compressionLevel" class="sr-only">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-gray-900">Baixa</span>
                                <span class="text-sm text-gray-500">~20% menor</span>
                            </div>
                            <p class="text-sm text-gray-600">Melhor qualidade, menor compressão</p>
                            <div class="mt-3 bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 20%"></div>
                            </div>
                        </label>

                        <label class="flex flex-col p-4 bg-white rounded-lg border cursor-pointer hover:bg-gray-50 transition-colors"
                               :class="compressionLevel === 'medium' ? 'border-orange-500 bg-orange-50' : 'border-gray-200'">
                            <input type="radio" value="medium" x-model="compressionLevel" class="sr-only">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-gray-900">Média</span>
                                <span class="text-sm text-gray-500">~40% menor</span>
                            </div>
                            <p class="text-sm text-gray-600">Equilíbrio entre qualidade e tamanho</p>
                            <div class="mt-3 bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-500 h-2 rounded-full" style="width: 40%"></div>
                            </div>
                        </label>

                        <label class="flex flex-col p-4 bg-white rounded-lg border cursor-pointer hover:bg-gray-50 transition-colors"
                               :class="compressionLevel === 'high' ? 'border-orange-500 bg-orange-50' : 'border-gray-200'">
                            <input type="radio" value="high" x-model="compressionLevel" class="sr-only">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-gray-900">Alta</span>
                                <span class="text-sm text-gray-500">~60% menor</span>
                            </div>
                            <p class="text-sm text-gray-600">Máxima compressão, menor qualidade</p>
                            <div class="mt-3 bg-gray-200 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: 60%"></div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Compression Options -->
                <div x-show="selectedFile && compressionLevel" class="bg-white border rounded-lg p-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Opções de Compressão</h4>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" x-model="options.optimizeImages" class="mr-3 text-orange-600">
                            <div>
                                <div class="font-medium">Otimizar Imagens</div>
                                <div class="text-sm text-gray-500">Reduz a qualidade das imagens para economizar espaço</div>
                            </div>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" x-model="options.removeMetadata" class="mr-3 text-orange-600">
                            <div>
                                <div class="font-medium">Remover Metadados</div>
                                <div class="text-sm text-gray-500">Remove informações desnecessárias do arquivo</div>
                            </div>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" x-model="options.optimizeFonts" class="mr-3 text-orange-600">
                            <div>
                                <div class="font-medium">Otimizar Fontes</div>
                                <div class="text-sm text-gray-500">Comprime e otimiza as fontes incorporadas</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Estimated Compression -->
                <div x-show="selectedFile && compressionLevel" class="bg-gradient-to-r from-orange-50 to-red-50 border border-orange-200 rounded-lg p-6">
                    <h4 class="font-semibold text-gray-900 mb-3">Estimativa de Compressão</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                        <div class="bg-white rounded-lg p-4">
                            <p class="text-sm text-gray-600">Tamanho Original</p>
                            <p class="text-xl font-bold text-gray-900" x-text="formatFileSize(selectedFile?.size)"></p>
                        </div>
                        <div class="bg-white rounded-lg p-4">
                            <p class="text-sm text-gray-600">Tamanho Estimado</p>
                            <p class="text-xl font-bold text-orange-600" x-text="getEstimatedSize()"></p>
                        </div>
                        <div class="bg-white rounded-lg p-4">
                            <p class="text-sm text-gray-600">Economia Estimada</p>
                            <p class="text-xl font-bold text-green-600" x-text="getEstimatedSavings()"></p>
                        </div>
                    </div>
                </div>

                <!-- Compress Button -->
                <div x-show="selectedFile && compressionLevel" class="text-center">
                    <button @click="compressFile" :disabled="processing" 
                            class="bg-orange-600 hover:bg-orange-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!processing">Comprimir PDF</span>
                        <span x-show="processing" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Comprimindo...
                        </span>
                    </button>
                </div>

                <!-- Result -->
                <div x-show="result" class="mt-6 p-6 rounded-lg" :class="result?.success ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'">
                    <div x-show="result?.success">
                        <h3 class="font-bold text-green-800 mb-2">✅ Compressão Concluída!</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center mb-4">
                            <div class="bg-white rounded-lg p-3">
                                <p class="text-sm text-gray-600">Original</p>
                                <p class="font-bold text-gray-900" x-text="result?.original_size"></p>
                            </div>
                            <div class="bg-white rounded-lg p-3">
                                <p class="text-sm text-gray-600">Comprimido</p>
                                <p class="font-bold text-orange-600" x-text="result?.compressed_size"></p>
                            </div>
                            <div class="bg-white rounded-lg p-3">
                                <p class="text-sm text-gray-600">Economia</p>
                                <p class="font-bold text-green-600" x-text="result?.reduction"></p>
                            </div>
                            <div class="bg-white rounded-lg p-3">
                                <p class="text-sm text-gray-600">Redução</p>
                                <p class="font-bold text-blue-600" x-text="result?.reduction_percent"></p>
                            </div>
                        </div>
                        <div x-show="result?.download_url" class="text-center">
                            <a :href="result?.download_url" :download="result?.filename" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-4-4m4 4l4-4m-6 6h12a2 2 0 002-2v-9a2 2 0 00-2-2H6a2 2 0 00-2 2v9a2 2 0 002 2z"/>
                                </svg>
                                Baixar PDF Comprimido
                            </a>
                            <p class="text-sm text-gray-500 mt-1">Arquivo: <span x-text="result?.filename"></span></p>
                        </div>
                    </div>
                    <p class="font-medium" :class="result?.success ? 'text-green-800' : 'text-red-800'" x-text="result?.message"></p>
                </div>
            </div>
        </div>

        <!-- Benefits -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-6">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Economize Espaço</h3>
                <p class="text-gray-600">Reduza significativamente o tamanho dos arquivos sem perder qualidade importante</p>
            </div>
            
            <div class="text-center p-6">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 12l2 2 4-4"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Mais Rápido</h3>
                <p class="text-gray-600">Arquivos menores são mais rápidos para compartilhar e enviar por email</p>
            </div>
            
            <div class="text-center p-6">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Qualidade Preservada</h3>
                <p class="text-gray-600">Algoritmos inteligentes que mantêm a qualidade visual do documento</p>
            </div>
        </div>
    </div>

    <script>
        function pdfCompressor() {
            return {
                selectedFile: null,
                compressionLevel: '',
                processing: false,
                result: null,
                options: {
                    optimizeImages: true,
                    removeMetadata: true,
                    optimizeFonts: false
                },

                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file && file.type === 'application/pdf') {
                        this.selectedFile = file;
                        this.result = null;
                        this.compressionLevel = 'medium'; // Default
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

                getCompressionRate() {
                    const rates = {
                        'low': 0.8,
                        'medium': 0.6,
                        'high': 0.4
                    };
                    return rates[this.compressionLevel] || 0.6;
                },

                getEstimatedSize() {
                    if (!this.selectedFile || !this.compressionLevel) return '';
                    const newSize = this.selectedFile.size * this.getCompressionRate();
                    return this.formatFileSize(newSize);
                },

                getEstimatedSavings() {
                    if (!this.selectedFile || !this.compressionLevel) return '';
                    const savings = this.selectedFile.size * (1 - this.getCompressionRate());
                    const savingsPercent = ((1 - this.getCompressionRate()) * 100).toFixed(0);
                    return `${savingsPercent}%`;
                },

                async compressFile() {
                    if (!this.selectedFile || !this.compressionLevel) return;

                    this.processing = true;
                    this.result = null;

                    const formData = new FormData();
                    formData.append('pdf_file', this.selectedFile);
                    formData.append('compression_level', this.compressionLevel);
                    formData.append('options', JSON.stringify(this.options));

                    try {
                        const response = await fetch('{{ route("tools.compress-pdf.process") }}', {
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
                            message: 'Erro ao comprimir o arquivo. Tente novamente.'
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
