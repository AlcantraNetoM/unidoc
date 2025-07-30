class SimplePdfEditor {
    constructor() {
        this.pdfDoc = null;
        this.pdfLibDoc = null;
        this.canvas = null;
        this.ctx = null;
        this.drawingCanvas = null;
        this.drawingCtx = null;
        this.currentPage = 0;
        this.zoom = 1;
        this.isDrawing = false;
        this.currentTool = null;
        this.lastX = 0;
        this.lastY = 0;
        
        this.init();
    }
    
    init() {
        console.log('SimplePdfEditor inicializado');
        console.log('Estado inicial:', {
            pdfDoc: this.pdfDoc,
            canvas: this.canvas,
            drawingCanvas: this.drawingCanvas
        });
        
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            console.log('DOM ainda carregando, aguardando...');
            document.addEventListener('DOMContentLoaded', () => {
                console.log('DOM carregado, configurando canvas...');
                this.setupCanvas();
            });
        } else {
            console.log('DOM já carregado, configurando canvas...');
            this.setupCanvas();
        }
    }
    
    setupCanvas() {
        console.log('⚙️ Configurando canvas...');
        console.log('🔍 DOM readyState:', document.readyState);
        
        // Try to find canvas elements
        this.canvas = document.getElementById('pdfCanvas');
        this.drawingCanvas = document.getElementById('drawingCanvas');
        
        console.log('🔍 Elementos encontrados:', {
            pdfCanvas: !!this.canvas,
            drawingCanvas: !!this.drawingCanvas
        });
        
        if (this.canvas) {
            try {
                this.ctx = this.canvas.getContext('2d');
                console.log('✅ Context 2D do canvas principal criado');
                
                // Test if context is working
                this.ctx.fillStyle = '#f0f0f0';
                this.ctx.fillRect(0, 0, 1, 1);
                console.log('✅ Canvas principal testado e funcionando');
            } catch (error) {
                console.error('❌ Erro ao criar context do canvas principal:', error);
                this.ctx = null;
            }
        } else {
            console.warn('⚠️ Canvas principal (pdfCanvas) não encontrado no DOM');
        }
        
        if (this.drawingCanvas) {
            try {
                this.drawingCtx = this.drawingCanvas.getContext('2d');
                console.log('✅ Context 2D do canvas de desenho criado');
                
                // Test if context is working
                this.drawingCtx.strokeStyle = '#000000';
                this.drawingCtx.lineWidth = 1;
                console.log('✅ Canvas de desenho testado e funcionando');
            } catch (error) {
                console.error('❌ Erro ao criar context do canvas de desenho:', error);
                this.drawingCtx = null;
            }
        } else {
            console.warn('⚠️ Canvas de desenho (drawingCanvas) não encontrado no DOM');
        }
        
        const success = !!(this.canvas && this.ctx && this.drawingCanvas && this.drawingCtx);
        console.log('📊 Setup finalizado:', {
            canvas: !!this.canvas,
            ctx: !!this.ctx,
            drawingCanvas: !!this.drawingCanvas,
            drawingCtx: !!this.drawingCtx,
            success: success
        });
        
        return success;
    }
    
    async loadPDF(event) {
        console.log('🚀 Iniciando loadPDF...', event);
        
        const file = event.target.files[0];
        if (!file) {
            console.log('❌ Nenhum arquivo selecionado');
            return;
        }
        
        console.log('📁 Arquivo selecionado:', {
            name: file.name,
            size: file.size,
            type: file.type,
            lastModified: new Date(file.lastModified)
        });
        
        try {
            // Verificar bibliotecas
            console.log('🔍 Verificando bibliotecas...');
            if (typeof pdfjsLib === 'undefined') {
                throw new Error('PDF.js não está carregado');
            }
            if (typeof PDFLib === 'undefined') {
                throw new Error('PDF-lib não está carregado');
            }
            console.log('✅ Bibliotecas OK');
            
            // Setup canvas if not already done
            if (!this.canvas || !this.drawingCanvas) {
                console.log('⚙️ Configurando canvas...');
                const canvasOk = this.setupCanvas();
                if (!canvasOk) {
                    throw new Error('Falha ao configurar canvas');
                }
            }
            
            // Verificar se os canvas estão disponíveis
            if (!this.canvas || !this.ctx) {
                throw new Error('Canvas principal não está disponível');
            }
            if (!this.drawingCanvas || !this.drawingCtx) {
                throw new Error('Canvas de desenho não está disponível');
            }
            console.log('✅ Canvas verificados');
            
            // Ler o arquivo uma vez e criar cópias do buffer
            console.log('📖 Lendo arquivo...');
            const originalBuffer = await file.arrayBuffer();
            console.log('💾 Buffer original criado:', originalBuffer.byteLength, 'bytes');
            
            if (originalBuffer.byteLength === 0) {
                throw new Error('Arquivo está vazio');
            }
            
            // Verificar se é um PDF válido (deve começar com %PDF)
            const firstBytes = new Uint8Array(originalBuffer, 0, 8);
            const header = String.fromCharCode(...firstBytes);
            if (!header.startsWith('%PDF-')) {
                throw new Error('Arquivo não é um PDF válido (header: ' + header + ')');
            }
            console.log('✅ Header PDF válido:', header);
            
            // Criar cópias independentes do buffer
            console.log('📋 Criando cópias do buffer...');
            const buffer1 = originalBuffer.slice(); // Para PDF.js
            const buffer2 = originalBuffer.slice(); // Para PDF-lib
            
            console.log('✅ Buffers copiados:', buffer1.byteLength, buffer2.byteLength);
            
            // Load with PDF.js for rendering
            console.log('📚 Carregando com PDF.js...');
            const loadingTask = pdfjsLib.getDocument({ 
                data: buffer1,
                verbosity: 0  // Reduzir logs do PDF.js
            });
            
            this.pdfDoc = await loadingTask.promise;
            console.log('✅ PDF.js carregado com sucesso!', {
                numPages: this.pdfDoc.numPages,
                fingerprint: this.pdfDoc.fingerprint
            });
            
            // Load with PDF-lib for editing
            console.log('📝 Carregando com PDF-lib...');
            this.pdfLibDoc = await PDFLib.PDFDocument.load(buffer2);
            console.log('✅ PDF-lib carregado com sucesso!');
            
            // Render first page
            console.log('🎨 Renderizando primeira página...');
            this.currentPage = 0; // Reset page
            await this.renderPage(1);
            
            // Notify Alpine.js
            console.log('📡 Notificando Alpine.js...');
            this.notifyAlpine('pdfLoaded', true);
            this.notifyAlpine('currentPage', 1);
            console.log('🎉 PDF carregado e renderizado com sucesso!');
            
        } catch (error) {
            console.error('❌ Erro ao carregar PDF:', error);
            console.error('📍 Stack trace:', error.stack);
            console.error('🔧 Estado do editor:', {
                canvas: !!this.canvas,
                drawingCanvas: !!this.drawingCanvas,
                ctx: !!this.ctx,
                drawingCtx: !!this.drawingCtx,
                pdfDoc: !!this.pdfDoc,
                pdfLibDoc: !!this.pdfLibDoc
            });
            this.notifyError('Erro ao carregar o arquivo PDF: ' + error.message);
        }
    }
    
    async renderPage(pageNum) {
        console.log('🎨 renderPage iniciado:', pageNum);
        console.log('📊 Estado atual:', {
            pdfDoc: !!this.pdfDoc,
            canvas: !!this.canvas,
            ctx: !!this.ctx,
            drawingCanvas: !!this.drawingCanvas,
            drawingCtx: !!this.drawingCtx,
            zoom: this.zoom
        });
        
        if (!this.pdfDoc) {
            console.error('❌ PDF não está carregado');
            throw new Error('PDF não foi carregado');
        }
        
        if (!this.canvas || !this.ctx) {
            console.error('❌ Canvas principal não está disponível');
            throw new Error('Canvas principal não está configurado');
        }
        
        try {
            console.log(`📄 Obtendo página ${pageNum} de ${this.pdfDoc.numPages}...`);
            
            if (pageNum < 1 || pageNum > this.pdfDoc.numPages) {
                throw new Error(`Número da página inválido: ${pageNum} (total: ${this.pdfDoc.numPages})`);
            }
            
            const page = await this.pdfDoc.getPage(pageNum);
            console.log('✅ Página obtida');
            
            const viewport = page.getViewport({ scale: this.zoom });
            console.log('📏 Viewport criado:', {
                width: viewport.width,
                height: viewport.height,
                scale: viewport.scale
            });
            
            // Set canvas dimensions
            console.log('🖼️ Configurando dimensões dos canvas...');
            this.canvas.width = viewport.width;
            this.canvas.height = viewport.height;
            console.log('✅ Canvas principal redimensionado para:', this.canvas.width, 'x', this.canvas.height);
            
            // Set drawing canvas dimensions to match
            if (this.drawingCanvas && this.drawingCtx) {
                this.drawingCanvas.width = viewport.width;
                this.drawingCanvas.height = viewport.height;
                console.log('✅ Canvas de desenho redimensionado para:', this.drawingCanvas.width, 'x', this.drawingCanvas.height);
                
                // Clear drawing canvas
                this.drawingCtx.clearRect(0, 0, viewport.width, viewport.height);
                console.log('✅ Canvas de desenho limpo');
            } else {
                console.warn('⚠️ Canvas de desenho não está disponível');
            }
            
            // Clear main canvas
            this.ctx.clearRect(0, 0, viewport.width, viewport.height);
            console.log('✅ Canvas principal limpo');
            
            // Set white background
            this.ctx.fillStyle = '#ffffff';
            this.ctx.fillRect(0, 0, viewport.width, viewport.height);
            console.log('✅ Fundo branco aplicado');
            
            // Render page
            console.log('🎨 Iniciando renderização da página...');
            const renderContext = {
                canvasContext: this.ctx,
                viewport: viewport
            };
            
            const renderTask = page.render(renderContext);
            await renderTask.promise;
            
            console.log('🎉 Página renderizada com sucesso!');
            
            // Update current page
            this.currentPage = pageNum - 1; // Zero-based
            
        } catch (error) {
            console.error('❌ Erro ao renderizar página:', error);
            console.error('📍 Stack trace:', error.stack);
            console.error('🔧 Estado final:', {
                canvasWidth: this.canvas?.width,
                canvasHeight: this.canvas?.height,
                contextAvailable: !!this.ctx
            });
            throw error; // Re-throw to be handled by caller
        }
    }
    
    setTool(tool) {
        this.currentTool = tool;
        console.log('Ferramenta selecionada:', tool);
        
        // Update cursor
        if (this.drawingCanvas) {
            if (tool === 'draw' || tool === 'highlight') {
                this.drawingCanvas.style.cursor = 'crosshair';
            } else if (tool === 'text') {
                this.drawingCanvas.style.cursor = 'text';
            } else {
                this.drawingCanvas.style.cursor = 'default';
            }
        }
    }
    
    startDrawing(event) {
        if (!this.drawingCanvas || !this.currentTool) return;
        if (this.currentTool !== 'draw' && this.currentTool !== 'highlight') return;
        
        this.isDrawing = true;
        const rect = this.drawingCanvas.getBoundingClientRect();
        this.lastX = (event.clientX - rect.left) / this.zoom;
        this.lastY = (event.clientY - rect.top) / this.zoom;
        
        // Setup drawing context
        this.drawingCtx.lineJoin = 'round';
        this.drawingCtx.lineCap = 'round';
        
        if (this.currentTool === 'draw') {
            this.drawingCtx.globalCompositeOperation = 'source-over';
            this.drawingCtx.strokeStyle = this.getDrawColor();
            this.drawingCtx.lineWidth = this.getLineWidth();
        } else if (this.currentTool === 'highlight') {
            this.drawingCtx.globalCompositeOperation = 'multiply';
            this.drawingCtx.strokeStyle = this.getHighlightColor();
            this.drawingCtx.globalAlpha = this.getHighlightOpacity();
            this.drawingCtx.lineWidth = 20; // Highlight is thicker
        }
    }
    
    draw(event) {
        if (!this.isDrawing || !this.drawingCanvas) return;
        
        const rect = this.drawingCanvas.getBoundingClientRect();
        const currentX = (event.clientX - rect.left) / this.zoom;
        const currentY = (event.clientY - rect.top) / this.zoom;
        
        this.drawingCtx.beginPath();
        this.drawingCtx.moveTo(this.lastX, this.lastY);
        this.drawingCtx.lineTo(currentX, currentY);
        this.drawingCtx.stroke();
        
        this.lastX = currentX;
        this.lastY = currentY;
    }
    
    stopDrawing() {
        this.isDrawing = false;
        if (this.drawingCtx) {
            this.drawingCtx.globalAlpha = 1;
            this.drawingCtx.globalCompositeOperation = 'source-over';
        }
    }
    
    addText(x, y, text, fontSize, color) {
        if (!this.drawingCanvas || !text) return;
        
        this.drawingCtx.font = `${fontSize}px Arial`;
        this.drawingCtx.fillStyle = color;
        this.drawingCtx.fillText(text, x, y);
        
        console.log('Texto adicionado:', text, 'em', x, y);
    }
    
    async savePDF() {
        if (!this.pdfLibDoc) {
            throw new Error('Nenhum PDF carregado');
        }
        
        try {
            // Get the drawing canvas content as image
            if (this.drawingCanvas && this.hasDrawings()) {
                const imageData = this.drawingCanvas.toDataURL('image/png');
                const imageBytes = await this.dataURLToUint8Array(imageData);
                
                // Embed the image in the PDF
                const pngImage = await this.pdfLibDoc.embedPng(imageBytes);
                const pages = this.pdfLibDoc.getPages();
                const page = pages[this.currentPage];
                
                const { width, height } = page.getSize();
                page.drawImage(pngImage, {
                    x: 0,
                    y: 0,
                    width: width,
                    height: height,
                });
            }
            
            const pdfBytes = await this.pdfLibDoc.save();
            console.log('PDF salvo com sucesso');
            return pdfBytes;
            
        } catch (error) {
            console.error('Erro ao salvar PDF:', error);
            throw error;
        }
    }
    
    hasDrawings() {
        if (!this.drawingCanvas) return false;
        
        const imageData = this.drawingCtx.getImageData(0, 0, this.drawingCanvas.width, this.drawingCanvas.height);
        const data = imageData.data;
        
        // Check if any pixel is not transparent
        for (let i = 3; i < data.length; i += 4) {
            if (data[i] !== 0) return true;
        }
        return false;
    }
    
    async dataURLToUint8Array(dataURL) {
        const response = await fetch(dataURL);
        const arrayBuffer = await response.arrayBuffer();
        return new Uint8Array(arrayBuffer);
    }
    
    // Helper methods to get properties from Alpine.js
    getDrawColor() {
        return document.querySelector('[x-data]').__x.$data.drawColor || '#000000';
    }
    
    getLineWidth() {
        return document.querySelector('[x-data]').__x.$data.lineWidth || 2;
    }
    
    getHighlightColor() {
        return document.querySelector('[x-data]').__x.$data.highlightColor || '#ffff00';
    }
    
    getHighlightOpacity() {
        return document.querySelector('[x-data]').__x.$data.highlightOpacity || 0.5;
    }
    
    notifyAlpine(property, value) {
        document.dispatchEvent(new CustomEvent('pdf-editor-update', {
            detail: { property, value }
        }));
    }
    
    notifyError(message) {
        document.dispatchEvent(new CustomEvent('pdf-editor-error', {
            detail: { message }
        }));
    }
}

// Initialize when page loads
console.log('📜 Script simple-pdf-editor.js carregado');
console.log('📊 Estado inicial do documento:', document.readyState);
console.log('🔍 Bibliotecas disponíveis:', {
    pdfjsLib: typeof pdfjsLib !== 'undefined',
    PDFLib: typeof PDFLib !== 'undefined'
});

function initializeEditor() {
    console.log('🚀 Tentando inicializar o editor...');
    console.log('📚 Verificando bibliotecas:', {
        pdfjsLib: typeof pdfjsLib !== 'undefined' ? 'OK' : 'MISSING',
        PDFLib: typeof PDFLib !== 'undefined' ? 'OK' : 'MISSING'
    });
    
    if (typeof pdfjsLib !== 'undefined' && typeof PDFLib !== 'undefined') {
        try {
            console.log('🎯 Criando instância do SimplePdfEditor...');
            window.simplePdfEditor = new SimplePdfEditor();
            window.pdfEditor = window.simplePdfEditor; // Compatibility
            console.log('✅ Simple PDF Editor inicializado com sucesso!');
            console.log('🔗 Editor disponível em window.simplePdfEditor e window.pdfEditor');
            return true;
        } catch (error) {
            console.error('❌ Erro ao inicializar editor:', error);
            console.error('📍 Stack trace:', error.stack);
            return false;
        }
    } else {
        console.warn('⚠️ Bibliotecas ainda não estão disponíveis');
        console.warn('📝 PDF.js:', typeof pdfjsLib !== 'undefined' ? 'carregado' : 'não carregado');
        console.warn('📝 PDF-lib:', typeof PDFLib !== 'undefined' ? 'carregado' : 'não carregado');
        return false;
    }
}

// Try immediate initialization
console.log('🏃 Tentativa de inicialização imediata...');
if (initializeEditor()) {
    console.log('✅ Inicialização imediata bem-sucedida');
} else {
    console.log('⏳ Inicialização imediata falhou, aguardando DOM...');
    
    // Wait for DOM
    document.addEventListener('DOMContentLoaded', () => {
        console.log('📄 DOM carregado, tentando inicializar...');
        
        if (!initializeEditor()) {
            console.log('⌛ Primeira tentativa após DOM falhou, tentando em 1s...');
            setTimeout(() => {
                if (!initializeEditor()) {
                    console.log('⌛ Segunda tentativa falhou, tentando em 3s...');
                    setTimeout(() => {
                        if (!initializeEditor()) {
                            console.error('❌ Todas as tentativas de inicialização falharam');
                            console.error('🔧 Estado final:', {
                                documentReady: document.readyState,
                                pdfjsLib: typeof pdfjsLib !== 'undefined',
                                PDFLib: typeof PDFLib !== 'undefined',
                                pdfCanvas: !!document.getElementById('pdfCanvas'),
                                drawingCanvas: !!document.getElementById('drawingCanvas')
                            });
                        }
                    }, 3000);
                }
            }, 1000);
        }
    });
}
