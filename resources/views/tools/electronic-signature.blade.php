<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Assinaturas Eletrônicas - UNIDOC</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fabric@5.3.0/dist/fabric.min.js"></script>
    <style>
        .signature-preview {
            font-family: cursive;
        }
        
        .drawing-canvas {
            touch-action: none; /* Prevent scrolling on touch devices */
            user-select: none;
        }
        
        .signature-mode-selected {
            background-color: #ecfdf5 !important;
            border-color: #10b981 !important;
            box-shadow: 0 0 0 1px #10b981;
        }
        
        .toast-notification {
            animation: slideInRight 0.3s ease-out forwards;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Estilos para melhorar drag and drop */
        .canvas-container {
            position: relative;
        }
        
        .canvas-instructions {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: #9ca3af;
            pointer-events: none;
            font-size: 14px;
        }
        
        /* Estilo para assinaturas sendo arrastadas */
        .signature-dragging {
            z-index: 1000 !important;
            opacity: 0.8;
        }
        
        /* Cursor personalizado durante drag */
        .drag-cursor,
        .drag-cursor * {
            cursor: grabbing !important;
        }
        
        /* Animações suaves para assinaturas */
        .signature-element {
            transition: all 0.2s ease;
        }
        
        .signature-element:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        /* Melhorar a visualização do resize handle */
        .resize-handle {
            transition: all 0.2s ease;
        }
        
        .resize-handle:hover {
            transform: scale(1.2);
            background-color: #1d4ed8 !important;
        }
        
        .signature-overlay-item {
            transition: all 0.2s ease;
        }
        
        .signature-overlay-item:hover {
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .canvas-container {
            position: relative;
        }
        
        .canvas-instructions {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #9ca3af;
            font-size: 14px;
            pointer-events: none;
            text-align: center;
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
            <div x-data="advancedElectronicSignature()" x-init="init()" class="space-y-6">
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
                                <canvas id="pdfCanvas" 
                                        class="mx-auto block cursor-crosshair" 
                                        style="max-width: 100%; height: auto; min-width: 200px; min-height: 200px;" 
                                        @click="addSignatureToCanvas($event)"
                                        width="600" 
                                        height="800"></canvas>
                                
                                <!-- Signature Overlay -->
                                <div id="signatureOverlay" class="absolute inset-0 pointer-events-none">
                                    <template x-for="(signature, index) in getCurrentPageSignatures()" :key="signature.id">
                                        <div class="absolute pointer-events-auto border-2 border-dashed transition-all duration-200 signature-element"
                                             :class="{
                                                'border-blue-500 bg-blue-50 bg-opacity-50 hover:bg-blue-100 cursor-move': selectedSignatureId !== signature.id && !isDragging,
                                                'border-green-500 bg-green-50 bg-opacity-70 cursor-move': selectedSignatureId === signature.id && !isDragging,
                                                'signature-dragging border-purple-500 bg-purple-50': isDragging && dragSignatureId === signature.id,
                                                'drag-cursor': isDragging && dragSignatureId === signature.id
                                             }"
                                             :style="`left: ${signature.x}px; top: ${signature.y}px; width: ${signature.width}px; height: ${signature.height}px;`"
                                             @mousedown.prevent="startDrag(signature.id, $event)"
                                             @touchstart.prevent="startDragTouch(signature.id, $event)"
                                             @click.stop="selectSignature(signature.id)"
                                             @mouseenter="signature.isHovered = true"
                                             @mouseleave="signature.isHovered = false"
                                             :title="`${signature.type === 'text' ? 'Texto' : (signature.type === 'draw' ? 'Desenho' : 'Imagem')} - Clique e arraste para mover`">
                                            
                                            <!-- Signature Content -->
                                            <div class="w-full h-full flex items-center justify-center p-1 pointer-events-none">
                                                <div x-show="signature.type === 'text'" 
                                                     class="text-blue-800 font-bold text-center signature-preview truncate"
                                                     :style="`font-size: ${signature.fontSize || 16}px;`"
                                                     x-text="signature.data"></div>
                                                
                                                <img x-show="signature.type === 'draw' || signature.type === 'upload'" 
                                                     :src="signature.data" 
                                                     class="max-w-full max-h-full object-contain pointer-events-none"
                                                     draggable="false">
                                            </div>
                                            
                                            <!-- Resize Handle -->
                                            <div class="absolute bottom-0 right-0 w-4 h-4 bg-blue-600 cursor-se-resize transform rotate-45 border border-white shadow-sm hover:bg-blue-700 transition-colors resize-handle"
                                                 @mousedown.stop.prevent="startResize(signature.id, $event)"
                                                 @touchstart.stop.prevent="startResizeTouch(signature.id, $event)"
                                                 title="Arrastar para redimensionar"></div>
                                            
                                            <!-- Delete Button -->
                                            <button @click.stop="removeSignature(signature.id)" 
                                                    class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full text-xs hover:bg-red-600 flex items-center justify-center shadow-md transition-colors"
                                                    title="Remover assinatura">
                                                ×
                                            </button>
                                            
                                            <!-- Position indicator (only when selected) -->
                                            <div x-show="selectedSignatureId === signature.id" 
                                                 class="absolute -top-8 left-0 bg-black text-white text-xs px-2 py-1 rounded pointer-events-none">
                                                <span x-text="`${Math.round(signature.x)}, ${Math.round(signature.y)}`"></span>
                                            </div>
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
                                       :class="signatureType === 'text' ? 'signature-mode-selected' : 'border-gray-200'">
                                    <input type="radio" value="text" x-model="signatureType" class="mr-3">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                        <span class="font-medium">Digitar Texto</span>
                                    </div>
                                </label>

                                <label class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors border"
                                       :class="signatureType === 'draw' ? 'signature-mode-selected' : 'border-gray-200'">
                                    <input type="radio" value="draw" x-model="signatureType" class="mr-3">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        <span class="font-medium">Desenhar à Mão</span>
                                    </div>
                                </label>

                                <label class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors border"
                                       :class="signatureType === 'upload' ? 'signature-mode-selected' : 'border-gray-200'">
                                    <input type="radio" value="upload" x-model="signatureType" class="mr-3">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="font-medium">Carregar Imagem</span>
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
                            <div x-show="signatureType === 'draw'" class="space-y-4" x-init="ensureSignatureCanvasReady()">
                                <h4 class="font-semibold text-gray-900">Desenhe sua assinatura</h4>
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <p class="text-sm text-gray-600 mb-2">Use o mouse ou touch para desenhar:</p>
                                    <div class="canvas-container relative">
                                        <canvas id="signatureCanvas" 
                                                width="300" 
                                                height="120" 
                                                class="drawing-canvas border border-gray-300 rounded bg-white cursor-crosshair w-full"></canvas>
                                        <div x-show="isCanvasEmpty()" class="canvas-instructions">
                                            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            <div>Clique e arraste para desenhar sua assinatura</div>
                                        </div>
                                    </div>
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
                        <div x-show="preparedSignature" class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <h4 class="font-medium text-green-900 mb-1">Assinatura preparada! ✓</h4>
                                    <p class="text-sm text-green-800">Agora clique no documento onde deseja posicionar sua assinatura. Você pode arrastar e redimensionar após adicionar.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- No signature prepared instructions -->
                        <div x-show="!preparedSignature && signatureType" class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <h4 class="font-medium text-blue-900 mb-1">Como criar sua assinatura:</h4>
                                    <div class="text-sm text-blue-800">
                                        <div x-show="signatureType === 'text'">• Digite seu nome e clique em "Preparar"</div>
                                        <div x-show="signatureType === 'draw'">• Desenhe sua assinatura usando o mouse ou touch e clique em "Preparar"</div>
                                        <div x-show="signatureType === 'upload'">• Selecione uma imagem da sua assinatura e clique em "Preparar"</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Instructions for moving signatures -->
                        <div x-show="signatures.length > 0 && !preparedSignature" class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-amber-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <div>
                                    <h4 class="font-medium text-amber-900 mb-1">💡 Dica: Movendo assinaturas</h4>
                                    <div class="text-sm text-amber-800">
                                        • Clique e arraste qualquer assinatura para reposicioná-la<br>
                                        • Use o handle no canto inferior direito para redimensionar<br>
                                        • Clique em uma assinatura para selecioná-la e editar propriedades
                                    </div>
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
                                           class="w-full" @input="updateSignature" x-show="selectedSignature">
                                    <span class="text-sm text-gray-500" x-text="(selectedSignature?.fontSize || 16) + 'px'"></span>
                                </div>
                                
                                <div x-show="selectedSignature">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Largura</label>
                                    <input type="range" x-model="selectedSignature.width" min="50" max="400" 
                                           class="w-full" @input="updateSignature">
                                    <span class="text-sm text-gray-500" x-text="(selectedSignature?.width || 150) + 'px'"></span>
                                </div>
                                
                                <div x-show="selectedSignature">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Altura</label>
                                    <input type="range" x-model="selectedSignature.height" min="30" max="200" 
                                           class="w-full" @input="updateSignature">
                                    <span class="text-sm text-gray-500" x-text="(selectedSignature?.height || 50) + 'px'"></span>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
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
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js';

        function advancedElectronicSignature() {
            return {
                // Estado do componente
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
                    console.log('=== INICIALIZANDO COMPONENTE DE ASSINATURA ELETRÔNICA ===');
                    
                    // Verificar dependências
                    const dependencies = {
                        'PDF.js': typeof pdfjsLib !== 'undefined',
                        'Alpine.js': typeof Alpine !== 'undefined'
                    };
                    console.log('Dependências disponíveis:', dependencies);
                    
                    this.$nextTick(() => {
                        this.initializePdfCanvas();
                        this.initializeSignatureCanvas();
                        this.setupEventListeners();
                        
                        console.log('Inicialização concluída');
                    });
                },
                
                initializePdfCanvas() {
                    console.log('Inicializando canvas PDF...');
                    this.pdfCanvas = document.getElementById('pdfCanvas');
                    
                    if (this.pdfCanvas) {
                        this.pdfContext = this.pdfCanvas.getContext('2d');
                        console.log('Canvas PDF encontrado:', {
                            id: this.pdfCanvas.id,
                            width: this.pdfCanvas.width,
                            height: this.pdfCanvas.height,
                            contextType: this.pdfContext ? '2d' : 'failed'
                        });
                        
                        // Configurar estilo inicial e garantir visibilidade
                        this.pdfCanvas.style.maxWidth = '100%';
                        this.pdfCanvas.style.height = 'auto';
                        this.pdfCanvas.style.display = 'block';
                        this.pdfCanvas.style.visibility = 'visible';
                        
                        // Desenhar indicador inicial no canvas
                        this.drawCanvasPlaceholder();
                        
                    } else {
                        console.error('Canvas PDF não encontrado! Verificando DOM...');
                        console.log('Elementos canvas no DOM:', 
                            Array.from(document.querySelectorAll('canvas')).map(c => c.id));
                    }
                },
                
                drawCanvasPlaceholder() {
                    if (!this.pdfContext || !this.pdfCanvas) return;
                    
                    // Limpar canvas
                    this.pdfContext.clearRect(0, 0, this.pdfCanvas.width, this.pdfCanvas.height);
                    
                    // Desenhar fundo
                    this.pdfContext.fillStyle = '#f8f9fa';
                    this.pdfContext.fillRect(0, 0, this.pdfCanvas.width, this.pdfCanvas.height);
                    
                    // Desenhar texto placeholder
                    this.pdfContext.fillStyle = '#6c757d';
                    this.pdfContext.font = '20px Arial';
                    this.pdfContext.textAlign = 'center';
                    this.pdfContext.fillText('Aguardando PDF...', this.pdfCanvas.width / 2, this.pdfCanvas.height / 2);
                    
                    console.log('Placeholder desenhado no canvas');
                },
                
                initializeSignatureCanvas() {
                    console.log('Inicializando canvas de assinatura...');
                    this.canvas = document.getElementById('signatureCanvas');
                    
                    if (this.canvas) {
                        this.ctx = this.canvas.getContext('2d');
                        this.setupCanvas();
                        console.log('Canvas de assinatura inicializado');
                    } else {
                        console.log('Canvas de assinatura não encontrado (será inicializado quando necessário)');
                        // Verificar periodicamente se o canvas está disponível
                        const checkCanvas = () => {
                            this.canvas = document.getElementById('signatureCanvas');
                            if (this.canvas) {
                                this.ctx = this.canvas.getContext('2d');
                                this.setupCanvas();
                                console.log('Canvas de assinatura inicializado dinamicamente');
                            } else {
                                setTimeout(checkCanvas, 100);
                            }
                        };
                        setTimeout(checkCanvas, 100);
                    }
                },
                
                setupEventListeners() {
                    console.log('Configurando event listeners...');
                    
                    // Event listener para mudança de arquivo
                    const fileInput = document.querySelector('input[type="file"][accept=".pdf"]');
                    if (fileInput) {
                        console.log('Input de arquivo encontrado');
                    } else {
                        console.warn('Input de arquivo não encontrado');
                    }
                    
                    // Verificar se PDF.js está carregado
                    if (typeof pdfjsLib !== 'undefined') {
                        console.log('PDF.js configurado:', {
                            version: pdfjsLib.version,
                            workerSrc: pdfjsLib.GlobalWorkerOptions.workerSrc
                        });
                    } else {
                        console.error('PDF.js não está disponível!');
                    }
                },

                setupCanvas() {
                    if (!this.canvas || !this.ctx) return;
                    
                    console.log('Configurando canvas de assinatura:', {
                        width: this.canvas.width,
                        height: this.canvas.height,
                        clientWidth: this.canvas.clientWidth,
                        clientHeight: this.canvas.clientHeight
                    });
                    
                    // Configurações básicas do canvas
                    this.ctx.strokeStyle = '#000';
                    this.ctx.lineWidth = 2;
                    this.ctx.lineCap = 'round';
                    this.ctx.lineJoin = 'round';
                    
                    // Limpar canvas e definir fundo branco
                    this.ctx.fillStyle = '#ffffff';
                    this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
                    this.ctx.fillStyle = '#000000'; // Resetar para cor de desenho
                    
                    // Remover event listeners existentes para evitar duplicatas
                    this.canvas.removeEventListener('mousedown', this.handleMouseDown);
                    this.canvas.removeEventListener('mousemove', this.handleMouseMove);
                    this.canvas.removeEventListener('mouseup', this.handleMouseUp);
                    this.canvas.removeEventListener('touchstart', this.handleTouchStart);
                    this.canvas.removeEventListener('touchmove', this.handleTouchMove);
                    this.canvas.removeEventListener('touchend', this.handleTouchEnd);
                    
                    // Bind dos métodos para preservar o contexto 'this'
                    this.handleMouseDown = this.handleMouseDown.bind(this);
                    this.handleMouseMove = this.handleMouseMove.bind(this);
                    this.handleMouseUp = this.handleMouseUp.bind(this);
                    this.handleTouchStart = this.handleTouchStart.bind(this);
                    this.handleTouchMove = this.handleTouchMove.bind(this);
                    this.handleTouchEnd = this.handleTouchEnd.bind(this);
                    
                    // Mouse events
                    this.canvas.addEventListener('mousedown', this.handleMouseDown);
                    this.canvas.addEventListener('mousemove', this.handleMouseMove);
                    this.canvas.addEventListener('mouseup', this.handleMouseUp);
                    this.canvas.addEventListener('mouseout', this.handleMouseUp);
                    
                    // Touch events para dispositivos móveis
                    this.canvas.addEventListener('touchstart', this.handleTouchStart, { passive: false });
                    this.canvas.addEventListener('touchmove', this.handleTouchMove, { passive: false });
                    this.canvas.addEventListener('touchend', this.handleTouchEnd, { passive: false });
                    
                    console.log('Canvas de assinatura configurado com sucesso');
                },
                
                handleMouseDown(e) {
                    this.drawing = true;
                    this.ctx.beginPath();
                    this.ctx.moveTo(e.offsetX, e.offsetY);
                    console.log('Início do desenho:', e.offsetX, e.offsetY);
                },
                
                handleMouseMove(e) {
                    if (this.drawing) {
                        this.ctx.lineTo(e.offsetX, e.offsetY);
                        this.ctx.stroke();
                        // Forçar atualização do Alpine para mostrar/esconder instruções
                        this.$nextTick(() => {
                            // Trigger reatividade
                        });
                    }
                },
                
                handleMouseUp() {
                    if (this.drawing) {
                        this.drawing = false;
                        console.log('Fim do desenho');
                    }
                },
                
                handleTouchStart(e) {
                    e.preventDefault();
                    const touch = e.touches[0];
                    const rect = this.canvas.getBoundingClientRect();
                    const x = touch.clientX - rect.left;
                    const y = touch.clientY - rect.top;
                    
                    this.drawing = true;
                    this.ctx.beginPath();
                    this.ctx.moveTo(x, y);
                    console.log('Início do desenho touch:', x, y);
                },
                
                handleTouchMove(e) {
                    e.preventDefault();
                    if (this.drawing) {
                        const touch = e.touches[0];
                        const rect = this.canvas.getBoundingClientRect();
                        const x = touch.clientX - rect.left;
                        const y = touch.clientY - rect.top;
                        
                        this.ctx.lineTo(x, y);
                        this.ctx.stroke();
                        // Forçar atualização do Alpine para mostrar/esconder instruções
                        this.$nextTick(() => {
                            // Trigger reatividade
                        });
                    }
                },
                
                handleTouchEnd(e) {
                    e.preventDefault();
                    if (this.drawing) {
                        this.drawing = false;
                        console.log('Fim do desenho touch');
                    }
                },
                
                ensureSignatureCanvasReady() {
                    this.$nextTick(() => {
                        // Aguardar um pequeno delay para garantir que o DOM foi atualizado
                        setTimeout(() => {
                            this.canvas = document.getElementById('signatureCanvas');
                            if (this.canvas && !this.ctx) {
                                this.ctx = this.canvas.getContext('2d');
                                this.setupCanvas();
                                console.log('Canvas de assinatura configurado dinamicamente');
                            } else if (!this.canvas) {
                                console.warn('Canvas de assinatura não encontrado após delay');
                            }
                        }, 100);
                    });
                },

                async handleFileSelect(event) {
                    const file = event.target.files[0];
                    console.log('Arquivo selecionado:', file);
                    
                    if (!file) {
                        console.log('Nenhum arquivo selecionado');
                        return;
                    }
                    
                    if (file.type !== 'application/pdf') {
                        console.error('Tipo de arquivo inválido:', file.type);
                        alert('Por favor, selecione um arquivo PDF válido.');
                        return;
                    }
                    
                    if (file.size > 10 * 1024 * 1024) { // 10MB
                        console.error('Arquivo muito grande:', file.size);
                        alert('O arquivo deve ter no máximo 10MB.');
                        return;
                    }
                    
                    await this.loadPdf(file);
                },

                async handleDrop(event) {
                    event.preventDefault();
                    const files = event.dataTransfer.files;
                    if (files.length > 0 && files[0].type === 'application/pdf') {
                        await this.loadPdf(files[0]);
                    }
                },

                async loadPdf(file) {
                    console.log('=== INICIANDO CARREGAMENTO DE PDF ===');
                    console.log('Arquivo:', {
                        name: file.name,
                        size: file.size,
                        type: file.type,
                        lastModified: new Date(file.lastModified)
                    });
                    
                    this.selectedFile = file;
                    this.loadingPdf = true;
                    this.signatures = [];
                    this.result = null;

                    try {
                        // Verificar se PDF.js está disponível
                        if (typeof pdfjsLib === 'undefined') {
                            throw new Error('PDF.js não está carregado');
                        }
                        console.log('PDF.js disponível, versão:', pdfjsLib.version);

                        // Converter arquivo para ArrayBuffer
                        console.log('Convertendo arquivo para ArrayBuffer...');
                        const arrayBuffer = await file.arrayBuffer();
                        console.log('ArrayBuffer criado:', {
                            size: arrayBuffer.byteLength,
                            type: typeof arrayBuffer
                        });
                        
                        // Verificar se o ArrayBuffer tem conteúdo
                        if (arrayBuffer.byteLength === 0) {
                            throw new Error('Arquivo está vazio');
                        }

                        // Carregar documento PDF
                        console.log('Carregando documento PDF...');
                        const loadingTask = pdfjsLib.getDocument({ 
                            data: arrayBuffer,
                            verbosity: 0 // Reduzir logs do PDF.js
                        });
                        
                        this.pdfDocument = await loadingTask.promise;
                        this.pdfPages = this.pdfDocument.numPages;
                        this.currentPage = 1;
                        
                        console.log('PDF carregado com sucesso:', {
                            pages: this.pdfPages,
                            fingerprint: this.pdfDocument.fingerprint
                        });
                        
                        // Aguardar o DOM atualizar e garantir que o canvas esteja disponível
                        await this.$nextTick();
                        await new Promise(resolve => setTimeout(resolve, 300));
                        
                        // Verificar se o canvas está no DOM
                        const canvas = document.getElementById('pdfCanvas');
                        console.log('Canvas no DOM:', {
                            exists: !!canvas,
                            id: canvas?.id,
                            width: canvas?.width,
                            height: canvas?.height,
                            clientWidth: canvas?.clientWidth,
                            clientHeight: canvas?.clientHeight,
                            offsetParent: !!canvas?.offsetParent
                        });
                        
                        // Renderizar primeira página
                        console.log('Iniciando renderização da primeira página...');
                        await this.renderPage();
                        
                    } catch (error) {
                        console.error('=== ERRO NO CARREGAMENTO DO PDF ===');
                        console.error('Tipo do erro:', error.constructor.name);
                        console.error('Mensagem:', error.message);
                        console.error('Stack trace:', error.stack);
                        
                        // Mostrar erro específico baseado no tipo
                        let errorMessage = 'Erro desconhecido ao carregar o PDF';
                        if (error.message.includes('Invalid PDF')) {
                            errorMessage = 'O arquivo não é um PDF válido ou está corrompido';
                        } else if (error.message.includes('PDF.js não está carregado')) {
                            errorMessage = 'Erro interno: PDF.js não carregado. Tente recarregar a página';
                        } else if (error.message.includes('Arquivo está vazio')) {
                            errorMessage = 'O arquivo selecionado está vazio';
                        } else {
                            errorMessage = `Erro ao carregar PDF: ${error.message}`;
                        }
                        
                        alert(errorMessage);
                    } finally {
                        this.loadingPdf = false;
                        console.log('=== CARREGAMENTO FINALIZADO ===');
                    }
                },

                async renderPage() {
                    console.log('Renderizando página:', this.currentPage);
                    
                    if (!this.pdfDocument) {
                        console.error('PDF document não carregado');
                        return;
                    }
                    
                    // Forçar re-obtenção do canvas
                    this.pdfCanvas = document.getElementById('pdfCanvas');
                    if (!this.pdfCanvas) {
                        console.error('Canvas PDF não encontrado no DOM');
                        // Aguardar um pouco e tentar novamente
                        await new Promise(resolve => setTimeout(resolve, 200));
                        this.pdfCanvas = document.getElementById('pdfCanvas');
                        if (!this.pdfCanvas) {
                            console.error('Canvas PDF ainda não encontrado após espera');
                            return;
                        }
                    }
                    
                    this.pdfContext = this.pdfCanvas.getContext('2d');
                    if (!this.pdfContext) {
                        console.error('Não foi possível obter contexto 2D do canvas');
                        return;
                    }

                    try {
                        const page = await this.pdfDocument.getPage(this.currentPage);
                        const viewport = page.getViewport({ scale: this.zoomLevel });
                        
                        console.log('Viewport calculado:', {
                            width: viewport.width,
                            height: viewport.height,
                            scale: viewport.scale
                        });
                        
                        // Configurar dimensões do canvas
                        this.pdfCanvas.width = viewport.width;
                        this.pdfCanvas.height = viewport.height;
                        
                        console.log('Canvas configurado:', {
                            width: this.pdfCanvas.width,
                            height: this.pdfCanvas.height,
                            clientWidth: this.pdfCanvas.clientWidth,
                            clientHeight: this.pdfCanvas.clientHeight,
                            style: this.pdfCanvas.style.cssText
                        });

                        // Limpar canvas antes de renderizar
                        this.pdfContext.clearRect(0, 0, this.pdfCanvas.width, this.pdfCanvas.height);

                        const renderContext = {
                            canvasContext: this.pdfContext,
                            viewport: viewport
                        };

                        console.log('Iniciando renderização...');
                        await page.render(renderContext).promise;
                        console.log('Página renderizada com sucesso!');
                        
                        // Verificar se há conteúdo no canvas
                        const imageData = this.pdfContext.getImageData(0, 0, this.pdfCanvas.width, this.pdfCanvas.height);
                        const hasContent = imageData.data.some(channel => channel !== 255);
                        console.log('Canvas tem conteúdo:', hasContent);
                        
                        // Forçar repaint do canvas
                        this.pdfCanvas.style.display = 'none';
                        this.pdfCanvas.offsetHeight; // Trigger reflow
                        this.pdfCanvas.style.display = 'block';
                        
                    } catch (error) {
                        console.error('Erro detalhado ao renderizar página:', error);
                        console.error('Stack trace:', error.stack);
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
                    if (!this.canvas || !this.ctx) {
                        console.warn('Canvas não disponível para limpeza');
                        return;
                    }
                    
                    // Limpar o canvas
                    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                    
                    // Definir fundo branco
                    this.ctx.fillStyle = '#ffffff';
                    this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
                    
                    // Resetar configurações de desenho
                    this.ctx.strokeStyle = '#000';
                    this.ctx.fillStyle = '#000';
                    
                    console.log('Canvas limpo');
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
                    
                    console.log('Assinatura de texto preparada:', this.preparedSignature);
                },

                addDrawnSignature() {
                    if (!this.canvas || this.isCanvasEmpty()) {
                        alert('Por favor, desenhe sua assinatura no canvas antes de preparar.');
                        return;
                    }
                    
                    try {
                        const canvasData = this.canvas.toDataURL('image/png');
                        
                        // Verificar se o canvas realmente gerou dados válidos
                        if (!canvasData || canvasData === 'data:,') {
                            throw new Error('Canvas não contém dados válidos');
                        }
                        
                        this.preparedSignature = {
                            type: 'draw',
                            data: canvasData
                        };
                        
                        console.log('Assinatura desenhada preparada:', this.preparedSignature);
                    } catch (error) {
                        console.error('Erro ao preparar assinatura desenhada:', error);
                        alert('Erro ao processar o desenho. Tente desenhar novamente.');
                    }
                },

                isCanvasEmpty() {
                    if (!this.canvas || !this.ctx) return true;
                    
                    try {
                        const imageData = this.ctx.getImageData(0, 0, this.canvas.width, this.canvas.height);
                        
                        // Verificar se há pixels não-brancos (255,255,255,255)
                        for (let i = 0; i < imageData.data.length; i += 4) {
                            const r = imageData.data[i];
                            const g = imageData.data[i + 1];
                            const b = imageData.data[i + 2];
                            const a = imageData.data[i + 3];
                            
                            // Se encontrar qualquer pixel que não seja branco ou transparente
                            if (a > 0 && (r !== 255 || g !== 255 || b !== 255)) {
                                return false;
                            }
                        }
                        
                        return true;
                    } catch (error) {
                        console.warn('Erro ao verificar se canvas está vazio:', error);
                        return true;
                    }
                },

                addUploadedSignature() {
                    if (!this.tempSignatureImage) {
                        alert('Por favor, selecione uma imagem antes de preparar.');
                        return;
                    }
                    
                    // Verificar se a imagem é válida
                    if (!this.tempSignatureImage.startsWith('data:image/')) {
                        alert('Formato de imagem inválido. Use PNG, JPG ou similar.');
                        return;
                    }
                    
                    this.preparedSignature = {
                        type: 'upload',
                        data: this.tempSignatureImage
                    };
                    
                    console.log('Assinatura de upload preparada:', this.preparedSignature);
                },

                addSignatureToCanvas(event) {
                    if (!this.preparedSignature) {
                        alert('Primeiro você precisa criar e preparar uma assinatura usando os controles ao lado.');
                        return;
                    }

                    // Verificar se há um PDF carregado
                    if (!this.selectedFile || !this.pdfDocument) {
                        alert('Primeiro você precisa carregar um documento PDF.');
                        return;
                    }

                    // Verificar se o canvas PDF está disponível
                    if (!this.pdfCanvas) {
                        console.error('Canvas PDF não disponível');
                        this.pdfCanvas = document.getElementById('pdfCanvas');
                        if (!this.pdfCanvas) {
                            alert('Erro: Canvas não encontrado. Tente recarregar a página.');
                            return;
                        }
                    }

                    const rect = this.pdfCanvas.getBoundingClientRect();
                    const x = event.clientX - rect.left;
                    const y = event.clientY - rect.top;

                    console.log('Adicionando assinatura na posição:', x, y);

                    // Criar assinatura com dados validados
                    const signature = {
                        id: Date.now() + Math.random(),
                        type: this.preparedSignature.type,
                        data: this.preparedSignature.data,
                        x: Math.max(0, Math.round(x - 75)), // Centralizar a assinatura e garantir que não seja negativo
                        y: Math.max(0, Math.round(y - 25)),
                        width: 150,
                        height: 50,
                        page: this.currentPage || 1
                    };
                    
                    // Adicionar fontSize apenas para assinaturas de texto
                    if (this.preparedSignature.type === 'text') {
                        signature.fontSize = this.preparedSignature.fontSize || 20;
                    }

                    // Validar dados da assinatura antes de adicionar
                    if (!signature.data || signature.data.trim() === '') {
                        alert('Erro: Dados da assinatura inválidos. Tente criar a assinatura novamente.');
                        return;
                    }

                    this.signatures.push(signature);
                    console.log('Assinatura adicionada:', signature);
                    
                    // Mostrar feedback de sucesso
                    this.showTemporaryMessage('Assinatura adicionada com sucesso! Você pode arrastar e redimensionar.', 'success');
                    
                    // Limpar assinatura preparada
                    this.preparedSignature = null;
                    this.signatureType = '';
                    this.tempSignatureText = '';
                    this.tempSignatureImage = null;
                    
                    // Limpar canvas de desenho se estava sendo usado
                    if (this.canvas && this.ctx) {
                        this.clearCanvas();
                    }
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
                    event.preventDefault();
                    event.stopPropagation();
                    
                    console.log('Iniciando drag da assinatura:', id);
                    
                    this.isDragging = true;
                    this.dragSignatureId = id;
                    this.dragStartX = event.clientX;
                    this.dragStartY = event.clientY;

                    const signature = this.signatures.find(sig => sig.id === id);
                    if (signature) {
                        this.dragStartSigX = signature.x;
                        this.dragStartSigY = signature.y;
                        
                        console.log('Posição inicial:', signature.x, signature.y);
                    }

                    // Bind os métodos para manter o contexto 'this'
                    this.boundHandleDrag = this.handleDrag.bind(this);
                    this.boundStopDrag = this.stopDrag.bind(this);

                    document.addEventListener('mousemove', this.boundHandleDrag);
                    document.addEventListener('mouseup', this.boundStopDrag);
                    
                    // Adicionar classe para indicar que está sendo arrastado
                    document.body.style.cursor = 'grabbing';
                    document.body.style.userSelect = 'none';
                },

                handleDrag(event) {
                    if (!this.isDragging) return;
                    
                    event.preventDefault();
                    
                    const deltaX = event.clientX - this.dragStartX;
                    const deltaY = event.clientY - this.dragStartY;
                    
                    const signature = this.signatures.find(sig => sig.id === this.dragSignatureId);
                    if (signature) {
                        // Calcular nova posição
                        const newX = this.dragStartSigX + deltaX;
                        const newY = this.dragStartSigY + deltaY;
                        
                        // Garantir que a assinatura não saia dos limites do canvas
                        const canvas = document.getElementById('pdfCanvas');
                        if (canvas) {
                            const maxX = canvas.width - signature.width;
                            const maxY = canvas.height - signature.height;
                            
                            signature.x = Math.max(0, Math.min(newX, maxX));
                            signature.y = Math.max(0, Math.min(newY, maxY));
                        } else {
                            signature.x = newX;
                            signature.y = newY;
                        }
                        
                        // Forçar atualização do Alpine.js
                        this.$nextTick();
                    }
                },
                
                stopDrag() {
                    if (!this.isDragging) return;
                    
                    console.log('Finalizando drag');
                    
                    this.isDragging = false;
                    this.dragSignatureId = null;
                    
                    // Remover event listeners
                    if (this.boundHandleDrag) {
                        document.removeEventListener('mousemove', this.boundHandleDrag);
                    }
                    if (this.boundStopDrag) {
                        document.removeEventListener('mouseup', this.boundStopDrag);
                    }
                    
                    // Restaurar cursor e seleção
                    document.body.style.cursor = '';
                    document.body.style.userSelect = '';
                    
                    // Limpar referências
                    this.boundHandleDrag = null;
                    this.boundStopDrag = null;
                },
                
                // Métodos de touch para dispositivos móveis
                startDragTouch(id, event) {
                    event.preventDefault();
                    event.stopPropagation();
                    
                    const touch = event.touches[0];
                    if (!touch) return;
                    
                    console.log('Iniciando drag touch da assinatura:', id);
                    
                    this.isDragging = true;
                    this.dragSignatureId = id;
                    this.dragStartX = touch.clientX;
                    this.dragStartY = touch.clientY;

                    const signature = this.signatures.find(sig => sig.id === id);
                    if (signature) {
                        this.dragStartSigX = signature.x;
                        this.dragStartSigY = signature.y;
                    }

                    this.boundHandleDragTouch = this.handleDragTouch.bind(this);
                    this.boundStopDragTouch = this.stopDragTouch.bind(this);

                    document.addEventListener('touchmove', this.boundHandleDragTouch, { passive: false });
                    document.addEventListener('touchend', this.boundStopDragTouch);
                },

                handleDragTouch(event) {
                    if (!this.isDragging) return;
                    
                    event.preventDefault();
                    
                    const touch = event.touches[0];
                    if (!touch) return;
                    
                    const deltaX = touch.clientX - this.dragStartX;
                    const deltaY = touch.clientY - this.dragStartY;
                    
                    const signature = this.signatures.find(sig => sig.id === this.dragSignatureId);
                    if (signature) {
                        const newX = this.dragStartSigX + deltaX;
                        const newY = this.dragStartSigY + deltaY;
                        
                        const canvas = document.getElementById('pdfCanvas');
                        if (canvas) {
                            const maxX = canvas.width - signature.width;
                            const maxY = canvas.height - signature.height;
                            
                            signature.x = Math.max(0, Math.min(newX, maxX));
                            signature.y = Math.max(0, Math.min(newY, maxY));
                        } else {
                            signature.x = newX;
                            signature.y = newY;
                        }
                        
                        this.$nextTick();
                    }
                },
                
                stopDragTouch() {
                    if (!this.isDragging) return;
                    
                    console.log('Finalizando drag touch');
                    
                    this.isDragging = false;
                    this.dragSignatureId = null;
                    
                    if (this.boundHandleDragTouch) {
                        document.removeEventListener('touchmove', this.boundHandleDragTouch);
                    }
                    if (this.boundStopDragTouch) {
                        document.removeEventListener('touchend', this.boundStopDragTouch);
                    }
                    
                    this.boundHandleDragTouch = null;
                    this.boundStopDragTouch = null;
                },
                
                startResize(id, event) {
                    event.preventDefault();
                    event.stopPropagation();
                    
                    console.log('Iniciando resize da assinatura:', id);
                    
                    this.isResizing = true;
                    this.dragSignatureId = id;
                    this.dragStartX = event.clientX;
                    this.dragStartY = event.clientY;
                    
                    const signature = this.signatures.find(sig => sig.id === id);
                    if (signature) {
                        this.dragStartSigWidth = signature.width;
                        this.dragStartSigHeight = signature.height;
                    }
                    
                    // Bind os métodos para manter o contexto 'this'
                    this.boundHandleResize = this.handleResize.bind(this);
                    this.boundStopResize = this.stopResize.bind(this);
                    
                    document.addEventListener('mousemove', this.boundHandleResize);
                    document.addEventListener('mouseup', this.boundStopResize);
                    
                    // Indicar que está redimensionando
                    document.body.style.cursor = 'se-resize';
                    document.body.style.userSelect = 'none';
                },
                
                handleResize(event) {
                    if (!this.isResizing) return;
                    
                    event.preventDefault();
                    
                    const deltaX = event.clientX - this.dragStartX;
                    const deltaY = event.clientY - this.dragStartY;
                    
                    const signature = this.signatures.find(sig => sig.id === this.dragSignatureId);
                    if (signature) {
                        signature.width = Math.max(50, this.dragStartSigWidth + deltaX);
                        signature.height = Math.max(20, this.dragStartSigHeight + deltaY);
                        
                        // Forçar atualização do Alpine.js
                        this.$nextTick();
                    }
                },
                
                stopResize() {
                    if (!this.isResizing) return;
                    
                    console.log('Finalizando resize');
                    
                    this.isResizing = false;
                    this.dragSignatureId = null;
                    
                    // Remover event listeners
                    if (this.boundHandleResize) {
                        document.removeEventListener('mousemove', this.boundHandleResize);
                    }
                    if (this.boundStopResize) {
                        document.removeEventListener('mouseup', this.boundStopResize);
                    }
                    
                    // Restaurar cursor e seleção
                    document.body.style.cursor = '';
                    document.body.style.userSelect = '';
                    
                    // Limpar referências
                    this.boundHandleResize = null;
                    this.boundStopResize = null;
                },
                
                // Métodos de resize touch para dispositivos móveis
                startResizeTouch(id, event) {
                    event.preventDefault();
                    event.stopPropagation();
                    
                    const touch = event.touches[0];
                    if (!touch) return;
                    
                    console.log('Iniciando resize touch da assinatura:', id);
                    
                    this.isResizing = true;
                    this.dragSignatureId = id;
                    this.dragStartX = touch.clientX;
                    this.dragStartY = touch.clientY;
                    
                    const signature = this.signatures.find(sig => sig.id === id);
                    if (signature) {
                        this.dragStartSigWidth = signature.width;
                        this.dragStartSigHeight = signature.height;
                    }
                    
                    this.boundHandleResizeTouch = this.handleResizeTouch.bind(this);
                    this.boundStopResizeTouch = this.stopResizeTouch.bind(this);
                    
                    document.addEventListener('touchmove', this.boundHandleResizeTouch, { passive: false });
                    document.addEventListener('touchend', this.boundStopResizeTouch);
                },
                
                handleResizeTouch(event) {
                    if (!this.isResizing) return;
                    
                    event.preventDefault();
                    
                    const touch = event.touches[0];
                    if (!touch) return;
                    
                    const deltaX = touch.clientX - this.dragStartX;
                    const deltaY = touch.clientY - this.dragStartY;
                    
                    const signature = this.signatures.find(sig => sig.id === this.dragSignatureId);
                    if (signature) {
                        signature.width = Math.max(50, this.dragStartSigWidth + deltaX);
                        signature.height = Math.max(20, this.dragStartSigHeight + deltaY);
                        
                        this.$nextTick();
                    }
                },
                
                stopResizeTouch() {
                    if (!this.isResizing) return;
                    
                    console.log('Finalizando resize touch');
                    
                    this.isResizing = false;
                    this.dragSignatureId = null;
                    
                    if (this.boundHandleResizeTouch) {
                        document.removeEventListener('touchmove', this.boundHandleResizeTouch);
                    }
                    if (this.boundStopResizeTouch) {
                        document.removeEventListener('touchend', this.boundStopResizeTouch);
                    }
                    
                    this.boundHandleResizeTouch = null;
                    this.boundStopResizeTouch = null;
                },
                 async signDocument() {
                    console.log('=== INICIANDO ASSINATURA DE DOCUMENTO ===');
                    console.log('Assinaturas disponíveis:', this.signatures.length);
                    console.log('PDF carregado:', !!this.pdfDocument);
                    console.log('Páginas do PDF:', this.pdfPages);
                    console.log('Arquivo selecionado:', this.selectedFile?.name);
                    
                    // Verificar se já está processando para evitar múltiplas submissões
                    if (this.processing) {
                        console.log('Já está processando, ignorando nova tentativa');
                        return;
                    }
                    
                    if (this.signatures.length === 0) {
                        alert('Adicione pelo menos uma assinatura antes de finalizar o documento.');
                        return;
                    }

                    if (!this.selectedFile) {
                        alert('Nenhum arquivo PDF selecionado.');
                        return;
                    }

                    if (!this.pdfDocument) {
                        alert('PDF não carregado corretamente. Recarregue o documento e tente novamente.');
                        return;
                    }
                    
                    if (!this.pdfPages || this.pdfPages < 1) {
                        alert('Número de páginas do PDF inválido. Recarregue o documento.');
                        return;
                    }
                    
                    // Validação extra dos dados antes de enviar
                    if (!this.selectedFile || this.selectedFile.size === 0) {
                        alert('Arquivo PDF inválido. Selecione um novo arquivo.');
                        return;
                    }
                    
                    if (this.selectedFile.size > 10 * 1024 * 1024) {
                        alert('Arquivo muito grande (máx. 10MB). Selecione um arquivo menor.');
                        return;
                    }
                    
                    this.processing = true;
                    this.result = null;
                    
                    try {
                        // Limpar e validar dados das assinaturas antes de enviar
                        const cleanSignatures = this.signatures.map((signature, index) => {
                            console.log(`Processando assinatura ${index + 1}:`, signature);
                            
                            const clean = {
                                id: signature.id || Date.now() + Math.random(),
                                type: String(signature.type || '').trim(),
                                data: String(signature.data || '').trim(),
                                x: Math.round(Math.max(0, Number(signature.x) || 0)),
                                y: Math.round(Math.max(0, Number(signature.y) || 0)),
                                width: Math.round(Math.max(50, Number(signature.width) || 150)),
                                height: Math.round(Math.max(30, Number(signature.height) || 50)),
                                page: Math.max(1, Math.min(Number(signature.page) || 1, Number(this.pdfPages) || 1))
                            };
                            
                            // Adicionar fontSize apenas para assinaturas de texto
                            if (signature.type === 'text' && signature.fontSize) {
                                clean.fontSize = Math.round(Math.max(10, Math.min(40, Number(signature.fontSize) || 16)));
                            }
                            
                            // Validação rigorosa dos dados
                            if (!clean.type || !['text', 'draw', 'upload'].includes(clean.type)) {
                                throw new Error(`Tipo de assinatura inválido: ${clean.type}`);
                            }
                            
                            if (!clean.data || clean.data === '') {
                                throw new Error(`Dados da assinatura ${index + 1} estão vazios`);
                            }
                            
                            if (clean.type === 'text' && clean.data.length < 1) {
                                throw new Error(`Texto da assinatura ${index + 1} não pode estar vazio`);
                            }
                            
                            if ((clean.type === 'draw' || clean.type === 'upload') && 
                                !clean.data.startsWith('data:image/')) {
                                throw new Error(`Dados de imagem inválidos para assinatura ${index + 1}`);
                            }
                            
                            // Validar tamanho dos dados base64 (não deve ser muito grande)
                            if ((clean.type === 'draw' || clean.type === 'upload') && 
                                clean.data.length > 1000000) { // 1MB limite para base64
                                throw new Error(`Imagem da assinatura ${index + 1} muito grande. Use uma imagem menor.`);
                            }
                            
                            // Validar posição e dimensões
                            if (clean.x < 0 || clean.y < 0) {
                                throw new Error(`Posição inválida para assinatura ${index + 1}`);
                            }
                            
                            if (clean.width < 10 || clean.height < 10) {
                                throw new Error(`Dimensões muito pequenas para assinatura ${index + 1}`);
                            }
                            
                            if (clean.page < 1 || clean.page > (this.pdfPages || 1)) {
                                throw new Error(`Página inválida para assinatura ${index + 1}: ${clean.page}`);
                            }
                            
                            console.log(`Assinatura ${index + 1} limpa:`, clean);
                            return clean;
                        });
                        
                        // Validar se há assinaturas válidas
                        if (cleanSignatures.length === 0) {
                            throw new Error('Nenhuma assinatura válida encontrada');
                        }
                        
                        console.log('Enviando assinaturas limpas:', cleanSignatures);
                        console.log('PDF Pages:', this.pdfPages, 'Type:', typeof this.pdfPages);
                        console.log('Selected File:', this.selectedFile?.name, 'Size:', this.selectedFile?.size);
                        
                        // Validação extra do pdf_pages
                        const pdfPagesValue = parseInt(this.pdfPages);
                        if (!pdfPagesValue || pdfPagesValue < 1) {
                            throw new Error('Número de páginas do PDF inválido: ' + this.pdfPages);
                        }
                        
                        // Criar AbortController para poder cancelar a requisição se necessário
                        const abortController = new AbortController();
                        
                        // Configurar timeout de 60 segundos para uploads grandes
                        const timeoutId = setTimeout(() => {
                            abortController.abort();
                        }, 60000);
                        
                        // Criar novo FormData para garantir que está limpo
                        const formData = new FormData();
                        
                        // Adicionar dados um por vez com verificação
                        if (!this.selectedFile) {
                            throw new Error('Arquivo PDF não disponível');
                        }
                        formData.append('pdf_file', this.selectedFile);
                        
                        const signaturesJson = JSON.stringify(cleanSignatures);
                        if (!signaturesJson || signaturesJson === '[]') {
                            throw new Error('Dados de assinaturas inválidos');
                        }
                        formData.append('signatures', signaturesJson);
                        formData.append('pdf_pages', String(pdfPagesValue)); // Garantir que seja string válida
                        
                        // Log dos dados do FormData
                        console.log('FormData contents:');
                        try {
                            for (let [key, value] of formData.entries()) {
                                if (key === 'pdf_file') {
                                    console.log(key + ':', {
                                        name: value.name,
                                        size: value.size,
                                        type: value.type,
                                        lastModified: value.lastModified
                                    });
                                } else {
                                    const preview = typeof value === 'string' && value.length > 100 
                                        ? value.substring(0, 100) + '...' 
                                        : value;
                                    console.log(key + ':', { type: typeof value, preview });
                                }
                            }
                        } catch (e) {
                            console.warn('Erro ao fazer log do FormData:', e);
                        }
                        
                        console.log('Enviando requisição para o backend...');
                        
                        // Obter CSRF token
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        if (!csrfToken) {
                            throw new Error('Token CSRF não encontrado. Recarregue a página.');
                        }
                        
                        const response = await fetch('/tools/electronic-signature', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            signal: abortController.signal,
                            // Adicionar configurações para evitar problemas de network
                            cache: 'no-cache',
                            credentials: 'same-origin'
                        });
                        
                        console.log('Status da resposta:', response.status);
                        
                        if (!response.ok) {
                            let errorText;
                            try {
                                const errorJson = await response.json();
                                errorText = errorJson.message || 'Erro desconhecido';
                                console.error('Erro JSON:', errorJson);
                            } catch (e) {
                                errorText = await response.text();
                                console.error('Erro Text:', errorText);
                            }
                            
                            // Melhorar mensagens de erro baseadas no status
                            let userMessage = errorText;
                            if (response.status === 422) {
                                userMessage = 'Erro de validação: ' + errorText;
                            } else if (response.status === 413) {
                                userMessage = 'Arquivo muito grande. Tente com um PDF menor.';
                            } else if (response.status === 500) {
                                userMessage = 'Erro interno do servidor. Tente novamente.';
                            }
                            
                            throw new Error(`Erro do servidor (${response.status}): ${userMessage}`);
                        }
                        
                        const result = await response.json();
                        console.log('Resposta do backend:', result);
                        
                        if (result.success) {
                            this.result = {
                                success: true,
                                message: 'Documento assinado com sucesso!',
                                download_url: result.download_url,
                                filename: result.filename,
                                size: this.formatFileSize(result.file_size)
                            };
                            
                            this.showTemporaryMessage('PDF assinado com sucesso!', 'success');
                        } else {
                            throw new Error(result.message || 'Erro ao processar documento');
                        }
                        
                    } catch (error) {
                        console.error('Erro detalhado ao assinar documento:', error);
                        
                        let errorMessage = 'Erro ao processar o documento. Tente novamente.';
                        
                        // Verificar se foi cancelado pelo AbortController
                        if (error.name === 'AbortError') {
                            errorMessage = 'Operação cancelada pelo usuário.';
                        } else if (error.message.includes('Body is disturbed')) {
                            errorMessage = 'Erro de comunicação. Tente recarregar a página e fazer o upload novamente.';
                        } else if (error.message.includes('pattern') || error.message.includes('validation')) {
                            errorMessage = 'Erro de validação dos dados. Verifique se todas as assinaturas estão corretas e tente novamente.';
                        } else if (error.message.includes('Token CSRF não encontrado')) {
                            errorMessage = 'Sessão expirada. Recarregue a página e tente novamente.';
                        } else if (error.message.includes('Arquivo PDF não disponível')) {
                            errorMessage = 'Arquivo PDF perdido. Selecione o arquivo novamente.';
                        } else if (error.message.includes('Assinatura de texto não pode estar vazia')) {
                            errorMessage = 'Uma ou mais assinaturas de texto estão vazias. Verifique e tente novamente.';
                        } else if (error.message.includes('Dados de imagem inválidos')) {
                            errorMessage = 'Uma ou mais imagens de assinatura são inválidas. Tente criar as assinaturas novamente.';
                        } else if (error.message.includes('Nenhuma assinatura válida')) {
                            errorMessage = 'Nenhuma assinatura válida encontrada. Adicione pelo menos uma assinatura.';
                        } else if (error.message.includes('413')) {
                            errorMessage = 'Arquivo muito grande. Tente com um PDF menor.';
                        } else if (error.message.includes('422')) {
                            errorMessage = 'Dados inválidos. Verifique as assinaturas e tente novamente.';
                        } else if (error.message.includes('500')) {
                            errorMessage = 'Erro interno do servidor. Tente novamente em alguns instantes.';
                        } else if (error.message.includes('pdf_pages')) {
                            errorMessage = 'Erro na contagem de páginas do PDF. Recarregue o documento e tente novamente.';
                        } else if (error.message) {
                            errorMessage = error.message;
                        }
                        
                        this.result = {
                            success: false,
                            message: errorMessage
                        };
                        
                        this.showTemporaryMessage(errorMessage, 'error');
                    } finally {
                        this.processing = false;
                        // Limpar timeout se ainda estiver ativo
                        if (timeoutId) {
                            clearTimeout(timeoutId);
                        }
                    }
                },

                // Formatar tamanho do arquivo
                formatFileSize(bytes) {
                    if (!bytes) return '0 B';
                    const k = 1024;
                    const sizes = ['B', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                },
                
                // Mostrar mensagem temporária
                showTemporaryMessage(message, type = 'info') {
                    const toast = document.createElement('div');
                    toast.className = `fixed top-4 right-4 z-50 px-4 py-2 rounded-lg text-white font-medium transform transition-all duration-300 ${
                        type === 'success' ? 'bg-green-600' : 
                        type === 'error' ? 'bg-red-600' : 
                        type === 'warning' ? 'bg-yellow-600' : 'bg-blue-600'
                    }`;
                    toast.textContent = message;
                    toast.style.transform = 'translateX(100%)';
                    
                    document.body.appendChild(toast);
                    
                    // Animar entrada
                    setTimeout(() => {
                        toast.style.transform = 'translateX(0)';
                    }, 100);
                    
                    // Remover após 3 segundos
                    setTimeout(() => {
                        toast.style.transform = 'translateX(100%)';
                        setTimeout(() => {
                            if (toast.parentNode) {
                                toast.parentNode.removeChild(toast);
                            }
                        }, 300);
                    }, 3000);
                },
                
                // Navegar entre páginas do PDF
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
                
                // Controles de zoom
                async zoomIn() {
                    this.zoomLevel = Math.min(this.zoomLevel + 0.25, 3);
                    await this.renderPage();
                },
                
                async zoomOut() {
                    this.zoomLevel = Math.max(this.zoomLevel - 0.25, 0.5);
                    await this.renderPage();
                },
                
                async resetZoom() {
                    this.zoomLevel = 1;
                    await this.renderPage();
                },
            }
        }
    </script>
</body>
</html>
