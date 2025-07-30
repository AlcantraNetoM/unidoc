<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Editor Avançado de PDF - UNIDOC</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Essential styles -->
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f9fafb; }
        
        .container { height: 100vh; display: flex; flex-direction: column; }
        .header { background: white; border-bottom: 1px solid #e5e7eb; padding: 1rem; }
        .header-content { display: flex; justify-content: space-between; align-items: center; }
        .back-link { color: #1d4ed8; text-decoration: none; font-size: 0.875rem; }
        .back-link:hover { color: #1e40af; }
        .title { color: #374151; font-weight: 500; }
        .btn { padding: 0.5rem 1rem; border: none; border-radius: 0.375rem; font-size: 0.875rem; cursor: pointer; }
        .btn-primary { background: #2563eb; color: white; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-primary:disabled { opacity: 0.5; cursor: not-allowed; }
        .btn-secondary { background: #6b7280; color: white; }
        .btn-secondary:hover { background: #4b5563; }
        
        .toolbar { background: white; border-bottom: 1px solid #e5e7eb; padding: 0.5rem; }
        .toolbar-content { display: flex; justify-content: center; gap: 1.5rem; }
        .tool-btn { display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border: none; border-radius: 0.5rem; background: transparent; color: #4b5563; cursor: pointer; }
        .tool-btn:hover { background: #f3f4f6; }
        .tool-btn.active { background: #dbeafe; color: #1d4ed8; }
        
        .main { flex: 1; display: flex; overflow: hidden; }
        .viewer { flex: 1; display: flex; flex-direction: column; }
        .viewer-content { flex: 1; background: #f3f4f6; overflow: auto; padding: 1.5rem; }
        .sidebar { width: 16rem; background: white; border-left: 1px solid #e5e7eb; padding: 1rem; }
        
        .upload-area { height: 100%; display: flex; align-items: center; justify-content: center; text-align: center; }
        .upload-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background: white; color: #374151; text-decoration: none; cursor: pointer; }
        .upload-btn:hover { background: #f9fafb; }
        
        .canvas-container { display: flex; justify-content: center; }
        .canvas-wrapper { position: relative; }
        .pdf-canvas { background: white; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        .drawing-canvas { position: absolute; top: 0; left: 0; border-radius: 0.5rem; pointer-events: none; }
        .drawing-canvas.active { pointer-events: all; }
        
        .loading { height: 100%; display: flex; align-items: center; justify-content: center; flex-direction: column; gap: 1rem; }
        .spinner { width: 2rem; height: 2rem; border: 2px solid #e5e7eb; border-top: 2px solid #2563eb; border-radius: 50%; animation: spin 1s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        
        .error { height: 100%; display: flex; align-items: center; justify-content: center; flex-direction: column; gap: 1rem; color: #dc2626; text-align: center; }
        
        .properties { display: none; }
        .properties.show { display: block; }
        .property-group { margin-bottom: 1rem; }
        .property-label { display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem; }
        .property-input { width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; }
        .property-textarea { width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; resize: vertical; min-height: 60px; }
        .property-range { width: 100%; margin-bottom: 0.25rem; }
        .property-color { width: 100%; height: 2.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; cursor: pointer; }
        .color-picker-group { display: flex; gap: 0.5rem; align-items: center; }
        .color-preset { width: 1.5rem; height: 1.5rem; border-radius: 0.25rem; border: 2px solid #e5e7eb; cursor: pointer; transition: all 0.2s; }
        .color-preset:hover { transform: scale(1.1); border-color: #374151; }
        .color-preset.active { border-color: #2563eb; border-width: 3px; }
        .range-display { display: flex; justify-content: between; align-items: center; font-size: 0.75rem; color: #6b7280; }
        .font-family-group { display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; margin-top: 0.5rem; }
        .font-btn { padding: 0.25rem 0.5rem; border: 1px solid #d1d5db; border-radius: 0.25rem; background: white; font-size: 0.75rem; cursor: pointer; text-align: center; }
        .font-btn:hover { background: #f3f4f6; }
        .font-btn.active { background: #dbeafe; border-color: #2563eb; color: #1d4ed8; }
        .text-effects { display: flex; gap: 0.5rem; margin-top: 0.5rem; }
        .effect-btn { padding: 0.25rem 0.5rem; border: 1px solid #d1d5db; border-radius: 0.25rem; background: white; font-size: 0.75rem; cursor: pointer; }
        .effect-btn:hover { background: #f3f4f6; }
        .effect-btn.active { background: #dbeafe; border-color: #2563eb; color: #1d4ed8; }
        .brush-preview { width: 100%; height: 40px; border: 1px solid #d1d5db; border-radius: 0.375rem; background: white; margin-top: 0.5rem; }
        .highlight-style { display: flex; gap: 0.5rem; margin-top: 0.5rem; }
        .style-btn { padding: 0.25rem 0.5rem; border: 1px solid #d1d5db; border-radius: 0.25rem; background: white; font-size: 0.75rem; cursor: pointer; }
        .style-btn:hover { background: #f3f4f6; }
        .style-btn.active { background: #dbeafe; border-color: #2563eb; color: #1d4ed8; }
        
        .hidden { display: none !important; }
        .debug { position: fixed; bottom: 1rem; right: 1rem; background: rgba(0,0,0,0.8); color: white; padding: 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; max-width: 20rem; z-index: 1000; }
        
        /* Text box styles */
        .text-box {
            position: absolute;
            border: 2px dashed #2563eb;
            background: rgba(37, 99, 235, 0.1);
            min-width: 100px;
            min-height: 30px;
            cursor: move;
            z-index: 100;
        }
        
        .text-box.active {
            border-color: #1d4ed8;
            background: rgba(29, 78, 216, 0.15);
        }
        
        .text-box-input {
            width: 100%;
            height: 100%;
            border: none;
            background: transparent;
            outline: none;
            resize: none;
            padding: 4px;
            font-family: inherit;
            color: inherit;
            overflow: hidden;
        }
        
        .text-box-controls {
            position: absolute;
            top: -30px;
            right: 0;
            display: flex;
            gap: 4px;
            background: white;
            border-radius: 4px;
            padding: 2px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        
        .text-box-btn {
            width: 24px;
            height: 24px;
            border: none;
            background: transparent;
            cursor: pointer;
            border-radius: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }
        
        .text-box-btn:hover {
            background: #f3f4f6;
        }
        
        .text-box-resize {
            position: absolute;
            bottom: -5px;
            right: -5px;
            width: 10px;
            height: 10px;
            background: #2563eb;
            cursor: se-resize;
            border-radius: 2px;
        }
    </style>
    
    <!-- PDF.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        // Configure PDF.js with better error handling
        if (typeof pdfjsLib !== 'undefined') {
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
            console.log('✅ PDF.js configurado');
        }
    </script>
    
    <!-- PDF-lib library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf-lib/1.17.1/pdf-lib.min.js"></script>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <a href="{{ route('landing') }}" class="back-link">← Voltar</a>
                    <span style="color: #d1d5db;">|</span>
                    <span class="title">Editor de PDF</span>
                </div>
                <button id="saveBtn" class="btn btn-primary" disabled onclick="downloadPdf()">
                    Guardar
                </button>
            </div>
        </div>

        <!-- Toolbar -->
        <div class="toolbar">
            <div class="toolbar-content">
                <button class="tool-btn" onclick="setTool('text')" data-tool="text">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                    </svg>
                    Adicionar texto
                </button>
                <button class="tool-btn" onclick="setTool('draw')" data-tool="draw">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                    Desenhar
                </button>
                <button class="tool-btn" onclick="setTool('highlight')" data-tool="highlight">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 003.361-6.867 8.21 8.21 0 003 2.48z"/>
                    </svg>
                    Destacar
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main">
            <!-- Viewer -->
            <div class="viewer">
                <div class="viewer-content">
                    <!-- Upload Area -->
                    <div id="uploadArea" class="upload-area">
                        <div>
                            <input type="file" id="pdfInput" accept=".pdf" style="display: none;" onchange="loadPdf(event)">
                            <label for="pdfInput" class="upload-btn">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                Selecionar PDF
                            </label>
                            <p style="margin-top: 0.5rem; color: #6b7280; font-size: 0.875rem;">Escolha um arquivo PDF para editar</p>
                        </div>
                    </div>

                    <!-- Loading -->
                    <div id="loadingArea" class="loading hidden">
                        <div class="spinner"></div>
                        <p style="color: #6b7280;">Carregando PDF...</p>
                    </div>

                    <!-- Error -->
                    <div id="errorArea" class="error hidden">
                        <p style="font-weight: 500;">Erro ao carregar PDF</p>
                        <p id="errorMessage" style="font-size: 0.875rem; margin-top: 0.5rem;"></p>
                        <button class="btn btn-secondary" onclick="resetEditor()" style="margin-top: 1rem;">
                            Tentar Novamente
                        </button>
                    </div>

                    <!-- Canvas -->
                    <div id="canvasArea" class="canvas-container hidden">
                        <div class="canvas-wrapper">
                            <canvas id="pdfCanvas" class="pdf-canvas"></canvas>
                            <canvas id="drawingCanvas" class="drawing-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div id="sidebar" class="sidebar hidden">
                <h3 style="font-size: 0.875rem; font-weight: 600; color: #111827; margin-bottom: 1rem;">Propriedades</h3>
                
                <!-- Text Properties -->
                <div id="textProperties" class="properties">
                    <div class="property-group">
                        <label class="property-label">Texto</label>
                        <textarea id="textInput" class="property-textarea" rows="3" placeholder="Digite o texto..."></textarea>
                    </div>
                    
                    <div class="property-group">
                        <label class="property-label">Fonte</label>
                        <select id="fontFamily" class="property-input">
                            <option value="Arial">Arial</option>
                            <option value="Helvetica">Helvetica</option>
                            <option value="Times New Roman">Times New Roman</option>
                            <option value="Courier New">Courier New</option>
                            <option value="Georgia">Georgia</option>
                            <option value="Verdana">Verdana</option>
                        </select>
                    </div>
                    
                    <div class="property-group">
                        <label class="property-label">Tamanho</label>
                        <div class="range-display">
                            <input type="range" id="fontSize" class="property-range" min="8" max="72" value="16">
                            <span id="fontSizeValue">16px</span>
                        </div>
                        <div class="font-family-group">
                            <button class="font-btn" onclick="setFontSize(12)">12px</button>
                            <button class="font-btn active" onclick="setFontSize(16)">16px</button>
                            <button class="font-btn" onclick="setFontSize(20)">20px</button>
                            <button class="font-btn" onclick="setFontSize(24)">24px</button>
                        </div>
                    </div>
                    
                    <div class="property-group">
                        <label class="property-label">Cor</label>
                        <div class="color-picker-group">
                            <input type="color" id="textColor" class="property-color" value="#000000">
                            <div style="display: flex; gap: 0.25rem;">
                                <div class="color-preset active" style="background: #000000;" onclick="setTextColor('#000000')"></div>
                                <div class="color-preset" style="background: #dc2626;" onclick="setTextColor('#dc2626')"></div>
                                <div class="color-preset" style="background: #2563eb;" onclick="setTextColor('#2563eb')"></div>
                                <div class="color-preset" style="background: #059669;" onclick="setTextColor('#059669')"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="property-group">
                        <label class="property-label">Efeitos</label>
                        <div class="text-effects">
                            <button class="effect-btn" onclick="toggleTextEffect('bold')" data-effect="bold">
                                <strong>B</strong>
                            </button>
                            <button class="effect-btn" onclick="toggleTextEffect('italic')" data-effect="italic">
                                <em>I</em>
                            </button>
                            <button class="effect-btn" onclick="toggleTextEffect('underline')" data-effect="underline">
                                <u>U</u>
                            </button>
                        </div>
                    </div>
                    
                    <div class="property-group">
                        <label class="property-label">Rotação</label>
                        <div class="range-display">
                            <input type="range" id="textRotation" class="property-range" min="0" max="360" value="0">
                            <span id="textRotationValue">0°</span>
                        </div>
                    </div>
                </div>

                <!-- Draw Properties -->
                <div id="drawProperties" class="properties">
                    <div class="property-group">
                        <label class="property-label">Cor</label>
                        <div class="color-picker-group">
                            <input type="color" id="drawColor" class="property-color" value="#000000">
                            <div style="display: flex; gap: 0.25rem;">
                                <div class="color-preset active" style="background: #000000;" onclick="setDrawColor('#000000')"></div>
                                <div class="color-preset" style="background: #dc2626;" onclick="setDrawColor('#dc2626')"></div>
                                <div class="color-preset" style="background: #2563eb;" onclick="setDrawColor('#2563eb')"></div>
                                <div class="color-preset" style="background: #059669;" onclick="setDrawColor('#059669')"></div>
                                <div class="color-preset" style="background: #7c3aed;" onclick="setDrawColor('#7c3aed')"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="property-group">
                        <label class="property-label">Espessura</label>
                        <div class="range-display">
                            <input type="range" id="lineWidth" class="property-range" min="1" max="20" value="2">
                            <span id="lineWidthValue">2px</span>
                        </div>
                    </div>
                    
                    <div class="property-group">
                        <label class="property-label">Estilo da Linha</label>
                        <div class="highlight-style">
                            <button class="style-btn active" onclick="setLineStyle('solid')" data-style="solid">Sólida</button>
                            <button class="style-btn" onclick="setLineStyle('dashed')" data-style="dashed">Tracejada</button>
                            <button class="style-btn" onclick="setLineStyle('dotted')" data-style="dotted">Pontilhada</button>
                        </div>
                    </div>
                    
                    <div class="property-group">
                        <label class="property-label">Opacidade</label>
                        <div class="range-display">
                            <input type="range" id="drawOpacity" class="property-range" min="0.1" max="1" step="0.1" value="1">
                            <span id="drawOpacityValue">100%</span>
                        </div>
                    </div>
                    
                    <div class="property-group">
                        <label class="property-label">Pré-visualização</label>
                        <canvas id="brushPreview" class="brush-preview" width="200" height="40"></canvas>
                    </div>
                </div>

                <!-- Highlight Properties -->
                <div id="highlightProperties" class="properties">
                    <div class="property-group">
                        <label class="property-label">Cor</label>
                        <div class="color-picker-group">
                            <input type="color" id="highlightColor" class="property-color" value="#ffff00">
                            <div style="display: flex; gap: 0.25rem;">
                                <div class="color-preset active" style="background: #ffff00;" onclick="setHighlightColor('#ffff00')"></div>
                                <div class="color-preset" style="background: #fbbf24;" onclick="setHighlightColor('#fbbf24')"></div>
                                <div class="color-preset" style="background: #34d399;" onclick="setHighlightColor('#34d399')"></div>
                                <div class="color-preset" style="background: #60a5fa;" onclick="setHighlightColor('#60a5fa')"></div>
                                <div class="color-preset" style="background: #f472b6;" onclick="setHighlightColor('#f472b6')"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="property-group">
                        <label class="property-label">Estilo</label>
                        <div class="highlight-style">
                            <button class="style-btn active" onclick="setHighlightStyle('classic')" data-style="classic">Clássico</button>
                            <button class="style-btn" onclick="setHighlightStyle('marker')" data-style="marker">Marcador</button>
                            <button class="style-btn" onclick="setHighlightStyle('underline')" data-style="underline">Sublinhado</button>
                        </div>
                    </div>
                    
                    <div class="property-group">
                        <label class="property-label">Espessura</label>
                        <div class="range-display">
                            <input type="range" id="highlightWidth" class="property-range" min="10" max="50" value="20">
                            <span id="highlightWidthValue">20px</span>
                        </div>
                    </div>
                    
                    <div class="property-group">
                        <label class="property-label">Opacidade</label>
                        <div class="range-display">
                            <input type="range" id="highlightOpacity" class="property-range" min="0.1" max="1" step="0.1" value="0.5">
                            <span id="highlightOpacityValue">50%</span>
                        </div>
                    </div>
                    
                    <div class="property-group">
                        <label class="property-label">Intensidade</label>
                        <div class="range-display">
                            <input type="range" id="highlightIntensity" class="property-range" min="0.2" max="0.8" step="0.1" value="0.5">
                            <span id="highlightIntensityValue">50%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Debug info -->
    <div id="debugInfo" class="debug hidden"></div>

    <script>
        // Global state
        let pdfDocument = null;
        let pdfLibDocument = null;
        let currentTool = null;
        let isDrawing = false;
        let lastX = 0, lastY = 0;
        
        // Enhanced state for new features
        let textEffects = { bold: false, italic: false, underline: false };
        let currentLineStyle = 'solid';
        let currentHighlightStyle = 'classic';
        let brushPreviewContext = null;
        let activeTextBox = null;
        let textBoxes = [];
        let isDragging = false;
        let isResizing = false;
        let dragStartX = 0;
        let dragStartY = 0;
        let renderedTexts = []; // Store data for all rendered text objects

        // Elements
        let pdfCanvas, drawingCanvas, pdfContext, drawingContext;

        // Debug function
        function debug(message) {
            console.log(message);
            const debugDiv = document.getElementById('debugInfo');
            debugDiv.textContent = new Date().toLocaleTimeString() + ': ' + message;
            debugDiv.classList.remove('hidden');
            setTimeout(() => debugDiv.classList.add('hidden'), 3000);
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            debug('Página carregada, inicializando...');
            
            // Setup canvas elements
            pdfCanvas = document.getElementById('pdfCanvas');
            drawingCanvas = document.getElementById('drawingCanvas');
            
            if (pdfCanvas && drawingCanvas) {
                pdfContext = pdfCanvas.getContext('2d');
                drawingContext = drawingCanvas.getContext('2d');
                debug('Canvas configurados');
            } else {
                debug('Erro: Canvas não encontrados');
            }

            // Setup brush preview
            const brushPreview = document.getElementById('brushPreview');
            if (brushPreview) {
                brushPreviewContext = brushPreview.getContext('2d');
                updateBrushPreview();
            }

            // Setup event listeners
            setupEventListeners();
            
            // Update range displays
            updateRangeDisplays();
        });

        function setupEventListeners() {
            // Drawing events
            drawingCanvas.addEventListener('mousedown', startDrawing);
            drawingCanvas.addEventListener('mousemove', draw);
            drawingCanvas.addEventListener('mouseup', stopDrawing);
            drawingCanvas.addEventListener('mouseout', stopDrawing);
            drawingCanvas.addEventListener('click', handleCanvasClick);

            // Listen for property changes to update the active text box
            document.getElementById('textInput').addEventListener('input', updateTextBoxStyle);
            document.getElementById('fontFamily').addEventListener('change', updateTextBoxStyle);
            document.getElementById('textColor').addEventListener('input', updateTextBoxStyle);


            // Enhanced range input updates - check if elements exist
            const lineWidthEl = document.getElementById('lineWidth');
            const drawOpacityEl = document.getElementById('drawOpacity');
            const drawColorEl = document.getElementById('drawColor');
            const highlightOpacityEl = document.getElementById('highlightOpacity');
            const highlightWidthEl = document.getElementById('highlightWidth');
            const highlightIntensityEl = document.getElementById('highlightIntensity');
            const fontSizeEl = document.getElementById('fontSize');
            const textRotationEl = document.getElementById('textRotation');
            
            if (lineWidthEl) {
                lineWidthEl.addEventListener('input', function() {
                    updateRangeDisplays();
                    updateBrushPreview();
                });
            }
            
            if (drawOpacityEl) {
                drawOpacityEl.addEventListener('input', function() {
                    updateRangeDisplays();
                    updateBrushPreview();
                });
            }
            
            if (drawColorEl) {
                drawColorEl.addEventListener('input', updateBrushPreview);
            }
            
            if (highlightOpacityEl) {
                highlightOpacityEl.addEventListener('input', updateRangeDisplays);
            }
            
            if (highlightWidthEl) {
                highlightWidthEl.addEventListener('input', updateRangeDisplays);
            }
            
            if (highlightIntensityEl) {
                highlightIntensityEl.addEventListener('input', updateRangeDisplays);
            }
            
            if (fontSizeEl) {
                fontSizeEl.addEventListener('input', function() {
                    updateRangeDisplays();
                    updateTextBoxStyle();
                });
            }
            
            if (textRotationEl) {
                textRotationEl.addEventListener('input', function() {
                    updateRangeDisplays();
                    updateTextBoxStyle();
                });
            }
        }

        function updateRangeDisplays() {
            // Drawing properties
            const lineWidthEl = document.getElementById('lineWidth');
            const drawOpacityEl = document.getElementById('drawOpacity');
            if (lineWidthEl && document.getElementById('lineWidthValue')) {
                document.getElementById('lineWidthValue').textContent = lineWidthEl.value + 'px';
            }
            if (drawOpacityEl && document.getElementById('drawOpacityValue')) {
                document.getElementById('drawOpacityValue').textContent = Math.round(drawOpacityEl.value * 100) + '%';
            }
            
            // Highlight properties
            const highlightOpacityEl = document.getElementById('highlightOpacity');
            const highlightWidthEl = document.getElementById('highlightWidth');
            const highlightIntensityEl = document.getElementById('highlightIntensity');
            if (highlightOpacityEl && document.getElementById('highlightOpacityValue')) {
                document.getElementById('highlightOpacityValue').textContent = Math.round(highlightOpacityEl.value * 100) + '%';
            }
            if (highlightWidthEl && document.getElementById('highlightWidthValue')) {
                document.getElementById('highlightWidthValue').textContent = highlightWidthEl.value + 'px';
            }
            if (highlightIntensityEl && document.getElementById('highlightIntensityValue')) {
                document.getElementById('highlightIntensityValue').textContent = Math.round(highlightIntensityEl.value * 100) + '%';
            }
            
            // Text properties
            const fontSizeEl = document.getElementById('fontSize');
            const textRotationEl = document.getElementById('textRotation');
            if (fontSizeEl && document.getElementById('fontSizeValue')) {
                document.getElementById('fontSizeValue').textContent = fontSizeEl.value + 'px';
            }
            if (textRotationEl && document.getElementById('textRotationValue')) {
                document.getElementById('textRotationValue').textContent = textRotationEl.value + '°';
            }
        }

        function showArea(areaId) {
            ['uploadArea', 'loadingArea', 'errorArea', 'canvasArea'].forEach(id => {
                document.getElementById(id).classList.add('hidden');
            });
            document.getElementById(areaId).classList.remove('hidden');
        }

        function showError(message) {
            document.getElementById('errorMessage').textContent = message;
            showArea('errorArea');
        }

        function resetEditor() {
            debug('Resetando editor...');
            
            // Reset state
            pdfDocument = null;
            pdfLibDocument = null;
            currentTool = null;
            textEffects = { bold: false, italic: false, underline: false };
            currentLineStyle = 'solid';
            currentHighlightStyle = 'classic';
            
            // Clear text boxes
            textBoxes.forEach(textBox => textBox.remove());
            textBoxes = [];
            activeTextBox = null;
            isDragging = false;
            isResizing = false;
            
            // Clear canvas
            if (pdfContext) pdfContext.clearRect(0, 0, pdfCanvas.width, pdfCanvas.height);
            if (drawingContext) drawingContext.clearRect(0, 0, drawingCanvas.width, drawingCanvas.height);
            
            // Reset UI
            document.getElementById('pdfInput').value = '';
            document.getElementById('saveBtn').disabled = true;
            document.getElementById('sidebar').classList.add('hidden');
            
            // Clear tool selection
            document.querySelectorAll('.tool-btn').forEach(btn => btn.classList.remove('active'));
            
            // Reset all effect buttons
            document.querySelectorAll('.effect-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.style-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.font-btn').forEach(btn => btn.classList.remove('active'));
            
            // Reset active states for default values
            document.querySelector('.font-btn[onclick="setFontSize(16)"]').classList.add('active');
            document.querySelector('#drawProperties [data-style="solid"]').classList.add('active');
            document.querySelector('#highlightProperties [data-style="classic"]').classList.add('active');
            
            // Update brush preview
            if (brushPreviewContext) {
                brushPreviewContext.clearRect(0, 0, document.getElementById('brushPreview').width, document.getElementById('brushPreview').height);
            }
            
            showArea('uploadArea');
        }

        async function loadPdf(event) {
            const file = event.target.files[0];
            if (!file) return;

            debug(`Carregando: ${file.name} (${file.size} bytes)`);
            showArea('loadingArea');

            try {
                // Validate libraries
                if (typeof pdfjsLib === 'undefined') {
                    throw new Error('PDF.js não carregado');
                }
                if (typeof PDFLib === 'undefined') {
                    throw new Error('PDF-lib não carregado');
                }

                // Read file with better error handling
                debug('Lendo arquivo...');
                const fileReader = new FileReader();
                
                const arrayBuffer = await new Promise((resolve, reject) => {
                    fileReader.onload = e => resolve(e.target.result);
                    fileReader.onerror = e => reject(new Error('Erro ao ler arquivo: ' + e.target.error));
                    fileReader.readAsArrayBuffer(file);
                });

                debug(`Arquivo lido: ${arrayBuffer.byteLength} bytes`);

                // Validate PDF header
                if (arrayBuffer.byteLength < 8) {
                    throw new Error('Arquivo muito pequeno para ser um PDF');
                }

                const header = new TextDecoder('utf-8', { fatal: false }).decode(new Uint8Array(arrayBuffer, 0, 8));
                debug(`Header detectado: "${header}"`);
                
                if (!header.startsWith('%PDF-')) {
                    throw new Error(`Arquivo não é um PDF válido. Header encontrado: "${header}"`);
                }

                // Create completely independent copies to avoid ArrayBuffer detachment
                debug('Criando cópias independentes do buffer...');
                
                // For PDF.js - create a new ArrayBuffer copy
                const pdfJsBuffer = arrayBuffer.slice(0);
                const uint8ArrayPdfJs = new Uint8Array(pdfJsBuffer);
                
                // For PDF-lib - create another independent copy
                const pdfLibBuffer = arrayBuffer.slice(0);
                const uint8ArrayPdfLib = new Uint8Array(pdfLibBuffer);

                // Load with PDF.js first
                debug('Carregando com PDF.js...');
                const loadingTask = pdfjsLib.getDocument({
                    data: uint8ArrayPdfJs,
                    verbosity: 0,
                    disableFontFace: false,
                    disableRange: false,
                    disableStream: false,
                    disableAutoFetch: false,
                    disableCreateObjectURL: false
                });

                pdfDocument = await loadingTask.promise;
                debug(`PDF.js OK: ${pdfDocument.numPages} páginas`);

                // Load with PDF-lib using the independent copy
                debug('Carregando com PDF-lib...');
                pdfLibDocument = await PDFLib.PDFDocument.load(uint8ArrayPdfLib);
                debug('PDF-lib OK');

                // Render first page
                await renderPage(1);

                // Update UI
                document.getElementById('saveBtn').disabled = false;
                showArea('canvasArea');
                debug('✅ PDF carregado com sucesso!');

            } catch (error) {
                console.error('Erro completo:', error);
                debug(`Erro: ${error.message}`);
                
                let friendlyMessage = error.message;
                if (error.message.includes('No PDF header found')) {
                    friendlyMessage = 'Arquivo corrompido ou não é um PDF válido. Tente outro arquivo.';
                } else if (error.message.includes('Invalid PDF')) {
                    friendlyMessage = 'PDF danificado ou protegido por senha.';
                } else if (error.message.includes('detached ArrayBuffer')) {
                    friendlyMessage = 'Erro interno de memória. Tente fazer upload do arquivo novamente.';
                } else if (error.message.includes('fetch')) {
                    friendlyMessage = 'Erro de conexão. Verifique sua internet.';
                } else if (error.message.includes('Cannot perform Construct')) {
                    friendlyMessage = 'Erro de processamento. Tente um PDF diferente ou recarregue a página.';
                }
                
                showError(friendlyMessage);
            }
        }

        async function renderPage(pageNum) {
            if (!pdfDocument || !pdfCanvas || !pdfContext) {
                throw new Error('PDF ou canvas não disponível');
            }

            debug(`Renderizando página ${pageNum}...`);

            const page = await pdfDocument.getPage(pageNum);
            const viewport = page.getViewport({ scale: 1.0 });

            debug(`Viewport: ${viewport.width}x${viewport.height}`);

            // Set canvas dimensions
            pdfCanvas.width = viewport.width;
            pdfCanvas.height = viewport.height;
            drawingCanvas.width = viewport.width;
            drawingCanvas.height = viewport.height;

            // Clear canvases
            pdfContext.clearRect(0, 0, viewport.width, viewport.height);
            drawingContext.clearRect(0, 0, viewport.width, viewport.height);

            // White background
            pdfContext.fillStyle = '#ffffff';
            pdfContext.fillRect(0, 0, viewport.width, viewport.height);

            // Render page
            const renderContext = {
                canvasContext: pdfContext,
                viewport: viewport
            };

            await page.render(renderContext).promise;
            debug('Página renderizada');
        }

        function setTool(tool) {
            currentTool = tool;
            debug(`Ferramenta: ${tool}`);

            // Update button states
            document.querySelectorAll('.tool-btn').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.tool === tool);
            });

            // Update cursor
            if (drawingCanvas) {
                const cursors = {
                    'draw': 'crosshair',
                    'highlight': 'crosshair',
                    'text': 'text'
                };
                drawingCanvas.style.cursor = cursors[tool] || 'default';
                drawingCanvas.classList.toggle('active', ['draw', 'highlight', 'text'].includes(tool));
            }

            // Show/hide sidebar and properties
            if (tool && pdfDocument) {
                document.getElementById('sidebar').classList.remove('hidden');
                
                // Hide all property panels
                document.querySelectorAll('.properties').forEach(p => p.classList.remove('show'));
                
                // Show relevant panel
                const panel = document.getElementById(tool + 'Properties');
                if (panel) panel.classList.add('show');
                
                // Update brush preview when draw tool is selected
                if (tool === 'draw') {
                    updateBrushPreview();
                }
            } else {
                document.getElementById('sidebar').classList.add('hidden');
            }
        }

        function startDrawing(event) {
            if (!['draw', 'highlight'].includes(currentTool)) return;

            isDrawing = true;
            const rect = drawingCanvas.getBoundingClientRect();
            lastX = event.clientX - rect.left;
            lastY = event.clientY - rect.top;

            drawingContext.lineJoin = 'round';
            drawingContext.lineCap = 'round';

            if (currentTool === 'draw') {
                drawingContext.globalCompositeOperation = 'source-over';
                drawingContext.strokeStyle = document.getElementById('drawColor').value;
                drawingContext.lineWidth = document.getElementById('lineWidth').value;
                drawingContext.globalAlpha = document.getElementById('drawOpacity').value;
                
                // Apply line style
                if (currentLineStyle === 'dashed') {
                    drawingContext.setLineDash([10, 10]);
                } else if (currentLineStyle === 'dotted') {
                    drawingContext.setLineDash([3, 6]);
                } else {
                    drawingContext.setLineDash([]);
                }
                
            } else if (currentTool === 'highlight') {
                const style = currentHighlightStyle;
                const width = document.getElementById('highlightWidth').value;
                const opacity = document.getElementById('highlightOpacity').value;
                const intensity = document.getElementById('highlightIntensity').value;
                
                if (style === 'classic') {
                    drawingContext.globalCompositeOperation = 'multiply';
                    drawingContext.lineWidth = width;
                    drawingContext.globalAlpha = intensity;
                } else if (style === 'marker') {
                    drawingContext.globalCompositeOperation = 'source-over';
                    drawingContext.lineWidth = width * 1.5;
                    drawingContext.globalAlpha = opacity;
                    drawingContext.lineCap = 'square';
                } else if (style === 'underline') {
                    drawingContext.globalCompositeOperation = 'source-over';
                    drawingContext.lineWidth = 3;
                    drawingContext.globalAlpha = opacity;
                    drawingContext.lineCap = 'round';
                }
                
                drawingContext.strokeStyle = document.getElementById('highlightColor').value;
                drawingContext.setLineDash([]);
            }
        }

        function draw(event) {
            if (!isDrawing) return;

            const rect = drawingCanvas.getBoundingClientRect();
            const currentX = event.clientX - rect.left;
            const currentY = event.clientY - rect.top;

            drawingContext.beginPath();
            drawingContext.moveTo(lastX, lastY);
            drawingContext.lineTo(currentX, currentY);
            drawingContext.stroke();

            lastX = currentX;
            lastY = currentY;
        }

        function stopDrawing() {
            isDrawing = false;
            if (drawingContext) {
                drawingContext.globalAlpha = 1;
                drawingContext.globalCompositeOperation = 'source-over';
            }
        }

        function handleCanvasClick(event) {
            debug(`Canvas clicado - Ferramenta atual: ${currentTool}`);
            
            if (currentTool === 'text') {
                const rect = drawingCanvas.getBoundingClientRect();
                const x = event.clientX - rect.left;
                const y = event.clientY - rect.top;

                // Check if clicking on an existing text object
                const clickedTextIndex = findClickedText(x, y);
                if (clickedTextIndex > -1) {
                    debug(`Clicou no texto renderizado, index: ${clickedTextIndex}`);
                    editRenderedText(clickedTextIndex);
                    return; // Stop further execution
                }

                // If not clicking on existing text, create a new box
                const clickedOnTextBox = event.target.closest('.text-box');
                if (!clickedOnTextBox) {
                    debug(`Criando caixa de texto na posição: x=${x}, y=${y}`);
                    createTextBox(x, y);
                } else {
                    setActiveTextBox(clickedOnTextBox);
                }
            }
        }

        function findClickedText(x, y) {
            // Iterate backwards to find the top-most text
            for (let i = renderedTexts.length - 1; i >= 0; i--) {
                const textData = renderedTexts[i];
                // Simple bounding box check (ignores rotation for now, but good enough for most cases)
                if (x >= textData.x && x <= textData.x + textData.width && y >= textData.y && y <= textData.y + textData.height) {
                    return i;
                }
            }
            return -1; // Not found
        }

        function editRenderedText(index) {
            const textData = renderedTexts.splice(index, 1)[0]; // Remove from array and get data
            
            // Re-create the editable text box with the stored data
            createTextBox(textData.x, textData.y, textData);

            // Redraw the canvas without the edited text
            redrawAllDrawings();
        }

        function redrawAllDrawings() {
            if (!drawingContext) return;
            debug("Redesenhando tudo...");
            // Clear the entire drawing canvas
            drawingContext.clearRect(0, 0, drawingCanvas.width, drawingCanvas.height);

            // Re-render all stored text objects
            renderedTexts.forEach(textData => {
                renderTextToCanvas(textData, false); // false to not store it again
            });

            // Note: This simple version doesn't re-render free-hand drawings.
            // A more robust solution would store all drawing paths, not just text.
        }


        function createTextBox(x, y, initialData = null) {
            const id = `textbox-${Date.now()}`;
            const textBox = document.createElement('div');
            textBox.id = id;
            textBox.className = 'text-box';
            textBox.style.left = `${x}px`;
            textBox.style.top = `${y}px`;
            textBox.style.width = initialData ? `${initialData.width}px` : '200px';
            textBox.style.height = 'auto';

            const input = document.createElement('textarea');
            input.className = 'text-box-input';
            input.placeholder = 'Digite aqui...';
            input.value = initialData ? initialData.text : '';
            input.oninput = () => {
                input.style.height = 'auto';
                input.style.height = `${input.scrollHeight}px`;
                textBox.style.height = 'auto';
            };
            
            const controls = document.createElement('div');
            controls.className = 'text-box-controls';
            controls.innerHTML = `
                <button class="text-box-btn" title="Confirmar" onclick="confirmTextBox('${id}')">✓</button>
                <button class="text-box-btn" title="Excluir" onclick="deleteTextBox('${id}')">×</button>
            `;

            const resizer = document.createElement('div');
            resizer.className = 'text-box-resize';

            textBox.appendChild(input);
            textBox.appendChild(controls);
            textBox.appendChild(resizer);

            const wrapper = document.querySelector('.canvas-wrapper');
            if (wrapper) {
                wrapper.appendChild(textBox);
                debug(`Caixa de texto ${id} adicionada ao wrapper.`);
            } else {
                debug('ERRO: .canvas-wrapper não encontrado!');
                return;
            }

            textBoxes.push(textBox);
            setupTextBoxEvents(textBox, input, resizer);
            setActiveTextBox(textBox);
            
            if (initialData) {
                // Restore properties from existing text
                document.getElementById('fontSize').value = initialData.size;
                document.getElementById('fontFamily').value = initialData.font;
                document.getElementById('textColor').value = initialData.color;
                document.getElementById('textRotation').value = initialData.rotation;
                textEffects = { ...initialData.effects };
                
                // Update UI controls to reflect the state
                updateRangeDisplays();
                document.querySelectorAll('.effect-btn').forEach(btn => {
                    const effect = btn.dataset.effect;
                    btn.classList.toggle('active', textEffects[effect]);
                });
            }

            updateTextBoxStyle();
            input.focus();
            
            setTimeout(() => {
                input.style.height = 'auto';
                input.style.height = `${input.scrollHeight}px`;
                textBox.style.height = 'auto';
            }, 10);
        }

        function confirmTextBox(id) {
            const textBox = document.getElementById(id);
            if (!textBox) return;
            const input = textBox.querySelector('.text-box-input');
            const text = input.value;
            if (!text.trim()) {
                deleteTextBox(id);
                return;
            }
            const style = window.getComputedStyle(input);
            const textData = {
                text: text,
                x: textBox.offsetLeft,
                y: textBox.offsetTop,
                width: textBox.offsetWidth,
                height: textBox.offsetHeight,
                font: style.fontFamily,
                size: parseInt(style.fontSize, 10),
                color: style.color,
                rotation: parseFloat(textBox.dataset.rotation || 0),
                effects: {
                    bold: style.fontWeight === 'bold' || parseInt(style.fontWeight) >= 700,
                    italic: style.fontStyle === 'italic',
                    underline: (textBox.dataset.underline === 'true')
                }
            };
            renderTextToCanvas(textData, true); // Pass true to store the text data
            deleteTextBox(id);
        }

        function deleteTextBox(id) {
            const textBox = document.getElementById(id);
            if (textBox) {
                textBox.remove();
                textBoxes = textBoxes.filter(box => box.id !== id);
                if (activeTextBox && activeTextBox.id === id) activeTextBox = null;
                debug(`Caixa de texto ${id} removida.`);
            }
        }

        function renderTextToCanvas(data, shouldStore = true) {
            drawingContext.save();
            drawingContext.globalCompositeOperation = 'source-over';
            drawingContext.globalAlpha = 1;
            const fontStyle = `${data.effects.italic ? 'italic' : ''} ${data.effects.bold ? 'bold' : ''} ${data.size}px ${data.font}`;
            drawingContext.font = fontStyle;
            drawingContext.fillStyle = data.color;
            const lines = data.text.split('\n');
            const textMetrics = drawingContext.measureText(lines[0] || 'W');
            const textWidth = Math.max(...lines.map(l => drawingContext.measureText(l).width));
            const textHeight = (textMetrics.actualBoundingBoxAscent + textMetrics.actualBoundingBoxDescent) * lines.length;
            const centerX = data.x + textWidth / 2;
            const centerY = data.y + textHeight / 2;

            if (data.rotation) {
                drawingContext.translate(centerX, centerY);
                drawingContext.rotate(data.rotation * Math.PI / 180);
                drawingContext.translate(-centerX, -centerY);
            }

            drawingContext.textBaseline = 'top';
            lines.forEach((line, index) => {
                const yPos = data.y + (index * data.size * 1.2);
                drawingContext.fillText(line, data.x, yPos);
                if (data.effects.underline) {
                    const metrics = drawingContext.measureText(line);
                    drawingContext.beginPath();
                    drawingContext.moveTo(data.x, yPos + data.size + 2);
                    drawingContext.lineTo(data.x + metrics.width, yPos + data.size + 2);
                    drawingContext.strokeStyle = data.color;
                    drawingContext.lineWidth = Math.max(1, data.size / 12);
                    drawingContext.stroke();
                }
            });
            drawingContext.restore();
            debug('Texto renderizado no canvas.');

            if (shouldStore) {
                renderedTexts.push(data);
                debug(`Texto armazenado. Total: ${renderedTexts.length}`);
            }
        }

        function updateTextBoxStyle() {
            if (!activeTextBox) return;
            const input = activeTextBox.querySelector('.text-box-input');
            if (!input) return;

            // Update text from the sidebar input field
            const textInputEl = document.getElementById('textInput');
            if (textInputEl.value !== input.value) {
                input.value = textInputEl.value;
            }
            
            const fontSize = document.getElementById('fontSize').value;
            const fontFamily = document.getElementById('fontFamily').value;
            const color = document.getElementById('textColor').value;
            const rotation = document.getElementById('textRotation').value;
            input.style.fontSize = `${fontSize}px`;
            input.style.lineHeight = `${parseInt(fontSize, 10) * 1.2}px`;
            input.style.fontFamily = fontFamily;
            input.style.color = color;
            input.style.fontWeight = textEffects.bold ? 'bold' : 'normal';
            input.style.fontStyle = textEffects.italic ? 'italic' : 'normal';
            activeTextBox.dataset.underline = textEffects.underline;
            activeTextBox.dataset.rotation = rotation;
            activeTextBox.dataset.bold = textEffects.bold;
            activeTextBox.dataset.italic = textEffects.italic;
            activeTextBox.dataset.underline = textEffects.underline;
            input.style.textDecoration = textEffects.underline ? 'underline' : 'none';
            activeTextBox.style.transform = `rotate(${rotation}deg)`;
            input.style.height = 'auto';
            input.style.height = `${input.scrollHeight}px`;
            activeTextBox.style.height = 'auto';
        }

        function setFontSize(size) {
            const fontSizeEl = document.getElementById('fontSize');
            if (fontSizeEl) {
                fontSizeEl.value = size;
                updateRangeDisplays();
                updateTextBoxStyle();
            }
            
            // Update button states
            document.querySelectorAll('.font-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            if (event && event.target) {
                event.target.classList.add('active');
            }
        }

        function setTextColor(color) {
            const textColorEl = document.getElementById('textColor');
            if (textColorEl) {
                textColorEl.value = color;
                updateTextBoxStyle();
            }
            
            // Update preset states
            document.querySelectorAll('#textProperties .color-preset').forEach(preset => {
                preset.classList.remove('active');
            });
            if (event && event.target) {
                event.target.classList.add('active');
            }
        }
        
        function setDrawColor(color) {
            const drawColorEl = document.getElementById('drawColor');
            if (drawColorEl) {
                drawColorEl.value = color;
                updateBrushPreview();
            }
            
            // Update preset states
            document.querySelectorAll('#drawProperties .color-preset').forEach(preset => {
                preset.classList.remove('active');
            });
            if (event && event.target) {
                event.target.classList.add('active');
            }
        }
        
        function setHighlightColor(color) {
            const highlightColorEl = document.getElementById('highlightColor');
            if (highlightColorEl) {
                highlightColorEl.value = color;
            }
            
            // Update preset states
            document.querySelectorAll('#highlightProperties .color-preset').forEach(preset => {
                preset.classList.remove('active');
            });
            if (event && event.target) {
                event.target.classList.add('active');
            }
        }

        function toggleTextEffect(effect) {
            textEffects[effect] = !textEffects[effect];
            
            // Update button state
            const btn = document.querySelector(`[data-effect="${effect}"]`);
            if (btn) {
                btn.classList.toggle('active', textEffects[effect]);
            }
            
            // Update active text box style
            updateTextBoxStyle();
            
            debug(`Efeito ${effect}: ${textEffects[effect]}`);
        }

        function setLineStyle(style) {
            currentLineStyle = style;
            updateBrushPreview();
            
            // Update button states
            document.querySelectorAll('#drawProperties .style-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            const btn = document.querySelector(`#drawProperties [data-style="${style}"]`);
            if (btn) {
                btn.classList.add('active');
            }
        }

        function setHighlightStyle(style) {
            currentHighlightStyle = style;
            
            // Update button states
            document.querySelectorAll('#highlightProperties .style-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            const btn = document.querySelector(`#highlightProperties [data-style="${style}"]`);
            if (btn) {
                btn.classList.add('active');
            }
        }

        function setActiveTextBox(textBox) {
            if (activeTextBox && activeTextBox !== textBox) {
                activeTextBox.classList.remove('active');
            }
            activeTextBox = textBox;
            if (activeTextBox) {
                activeTextBox.classList.add('active');
                const maxZIndex = Math.max(100, ...textBoxes.map(box => parseInt(box.style.zIndex) || 100));
                activeTextBox.style.zIndex = maxZIndex + 1;

                // Update sidebar with active text box content
                const input = activeTextBox.querySelector('.text-box-input');
                if (input) {
                    document.getElementById('textInput').value = input.value;
                }
            }
        }

        function setupTextBoxEvents(textBox, input, resizer) {
            textBox.addEventListener('mousedown', (e) => {
                if (e.target === resizer) {
                    isResizing = true;
                    dragStartX = e.clientX;
                    dragStartY = e.clientY;
                    textBox.classList.add('active');
                    debug(`Iniciando redimensionamento: ${textBox.id}`);
                } else {
                    isDragging = true;
                    dragStartX = e.clientX - textBox.offsetLeft;
                    dragStartY = e.clientY - textBox.offsetTop;
                    textBox.classList.add('active');
                    debug(`Iniciando arrasto: ${textBox.id}`);
                }
            });

            document.addEventListener('mousemove', (e) => {
                if (isDragging && activeTextBox === textBox) {
                    const x = e.clientX - dragStartX;
                    const y = e.clientY - dragStartY;
                    textBox.style.left = `${x}px`;
                    textBox.style.top = `${y}px`;
                } else if (isResizing && activeTextBox === textBox) {
                    const width = Math.max(100, e.clientX - textBox.offsetLeft);
                    const height = Math.max(30, e.clientY - textBox.offsetTop);
                    textBox.style.width = `${width}px`;
                    textBox.style.height = `${height}px`;
                }
            });

            document.addEventListener('mouseup', (e) => {
                // Only finalize interaction if dragging or resizing was active
                if (isDragging || isResizing) {
                    isDragging = false;
                    isResizing = false;
                    textBox.classList.remove('active');
                    debug(`Finalizando interação: ${textBox.id}`);
                }
            });

            // Prevent text selection while dragging/resizing
            textBox.addEventListener('dragstart', (e) => e.preventDefault());
        }

        function updateBrushPreview() {
            if (!brushPreviewContext) return;
            
            const color = document.getElementById('drawColor').value;
            const width = document.getElementById('lineWidth').value;
            const opacity = document.getElementById('drawOpacity').value;
            const style = currentLineStyle;
            
            brushPreviewContext.clearRect(0, 0, 200, 40);
            brushPreviewContext.beginPath();
            brushPreviewContext.moveTo(10, 20);
            brushPreviewContext.lineTo(190, 20);
            brushPreviewContext.strokeStyle = color;
            brushPreviewContext.lineWidth = width;
            brushPreviewContext.globalAlpha = opacity;
            
            // Apply line style
            if (style === 'dashed') {
                brushPreviewContext.setLineDash([10, 10]);
            } else if (style === 'dotted') {
                brushPreviewContext.setLineDash([3, 6]);
            } else {
                brushPreviewContext.setLineDash([]);
            }
            
            brushPreviewContext.stroke();
            
            brushPreviewContext.globalAlpha = 1;
        }

        // Download the edited PDF by embedding the drawing canvas overlay into each page
        async function downloadPdf() {
            if (!pdfLibDocument) {
                debug('Erro: documento PDF não carregado.');
                return;
            }
            try {
                // Convert drawings to PNG
                const pngUrl = drawingCanvas.toDataURL('image/png');
                const pngBytes = await fetch(pngUrl).then(res => res.arrayBuffer());
                const pngImage = await pdfLibDocument.embedPng(pngBytes);
                const pages = pdfLibDocument.getPages();

                // Overlay image on each page
                for (const page of pages) {
                    const { width, height } = page.getSize();
                    page.drawImage(pngImage, {
                        x: 0,
                        y: 0,
                        width: width,
                        height: height
                    });
                }

                // Save and trigger download
                const pdfBytes = await pdfLibDocument.save();
                const blob = new Blob([pdfBytes], { type: 'application/pdf' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'edited.pdf';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
                debug('PDF baixado com sucesso.');
            } catch (err) {
                console.error(err);
                debug('Erro ao gerar PDF: ' + err.message);
            }
        }
        
    </script>
</body>
</html>
