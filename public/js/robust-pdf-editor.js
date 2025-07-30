class RobustPdfEditor {
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
        this.initialized = false;
        
        console.log('🏗️ RobustPdfEditor construtor chamado');
        this.init();
    }
    
    async init() {
        console.log('🚀 Iniciando RobustPdfEditor...');
        
        // Wait for libraries to be available
        await this.waitForLibraries();
        
        // Wait for DOM to be ready
        await this.waitForDOM();
        
        // Setup canvas
        await this.setupCanvas();
        
        this.initialized = true;
        console.log('✅ RobustPdfEditor inicializado com sucesso!');
    }
    
    async waitForLibraries(maxAttempts = 10) {
        console.log('📚 Aguardando bibliotecas...');
        
        for (let i = 0; i < maxAttempts; i++) {
            if (typeof pdfjsLib !== 'undefined' && typeof PDFLib !== 'undefined') {
                console.log('✅ Bibliotecas carregadas!');
                return true;
            }
            
            console.log(`⏳ Tentativa ${i + 1}/${maxAttempts} - Aguardando bibliotecas...`);
            await this.sleep(500);
        }
        
        throw new Error('Bibliotecas PDF não carregaram a tempo');
    }
    
    async waitForDOM(maxAttempts = 20) {
        console.log('📄 Aguardando DOM...');
        
        for (let i = 0; i < maxAttempts; i++) {
            if (document.readyState === 'complete' || document.readyState === 'interactive') {
                const pdfCanvas = document.getElementById('pdfCanvas');
                const drawingCanvas = document.getElementById('drawingCanvas');
                
                if (pdfCanvas && drawingCanvas) {
                    console.log('✅ DOM e elementos necessários encontrados!');
                    return true;
                }
            }
            
            console.log(`⏳ Tentativa ${i + 1}/${maxAttempts} - Aguardando DOM...`);
            await this.sleep(250);
        }
        
        throw new Error('Elementos do DOM não foram encontrados');
    }
    
    async setupCanvas() {
        console.log('⚙️ Configurando canvas...');
        
        this.canvas = document.getElementById('pdfCanvas');
        this.drawingCanvas = document.getElementById('drawingCanvas');
        
        if (!this.canvas || !this.drawingCanvas) {
            throw new Error('Canvas não encontrados');
        }
        
        this.ctx = this.canvas.getContext('2d');
        this.drawingCtx = this.drawingCanvas.getContext('2d');
        
        if (!this.ctx || !this.drawingCtx) {
            throw new Error('Contexts 2D não puderam ser criados');
        }
        
        console.log('✅ Canvas configurados com sucesso');
    }
    
    sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }
    
    async loadPDF(event) {
        console.log('📁 Carregando PDF...');
        
        if (!this.initialized) {
            console.log('⏳ Editor não inicializado, aguardando...');
            await this.init();
        }
        
        const file = event.target.files[0];
        if (!file) {
            console.log('❌ Nenhum arquivo selecionado');
            return;
        }
        
        console.log('📄 Processando arquivo:', file.name);
        
        try {
            // Read file
            const arrayBuffer = await file.arrayBuffer();
            
            // Validate PDF
            const header = new TextDecoder().decode(arrayBuffer.slice(0, 8));
            if (!header.startsWith('%PDF-')) {
                throw new Error('Arquivo não é um PDF válido');
            }
            
            // Create separate buffers
            const buffer1 = arrayBuffer.slice();
            const buffer2 = arrayBuffer.slice();
            
            // Load with PDF.js
            console.log('📚 Carregando com PDF.js...');
            const loadingTask = pdfjsLib.getDocument({ data: buffer1 });
            this.pdfDoc = await loadingTask.promise;
            console.log(`✅ PDF.js: ${this.pdfDoc.numPages} páginas`);
            
            // Load with PDF-lib
            console.log('📝 Carregando com PDF-lib...');
            this.pdfLibDoc = await PDFLib.PDFDocument.load(buffer2);
            console.log('✅ PDF-lib carregado');
            
            // Render first page
            await this.renderPage(1);
            
            // Notify success
            this.notifyAlpine('pdfLoaded', true);
            console.log('🎉 PDF carregado com sucesso!');
            
        } catch (error) {
            console.error('❌ Erro ao carregar PDF:', error);
            this.notifyError('Erro ao carregar PDF: ' + error.message);
        }
    }
    
    async renderPage(pageNum) {
        if (!this.pdfDoc || !this.canvas) {
            throw new Error('PDF ou canvas não disponível');
        }
        
        console.log(`🎨 Renderizando página ${pageNum}...`);
        
        try {
            const page = await this.pdfDoc.getPage(pageNum);
            const viewport = page.getViewport({ scale: this.zoom });
            
            // Resize canvas
            this.canvas.width = viewport.width;
            this.canvas.height = viewport.height;
            this.drawingCanvas.width = viewport.width;
            this.drawingCanvas.height = viewport.height;
            
            // Clear canvas
            this.ctx.clearRect(0, 0, viewport.width, viewport.height);
            this.drawingCtx.clearRect(0, 0, viewport.width, viewport.height);
            
            // White background
            this.ctx.fillStyle = '#ffffff';
            this.ctx.fillRect(0, 0, viewport.width, viewport.height);
            
            // Render
            const renderContext = {
                canvasContext: this.ctx,
                viewport: viewport
            };
            
            await page.render(renderContext).promise;
            console.log('✅ Página renderizada');
            
        } catch (error) {
            console.error('❌ Erro ao renderizar:', error);
            throw error;
        }
    }
    
    setTool(tool) {
        this.currentTool = tool;
        console.log('🔧 Ferramenta:', tool);
        
        if (this.drawingCanvas) {
            const cursors = {
                'draw': 'crosshair',
                'highlight': 'crosshair',
                'text': 'text'
            };
            this.drawingCanvas.style.cursor = cursors[tool] || 'default';
        }
    }
    
    startDrawing(event) {
        if (!this.drawingCanvas || !this.currentTool) return;
        if (!['draw', 'highlight'].includes(this.currentTool)) return;
        
        this.isDrawing = true;
        const rect = this.drawingCanvas.getBoundingClientRect();
        this.lastX = (event.clientX - rect.left) / this.zoom;
        this.lastY = (event.clientY - rect.top) / this.zoom;
        
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
            this.drawingCtx.lineWidth = 20;
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
        
        console.log('📝 Texto adicionado:', text);
    }
    
    async savePDF() {
        if (!this.pdfLibDoc) {
            throw new Error('PDF não carregado');
        }
        
        try {
            if (this.drawingCanvas && this.hasDrawings()) {
                const imageData = this.drawingCanvas.toDataURL('image/png');
                const imageBytes = await this.dataURLToUint8Array(imageData);
                
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
            console.log('💾 PDF salvo');
            return pdfBytes;
            
        } catch (error) {
            console.error('❌ Erro ao salvar:', error);
            throw error;
        }
    }
    
    hasDrawings() {
        if (!this.drawingCanvas) return false;
        
        const imageData = this.drawingCtx.getImageData(0, 0, this.drawingCanvas.width, this.drawingCanvas.height);
        const data = imageData.data;
        
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
    
    // Helper methods
    getDrawColor() {
        try {
            return document.querySelector('[x-data]').__x.$data.drawColor || '#000000';
        } catch {
            return '#000000';
        }
    }
    
    getLineWidth() {
        try {
            return document.querySelector('[x-data]').__x.$data.lineWidth || 2;
        } catch {
            return 2;
        }
    }
    
    getHighlightColor() {
        try {
            return document.querySelector('[x-data]').__x.$data.highlightColor || '#ffff00';
        } catch {
            return '#ffff00';
        }
    }
    
    getHighlightOpacity() {
        try {
            return document.querySelector('[x-data]').__x.$data.highlightOpacity || 0.5;
        } catch {
            return 0.5;
        }
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

// Robust initialization
console.log('📜 RobustPdfEditor carregado');

async function initRobustEditor() {
    try {
        console.log('🚀 Inicializando editor robusto...');
        window.simplePdfEditor = new RobustPdfEditor();
        window.pdfEditor = window.simplePdfEditor;
        console.log('✅ Editor robusto inicializado!');
        return true;
    } catch (error) {
        console.error('❌ Erro na inicialização robusta:', error);
        return false;
    }
}

// Try different initialization strategies
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initRobustEditor);
} else {
    initRobustEditor();
}

// Fallback initialization after 2 seconds
setTimeout(() => {
    if (!window.simplePdfEditor) {
        console.log('⚡ Inicialização de emergência...');
        initRobustEditor();
    }
}, 2000);
