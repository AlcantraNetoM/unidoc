<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assinaturas Eletrônicas - UNIDOC</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fabric@5.3.0/dist/fabric.min.js"></script>
    <style>
        .signature-preview {
            font-family: cursive;
        }
    </style>
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
                    <h2 class="ml-4 text-lg font-semibold text-gray-700">Assinaturas Eletrônicas</h2>
                </div>
                <a href="{{ route('landing') }}" class="text-gray-600 hover:text-blue-600 transition-colors">
                    ← Voltar ao início
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Assinaturas Eletrônicas Avançadas</h1>
                <p class="text-gray-600">Assine seus documentos PDF de forma digital e segura com posicionamento visual interativo</p>
            </div>

            <!-- Electronic Signature Tool -->
            <div x-data="advancedElectronicSignature()" class="space-y-6">
                <!-- File Upload -->
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors"
                     @drop="handleDrop($event)" @dragover.prevent @dragenter.prevent>
                    <input type="file" id="pdfFile" accept=".pdf" @change="handleFileSelect" class="hidden">
                    <label for="pdfFile" class="cursor-pointer">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Selecione um PDF para assinar</h3>
                        <p class="text-gray-500">Clique aqui ou arraste seu arquivo PDF (máx. 10MB)</p>
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
                                <p class="text-sm text-gray-500">
                                    <span x-text="formatFileSize(selectedFile?.size)"></span>
                                    <span x-show="pdfPages"> • <span x-text="pdfPages"></span> páginas</span>
                                </p>
                            </div>
                        </div>
                        <button @click="removeFile" class="text-red-500 hover:text-red-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Loading PDF -->
                <div x-show="loadingPdf" class="text-center py-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                    <p class="text-gray-600">Carregando PDF...</p>
                </div>

                <!-- PDF Viewer and Signature Tools -->
                <div x-show="selectedFile && !loadingPdf" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- PDF Viewer -->
                    <div class="lg:col-span-2 space-y-4">
                        <div class="bg-white border rounded-lg p-4">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Visualização do Documento</h3>
                                <div class="flex items-center space-x-2">
                                    <button @click="prevPage" :disabled="currentPage <= 1" 
                                            class="px-3 py-1 bg-gray-100 text-gray-700 rounded disabled:opacity-50 hover:bg-gray-200">
                                        ← Anterior
                                    </button>
                                    <span class="text-sm text-gray-600">
                                        Página <span x-text="currentPage"></span> de <span x-text="pdfPages"></span>
                                    </span>
                                    <button @click="nextPage" :disabled="currentPage >= pdfPages" 
                                            class="px-3 py-1 bg-gray-100 text-gray-700 rounded disabled:opacity-50 hover:bg-gray-200">
                                        Próxima →
                                    </button>
                                </div>
                            </div>
                            
                            <!-- PDF Canvas Container -->
                            <div class="relative border border-gray-300 rounded-lg overflow-hidden bg-gray-100 flex justify-center" style="min-height: 600px;">
                                <canvas id="pdfCanvas" class="mx-auto block cursor-crosshair" @click="addSignatureToCanvas($event)"></canvas>
                                
                                <!-- Signature Overlay -->
                                <div id="signatureOverlay" class="absolute inset-0 pointer-events-none">
                                    <template x-for="(signature, index) in getCurrentPageSignatures()" :key="index">
                                        <div class="absolute pointer-events-auto cursor-move border-2 border-dashed border-blue-500 bg-blue-50 bg-opacity-50 hover:bg-blue-100"
                                             :style="`left: ${signature.x}px; top: ${signature.y}px; width: ${signature.width}px; height: ${signature.height}px;`"
                                             @mousedown="startDrag(signature.id, $event)"
                                             @click="selectSignature(signature.id)">
                                            
                                            <!-- Signature Content -->
                                            <div class="w-full h-full flex items-center justify-center p-1">
                                                <div x-show="signature.type === 'text'" 
                                                     class="text-blue-800 font-bold text-center signature-preview"
                                                     :style="`font-size: ${signature.fontSize || 16}px;`"
                                                     x-text="signature.data"></div>
                                                
                                                <img x-show="signature.type === 'draw' || signature.type === 'upload'" 
                                                     :src="signature.data" 
                                                     class="max-w-full max-h-full object-contain">
                                            </div>
                                            
                                            <!-- Resize Handle -->
                                            <div class="absolute bottom-0 right-0 w-3 h-3 bg-blue-500 cursor-se-resize"
                                                 @mousedown.stop="startResize(signature.id, $event)"></div>
                                            
                                            <!-- Delete Button -->
                                            <button @click.stop="removeSignature(signature.id)" 
                                                    class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full text-xs hover:bg-red-600 flex items-center justify-center">
                                                ×
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            
                            <!-- Zoom Controls -->
                            <div class="flex items-center justify-center mt-4 space-x-4">
                                <button @click="zoomOut" class="px-3 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                    Zoom -
                                </button>
                                <span class="text-sm text-gray-600" x-text="Math.round(zoomLevel * 100) + '%'"></span>
                                <button @click="zoomIn" class="px-3 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                    Zoom +
                                </button>
                                <button @click="resetZoom" class="px-3 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                    Ajustar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Signature Tools Panel -->
                    <div class="space-y-6">
                        <!-- Signature Type Selection -->
                        <div class="bg-white border rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Criar Assinatura</h3>
                            <div class="space-y-3">
                                <label class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors border"
                                       :class="signatureType === 'text' ? 'bg-green-50 border-green-500' : 'border-gray-200'">
                                    <input type="radio" value="text" x-model="signatureType" class="mr-3">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                        <span class="font-medium">Texto</span>
                                    </div>
                                </label>

                                <label class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors border"
                                       :class="signatureType === 'draw' ? 'bg-green-50 border-green-500' : 'border-gray-200'">
                                    <input type="radio" value="draw" x-model="signatureType" class="mr-3">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        <span class="font-medium">Desenhar</span>
                                    </div>
                                </label>

                                <label class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors border"
                                       :class="signatureType === 'upload' ? 'bg-green-50 border-green-500' : 'border-gray-200'">
                                    <input type="radio" value="upload" x-model="signatureType" class="mr-3">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="font-medium">Upload</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Signature Input -->
                        <div x-show="signatureType" class="bg-white border rounded-lg p-4">
                            <!-- Text Signature -->
                            <div x-show="signatureType === 'text'" class="space-y-4">
                                <h4 class="font-semibold text-gray-900">Digite sua assinatura</h4>
                                <input type="text" x-model="tempSignatureText" placeholder="Seu nome completo" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <p class="text-sm text-gray-600 mb-2">Prévia:</p>
                                    <div class="text-xl text-green-600 signature-preview" x-text="tempSignatureText || 'Seu nome aqui'"></div>
                                </div>
                                <div class="flex space-x-2">
                                    <button @click="addTextSignature" :disabled="!tempSignatureText.trim()"
                                            class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                                        Preparar
                                    </button>
                                </div>
                            </div>

                            <!-- Draw Signature -->
                            <div x-show="signatureType === 'draw'" class="space-y-4">
                                <h4 class="font-semibold text-gray-900">Desenhe sua assinatura</h4>
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <canvas id="signatureCanvas" width="300" height="120" class="border border-gray-300 rounded bg-white cursor-crosshair w-full"></canvas>
                                    <div class="flex justify-center mt-3 space-x-3">
                                        <button @click="clearCanvas" class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600 text-sm">
                                            Limpar
                                        </button>
                                        <button @click="addDrawnSignature" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                                            Preparar
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Signature -->
                            <div x-show="signatureType === 'upload'" class="space-y-4">
                                <h4 class="font-semibold text-gray-900">Carregar imagem da assinatura</h4>
                                <input type="file" accept="image/*" @change="handleSignatureUpload" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <div x-show="tempSignatureImage" class="bg-gray-50 p-3 rounded-lg">
                                    <p class="text-sm text-gray-600 mb-2">Prévia:</p>
                                    <img :src="tempSignatureImage" class="max-h-20 mx-auto border border-gray-300 rounded">
                                    <button @click="addUploadedSignature" class="w-full mt-3 bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-medium">
                                        Preparar
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Instructions -->
                        <div x-show="preparedSignature" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <h4 class="font-medium text-yellow-900 mb-1">Como adicionar a assinatura:</h4>
                                    <p class="text-sm text-yellow-800">Clique no documento onde deseja posicionar sua assinatura. Você pode arrastar e redimensionar após adicionar.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Signatures List -->
                        <div x-show="signatures.length > 0" class="bg-white border rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">Assinaturas no Documento (<span x-text="signatures.length"></span>)</h4>
                            <div class="space-y-2 max-h-40 overflow-y-auto">
                                <template x-for="signature in signatures" :key="signature.id">
                                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded text-sm"
                                         :class="selectedSignatureId === signature.id ? 'bg-blue-50 border border-blue-300' : ''">
                                        <div class="flex items-center">
                                            <span class="font-medium text-gray-700">
                                                <span x-text="signature.type === 'text' ? 'Texto' : (signature.type === 'draw' ? 'Desenho' : 'Imagem')"></span>
                                                <span x-text="'(Pág. ' + signature.page + ')'"></span>
                                            </span>
                                        </div>
                                        <div class="flex space-x-1">
                                            <button @click="selectSignature(signature.id)" class="text-blue-600 hover:text-blue-800 text-xs">
                                                Editar
                                            </button>
                                            <button @click="removeSignature(signature.id)" class="text-red-600 hover:text-red-800 text-xs">
                                                Remover
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Signature Properties -->
                        <div x-show="selectedSignature" class="bg-white border rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">Propriedades da Assinatura</h4>
                            <div class="space-y-3">
                                <div x-show="selectedSignature?.type === 'text'">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tamanho da Fonte</label>
                                    <input type="range" x-model="selectedSignature.fontSize" min="10" max="40" 
                                           class="w-full" @input="updateSignature">
                                    <span class="text-sm text-gray-500" x-text="selectedSignature?.fontSize + 'px'"></span>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Largura</label>
                                    <input type="range" x-model="selectedSignature.width" min="50" max="400" 
                                           class="w-full" @input="updateSignature">
                                    <span class="text-sm text-gray-500" x-text="selectedSignature?.width + 'px'"></span>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Altura</label>
                                    <input type="range" x-model="selectedSignature.height" min="30" max="200" 
                                           class="w-full" @input="updateSignature">
                                    <span class="text-sm text-gray-500" x-text="selectedSignature?.height + 'px'"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Sign Document Button -->
                        <div x-show="signatures.length > 0" class="bg-white border rounded-lg p-4">
                            <button @click="signDocument" :disabled="processing" 
                                    class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!processing">Finalizar e Baixar PDF</span>
                                <span x-show="processing" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processando...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Result -->
                <div x-show="result" class="mt-6 p-4 rounded-lg" :class="result?.success ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'">
                    <p class="font-medium" :class="result?.success ? 'text-green-800' : 'text-red-800'" x-text="result?.message"></p>
                    <div x-show="result?.success && result?.download_url" class="mt-3">
                        <a :href="result?.download_url" :download="result?.filename" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-4-4m4 4l4-4m-6 6h12a2 2 0 002-2v-9a2 2 0 00-2-2H6a2 2 0 00-2 2v9a2 2 0 002 2z"/>
                            </svg>
                            Baixar PDF Assinado (<span x-text="result?.size"></span>)
                        </a>
                        <p class="text-sm text-gray-500 mt-1">Arquivo: <span x-text="result?.filename"></span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Notice -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-blue-600 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2-2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <div>
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">Segurança e Privacidade</h3>
                    <ul class="text-blue-800 space-y-1">
                        <li>• Seus arquivos são processados de forma segura</li>
                        <li>• Não armazenamos seus documentos em nossos servidores</li>
                        <li>• Todas as assinaturas são processadas localmente quando possível</li>
                        <li>• Seus dados são protegidos durante todo o processo</li>
                        <li>• Suporte a múltiplas assinaturas no mesmo documento</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Configure PDF.js worker
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        function advancedElectronicSignature() {
            return {
                selectedFile: null,
                signatureType: '',
                tempSignatureText: '',
                tempSignatureImage: null,
                preparedSignature: null,
                signatures: [],
                selectedSignatureId: null,
                selectedSignature: null,
                processing: false,
                result: null,
                loadingPdf: false,
                
                // PDF related
                pdfDocument: null,
                pdfPages: 0,
                currentPage: 1,
                zoomLevel: 1,
                pdfCanvas: null,
                pdfContext: null,
                
                // Canvas related
                canvas: null,
                ctx: null,
                drawing: false,
                
                // Drag and resize
                isDragging: false,
                isResizing: false,
                dragStartX: 0,
                dragStartY: 0,
                dragSignatureId: null,

                init() {
                    this.$nextTick(() => {
                        this.pdfCanvas = document.getElementById('pdfCanvas');
                        this.pdfContext = this.pdfCanvas?.getContext('2d');
                        
                        this.canvas = document.getElementById('signatureCanvas');
                        if (this.canvas) {
                            this.ctx = this.canvas.getContext('2d');
                            this.setupCanvas();
                        }
                    });
                },

                setupCanvas() {
                    if (!this.canvas || !this.ctx) return;
                    
                    this.ctx.strokeStyle = '#000';
                    this.ctx.lineWidth = 2;
                    this.ctx.lineCap = 'round';
                    
                    this.canvas.addEventListener('mousedown', (e) => {
                        this.drawing = true;
                        this.ctx.beginPath();
                        this.ctx.moveTo(e.offsetX, e.offsetY);
                    });
                    
                    this.canvas.addEventListener('mousemove', (e) => {
                        if (this.drawing) {
                            this.ctx.lineTo(e.offsetX, e.offsetY);
                            this.ctx.stroke();
                        }
                    });
                    
                    this.canvas.addEventListener('mouseup', () => {
                        this.drawing = false;
                    });

                    // Touch events for mobile
                    this.canvas.addEventListener('touchstart', (e) => {
                        e.preventDefault();
                        const touch = e.touches[0];
                        const rect = this.canvas.getBoundingClientRect();
                        this.drawing = true;
                        this.ctx.beginPath();
                        this.ctx.moveTo(touch.clientX - rect.left, touch.clientY - rect.top);
                    });

                    this.canvas.addEventListener('touchmove', (e) => {
                        e.preventDefault();
                        if (this.drawing) {
                            const touch = e.touches[0];
                            const rect = this.canvas.getBoundingClientRect();
                            this.ctx.lineTo(touch.clientX - rect.left, touch.clientY - rect.top);
                            this.ctx.stroke();
                        }
                    });

                    this.canvas.addEventListener('touchend', (e) => {
                        e.preventDefault();
                        this.drawing = false;
                    });
                },

                async handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file && file.type === 'application/pdf') {
                        await this.loadPdf(file);
                    } else {
                        alert('Por favor, selecione um arquivo PDF válido.');
                    }
                },

                async handleDrop(event) {
                    event.preventDefault();
                    const files = event.dataTransfer.files;
                    if (files.length > 0 && files[0].type === 'application/pdf') {
                        await this.loadPdf(files[0]);
                    }
                },

                async loadPdf(file) {
                    this.selectedFile = file;
                    this.loadingPdf = true;
                    this.signatures = [];
                    this.result = null;

                    try {
                        const arrayBuffer = await file.arrayBuffer();
                        this.pdfDocument = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;
                        this.pdfPages = this.pdfDocument.numPages;
                        this.currentPage = 1;
                        
                        await this.renderPage();
                    } catch (error) {
                        console.error('Erro ao carregar PDF:', error);
                        alert('Erro ao carregar o PDF. Verifique se o arquivo não está corrompido.');
                    } finally {
                        this.loadingPdf = false;
                    }
                },

                async renderPage() {
                    if (!this.pdfDocument || !this.pdfCanvas) return;

                    try {
                        const page = await this.pdfDocument.getPage(this.currentPage);
                        const viewport = page.getViewport({ scale: this.zoomLevel });
                        
                        this.pdfCanvas.width = viewport.width;
                        this.pdfCanvas.height = viewport.height;

                        const renderContext = {
                            canvasContext: this.pdfContext,
                            viewport: viewport
                        };

                        await page.render(renderContext).promise;
                    } catch (error) {
                        console.error('Erro ao renderizar página:', error);
                    }
                },

                removeFile() {
                    this.selectedFile = null;
                    this.pdfDocument = null;
                    this.signatures = [];
                    this.preparedSignature = null;
                    this.result = null;
                    this.signatureType = '';
                    this.tempSignatureText = '';
                    this.tempSignatureImage = null;
                },

                clearCanvas() {
                    if (this.ctx && this.canvas) {
                        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                    }
                },

                handleSignatureUpload(event) {
                    const file = event.target.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.tempSignatureImage = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                },

                addTextSignature() {
                    if (!this.tempSignatureText.trim()) return;
                    
                    this.preparedSignature = {
                        type: 'text',
                        data: this.tempSignatureText.trim(),
                        fontSize: 20
                    };
                },

                addDrawnSignature() {
                    if (!this.canvas || this.isCanvasEmpty()) return;
                    
                    this.preparedSignature = {
                        type: 'draw',
                        data: this.canvas.toDataURL()
                    };
                },

                addUploadedSignature() {
                    if (!this.tempSignatureImage) return;
                    
                    this.preparedSignature = {
                        type: 'upload',
                        data: this.tempSignatureImage
                    };
                },

                addSignatureToCanvas(event) {
                    if (!this.preparedSignature) return;

                    const rect = this.pdfCanvas.getBoundingClientRect();
                    const x = event.clientX - rect.left;
                    const y = event.clientY - rect.top;

                    const signature = {
                        id: Date.now() + Math.random(),
                        ...this.preparedSignature,
                        x: x - 75, // Center the signature
                        y: y - 25,
                        width: 150,
                        height: 50,
                        page: this.currentPage
                    };

                    this.signatures.push(signature);
                    this.preparedSignature = null;
                    this.signatureType = '';
                    this.tempSignatureText = '';
                    this.tempSignatureImage = null;
                },

                getCurrentPageSignatures() {
                    return this.signatures.filter(sig => sig.page === this.currentPage);
                },

                selectSignature(id) {
                    this.selectedSignatureId = id;
                    this.selectedSignature = this.signatures.find(sig => sig.id === id);
                },

                removeSignature(id) {
                    this.signatures = this.signatures.filter(sig => sig.id !== id);
                    if (this.selectedSignatureId === id) {
                        this.selectedSignatureId = null;
                        this.selectedSignature = null;
                    }
                },

                updateSignature() {
                    if (this.selectedSignature) {
                        // Trigger reactivity
                        this.$nextTick();
                    }
                },

                startDrag(id, event) {
                    this.isDragging = true;
                    this.dragSignatureId = id;
                    this.dragStartX = event.clientX;
                    this.dragStartY = event.clientY;

                    const signature = this.signatures.find(sig => sig.id === id);
                    this.dragStartSigX = signature.x;
                    this.dragStartSigY = signature.y;

                    document.addEventListener('mousemove', this.handleDrag);
                    document.addEventListener('mouseup', this.stopDrag);
                },

                handleDrag(event) {
                    if (!this.isDragging) return;

                    const deltaX = event.clientX - this.dragStartX;
                    const deltaY = event.clientY - this.dragStartY;

                    const signature = this.signatures.find(sig => sig.id === this.dragSignatureId);
                    if (signature) {
                        signature.x = Math.max(0, this.dragStartSigX + deltaX);
                        signature.y = Math.max(0, this.dragStartSigY + deltaY);
                    }
                },

                stopDrag() {
                    this.isDragging = false;
                    document.removeEventListener('mousemove', this.handleDrag);
                    document.removeEventListener('mouseup', this.stopDrag);
                },

                startResize(id, event) {
                    this.isResizing = true;
                    this.dragSignatureId = id;
                    this.dragStartX = event.clientX;
                    this.dragStartY = event.clientY;

                    const signature = this.signatures.find(sig => sig.id === id);
                    this.dragStartWidth = signature.width;
                    this.dragStartHeight = signature.height;

                    document.addEventListener('mousemove', this.handleResize);
                    document.addEventListener('mouseup', this.stopResize);
                },

                handleResize(event) {
                    if (!this.isResizing) return;

                    const deltaX = event.clientX - this.dragStartX;
                    const deltaY = event.clientY - this.dragStartY;

                    const signature = this.signatures.find(sig => sig.id === this.dragSignatureId);
                    if (signature) {
                        signature.width = Math.max(50, this.dragStartWidth + deltaX);
                        signature.height = Math.max(30, this.dragStartHeight + deltaY);
                    }
                },

                stopResize() {
                    this.isResizing = false;
                    document.removeEventListener('mousemove', this.handleResize);
                    document.removeEventListener('mouseup', this.stopResize);
                },

                async prevPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                        await this.renderPage();
                    }
                },

                async nextPage() {
                    if (this.currentPage < this.pdfPages) {
                        this.currentPage++;
                        await this.renderPage();
                    }
                },

                async zoomIn() {
                    this.zoomLevel = Math.min(3, this.zoomLevel + 0.25);
                    await this.renderPage();
                },

                async zoomOut() {
                    this.zoomLevel = Math.max(0.5, this.zoomLevel - 0.25);
                    await this.renderPage();
                },

                async resetZoom() {
                    this.zoomLevel = 1;
                    await this.renderPage();
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

                isCanvasEmpty() {
                    if (!this.canvas || !this.ctx) return true;
                    const imageData = this.ctx.getImageData(0, 0, this.canvas.width, this.canvas.height);
                    return imageData.data.every(pixel => pixel === 0);
                },

                async signDocument() {
                    if (!this.selectedFile || this.signatures.length === 0) return;

                    this.processing = true;
                    this.result = null;

                    const formData = new FormData();
                    formData.append('pdf_file', this.selectedFile);
                    formData.append('signatures', JSON.stringify(this.signatures));
                    formData.append('pdf_pages', this.pdfPages);

                    try {
                        const response = await fetch('{{ route("tools.electronic-signature.process") }}', {
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
                            message: 'Erro ao assinar o documento. Tente novamente.'
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
