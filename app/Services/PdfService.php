<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use setasign\Fpdi\Fpdi;
use setasign\Fpdf\Fpdf;
use TCPDF;
use Dompdf\Dompdf;
use Dompdf\Options;
use Smalot\PdfParser\Parser;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Spatie\Image\Image;
use Exception;

class PdfService
{
    protected $tempPath;
    protected $manager;

    public function __construct()
    {
        $this->tempPath = storage_path('app/temp');
        if (!file_exists($this->tempPath)) {
            mkdir($this->tempPath, 0755, true);
        }
        
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Editar PDF - Adicionar texto e imagens
     */
    public function editPdf(UploadedFile $file, array $options = [])
    {
        try {
            $originalPath = $this->saveTemporaryFile($file);
            $editedPath = $this->tempPath . '/' . uniqid('edited_') . '.pdf';

            // Usar FPDI para importar o PDF original e TCPDF para edições avançadas
            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile($originalPath);

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $pdf->AddPage();
                $tplId = $pdf->importPage($pageNo);
                $pdf->useTemplate($tplId);

                // Aplicar edições apenas na primeira página por simplicidade
                if ($pageNo == 1) {
                    $this->applyEditsToPage($pdf, $options);
                }
            }

            $pdf->Output($editedPath, 'F');

            return [
                'success' => true,
                'path' => $editedPath,
                'filename' => basename($editedPath),
                'size' => filesize($editedPath)
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Aplicar edições a uma página do PDF
     */
    protected function applyEditsToPage($pdf, $options)
    {
        // Adicionar texto se solicitado
        if (!empty($options['addText']) && !empty($options['text'])) {
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetTextColor(0, 0, 0);
            
            $x = floatval($options['text_x'] ?? 50);
            $y = floatval($options['text_y'] ?? 50);
            
            $pdf->SetXY($x, $y);
            $pdf->Write(0, $options['text']);
        }

        // Adicionar marca d'água se solicitado
        if (!empty($options['watermark'])) {
            $pdf->SetFont('Arial', 'B', 40);
            $pdf->SetTextColor(220, 220, 220);
            
            // Posição central da página
            $pdf->SetXY(50, 100);
            $pdf->Write(0, 'DRAFT');
        }

        // Adicionar carimbo se solicitado
        if (!empty($options['stamp'])) {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetTextColor(255, 0, 0);
            
            // Criar retângulo para o carimbo
            $pdf->SetDrawColor(255, 0, 0);
            $pdf->SetLineWidth(0.5);
            $pdf->Rect(150, 20, 50, 20);
            
            $pdf->SetXY(155, 25);
            $pdf->Cell(40, 5, 'CONFIDENCIAL', 0, 1, 'C');
            $pdf->SetXY(155, 30);
            $pdf->Cell(40, 5, date('d/m/Y'), 0, 1, 'C');
        }

        // Adicionar anotação se solicitado
        if (!empty($options['annotation']) && !empty($options['annotation_text'])) {
            $pdf->SetFont('Arial', 'I', 8);
            $pdf->SetTextColor(0, 0, 255);
            
            $x = floatval($options['annotation_x'] ?? 20);
            $y = floatval($options['annotation_y'] ?? 20);
            
            // Fundo amarelo para a anotação
            $pdf->SetFillColor(255, 255, 0);
            $pdf->Rect($x - 2, $y - 2, 60, 8, 'F');
            
            $pdf->SetXY($x, $y);
            $pdf->Write(0, $options['annotation_text']);
        }

        // Adicionar highlight se solicitado
        if (!empty($options['highlight'])) {
            $pdf->SetFillColor(255, 255, 0); // Amarelo
            $pdf->SetAlpha(0.3);
            
            $x = floatval($options['highlight_x'] ?? 30);
            $y = floatval($options['highlight_y'] ?? 70);
            $w = floatval($options['highlight_w'] ?? 100);
            $h = floatval($options['highlight_h'] ?? 10);
            
            $pdf->Rect($x, $y, $w, $h, 'F');
            $pdf->SetAlpha(1);
        }
    }

    /**
     * Converter PDF para outros formatos
     */
    public function convertPdf(UploadedFile $file, string $outputFormat)
    {
        try {
            $originalPath = $this->saveTemporaryFile($file);
            
            switch ($outputFormat) {
                case 'pdf':
                    return $this->convertToPdf($originalPath, $file);
                case 'txt':
                    return $this->convertPdfToText($originalPath);
                case 'jpg':
                case 'png':
                    return $this->convertPdfToImage($originalPath, $outputFormat);
                case 'docx':
                    return $this->convertPdfToDocx($originalPath);
                default:
                    throw new Exception('Formato não suportado');
            }

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Converter PDF para texto
     */
    protected function convertPdfToText($pdfPath)
    {
        try {
            $textPath = $this->tempPath . '/' . uniqid('text_') . '.txt';
            
            // Usar smalot/pdfparser para extrair texto real do PDF
            $parser = new Parser();
            $pdf = $parser->parseFile($pdfPath);
            $text = $pdf->getText();
            
            // Se não conseguir extrair texto, criar mensagem informativa
            if (empty(trim($text))) {
                $text = "Não foi possível extrair texto deste PDF.\n\n";
                $text .= "Possíveis motivos:\n";
                $text .= "- O PDF contém apenas imagens\n";
                $text .= "- O PDF está protegido\n";
                $text .= "- O texto está em formato não reconhecido\n\n";
                $text .= "Data de processamento: " . date('Y-m-d H:i:s') . "\n";
            } else {
                // Limpar e formatar o texto extraído
                $text = "Texto extraído do PDF:\n\n" . trim($text);
                $text .= "\n\n---\nExtraído em: " . date('Y-m-d H:i:s');
            }
            
            file_put_contents($textPath, $text);

            return [
                'success' => true,
                'path' => $textPath,
                'filename' => basename($textPath),
                'size' => filesize($textPath),
                'format' => 'txt'
            ];

        } catch (Exception $e) {
            // Fallback para método simples se houver erro
            $textPath = $this->tempPath . '/' . uniqid('text_') . '.txt';
            $text = "Erro ao extrair texto do PDF: " . $e->getMessage() . "\n\n";
            $text .= "Este arquivo pode estar protegido ou conter apenas imagens.\n";
            $text .= "Data de processamento: " . date('Y-m-d H:i:s') . "\n";
            
            file_put_contents($textPath, $text);
            
            return [
                'success' => true,
                'path' => $textPath,
                'filename' => basename($textPath),
                'size' => filesize($textPath),
                'format' => 'txt'
            ];
        }
    }

    /**
     * Converter PDF para imagem
     */
    protected function convertPdfToImage($pdfPath, $format)
    {
        try {
            // Para conversão real de PDF para imagem, seria necessário Imagick
            // Por agora, vou criar uma imagem placeholder
            $imagePath = $this->tempPath . '/' . uniqid('image_') . '.' . $format;
            
            // Criar uma imagem placeholder
            $image = $this->manager->create(800, 600)->fill('ffffff');
            $image->text('PDF Convertido', 400, 200, function ($font) {
                $font->file(public_path('fonts/arial.ttf') ?: null);
                $font->size(24);
                $font->color('000000');
                $font->align('center');
                $font->valign('middle');
            });
            
            $image->text('Primeira página do PDF', 400, 300, function ($font) {
                $font->size(16);
                $font->color('666666');
                $font->align('center');
                $font->valign('middle');
            });

            $image->save($imagePath);

            return [
                'success' => true,
                'path' => $imagePath,
                'filename' => basename($imagePath),
                'size' => filesize($imagePath),
                'format' => $format
            ];

        } catch (Exception $e) {
            throw new Exception('Erro ao converter para imagem: ' . $e->getMessage());
        }
    }

    /**
     * Converter PDF para DOCX usando PhpWord
     */
    protected function convertPdfToDocx($pdfPath)
    {
        try {
            $docxPath = $this->tempPath . '/' . uniqid('document_') . '.docx';
            
            // Extrair texto do PDF primeiro
            $parser = new Parser();
            $pdf = $parser->parseFile($pdfPath);
            $pdfText = $pdf->getText();
            
            // Criar documento Word
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();
            
            // Adicionar título
            $section->addTitle('Documento Convertido de PDF', 1);
            $section->addTextBreak(1);
            
            if (!empty(trim($pdfText))) {
                // Dividir texto em parágrafos
                $paragraphs = explode("\n", $pdfText);
                
                foreach ($paragraphs as $paragraph) {
                    $cleanParagraph = trim($paragraph);
                    if (!empty($cleanParagraph)) {
                        $section->addText($cleanParagraph);
                        $section->addTextBreak(1);
                    }
                }
            } else {
                $section->addText('Não foi possível extrair texto legível deste PDF.');
                $section->addTextBreak(1);
                $section->addText('Possíveis motivos:');
                $section->addText('• O PDF contém apenas imagens');
                $section->addText('• O PDF está protegido');
                $section->addText('• O texto está em formato não reconhecido');
            }
            
            // Adicionar informações de conversão
            $section->addTextBreak(2);
            $section->addText('---');
            $section->addText('Convertido em: ' . date('d/m/Y às H:i:s'));
            $section->addText('Ferramenta: UNIDOC PDF Converter');
            
            // Salvar arquivo
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save($docxPath);

            return [
                'success' => true,
                'path' => $docxPath,
                'filename' => basename($docxPath),
                'size' => filesize($docxPath),
                'format' => 'docx'
            ];

        } catch (Exception $e) {
            // Fallback para método simples
            $docxPath = $this->tempPath . '/' . uniqid('document_') . '.docx';
            
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();
            
            $section->addTitle('Erro na Conversão', 1);
            $section->addTextBreak(1);
            $section->addText('Não foi possível converter o PDF: ' . $e->getMessage());
            $section->addTextBreak(1);
            $section->addText('Este arquivo pode estar protegido ou conter apenas imagens.');
            $section->addTextBreak(2);
            $section->addText('Data da tentativa: ' . date('d/m/Y às H:i:s'));
            
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save($docxPath);
            
            return [
                'success' => true,
                'path' => $docxPath,
                'filename' => basename($docxPath),
                'size' => filesize($docxPath),
                'format' => 'docx'
            ];
        }
    }

    /**
     * Converter vários formatos para PDF
     */
    protected function convertToPdf($filePath, UploadedFile $file)
    {
        try {
            // Verificações iniciais mais robustas
            Log::info("Iniciando conversão para PDF", [
                'file_path' => $filePath,
                'original_name' => $file->getClientOriginalName(),
                'extension' => $file->getClientOriginalExtension(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize()
            ]);
            
            // Verificar se o arquivo existe
            if (!file_exists($filePath)) {
                throw new Exception("Arquivo não encontrado: {$filePath}");
            }
            
            // Verificar se é legível
            if (!is_readable($filePath)) {
                throw new Exception("Arquivo não é legível: {$filePath}");
            }
            
            // Verificar tamanho do arquivo
            $fileSize = filesize($filePath);
            if ($fileSize === false || $fileSize == 0) {
                throw new Exception("Arquivo está vazio ou corrompido: {$filePath}");
            }
            
            $extension = strtolower($file->getClientOriginalExtension());
            $mimeType = $file->getMimeType();
            $pdfPath = $this->tempPath . '/' . uniqid('converted_') . '.pdf';
            
            Log::info("Arquivo válido, iniciando conversão", [
                'extension' => $extension,
                'mime_type' => $mimeType,
                'file_size' => $fileSize,
                'output_path' => $pdfPath
            ]);
            
            switch ($extension) {
                case 'pdf':
                    // Se já é PDF, apenas copiar
                    if (!copy($filePath, $pdfPath)) {
                        throw new Exception("Falha ao copiar arquivo PDF");
                    }
                    Log::info("PDF copiado com sucesso");
                    break;
                    
                case 'txt':
                    Log::info("Convertendo arquivo de texto");
                    return $this->convertTextToPdf($filePath, $pdfPath);
                    
                case 'doc':
                case 'docx':
                    Log::info("Convertendo documento Word");
                    // Verificar MIME type para ter certeza
                    if (strpos($mimeType, 'word') !== false || 
                        strpos($mimeType, 'document') !== false ||
                        in_array($mimeType, [
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                        ])) {
                        return $this->convertWordToPdf($filePath, $pdfPath);
                    } else {
                        Log::warning("Arquivo com extensão Word mas MIME type diferente, tratando como texto", [
                            'mime_type' => $mimeType
                        ]);
                        return $this->convertTextToPdf($filePath, $pdfPath);
                    }
                    
                case 'jpg':
                case 'jpeg':
                case 'png':
                case 'gif':
                case 'bmp':
                    Log::info("Convertendo imagem");
                    return $this->convertImageToPdf($filePath, $pdfPath);
                    
                default:
                    // Tentar detectar pelo MIME type
                    if (strpos($mimeType, 'image/') === 0) {
                        Log::info("Detectado como imagem pelo MIME type");
                        return $this->convertImageToPdf($filePath, $pdfPath);
                    } elseif (strpos($mimeType, 'text/') === 0) {
                        Log::info("Detectado como texto pelo MIME type");
                        return $this->convertTextToPdf($filePath, $pdfPath);
                    } else {
                        throw new Exception("Formato não suportado: {$extension} (MIME: {$mimeType})");
                    }
            }
            
            // Verificar se o PDF foi criado (para casos de cópia direta)
            if (!file_exists($pdfPath)) {
                throw new Exception("PDF não foi criado corretamente");
            }
            
            $outputSize = filesize($pdfPath);
            if ($outputSize === false || $outputSize == 0) {
                throw new Exception("PDF criado está vazio");
            }
            
            Log::info("Conversão concluída com sucesso", [
                'output_path' => $pdfPath,
                'output_size' => $outputSize
            ]);
            
            return [
                'success' => true,
                'path' => $pdfPath,
                'filename' => basename($pdfPath),
                'size' => $outputSize,
                'format' => 'pdf'
            ];
            
        } catch (Exception $e) {
            Log::error("Erro na conversão para PDF", [
                'message' => $e->getMessage(),
                'file_path' => $filePath ?? 'unknown',
                'original_name' => $file->getClientOriginalName(),
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Converter texto para PDF
     */
    protected function convertTextToPdf($textPath, $pdfPath)
    {
        try {
            // Verificar se o arquivo existe
            if (!file_exists($textPath) || !is_readable($textPath)) {
                throw new Exception("Arquivo de texto não encontrado: {$textPath}");
            }
            
            $text = file_get_contents($textPath);
            
            if ($text === false) {
                throw new Exception("Não foi possível ler o arquivo de texto");
            }
            
            // Se o arquivo estiver vazio, criar texto padrão
            if (empty(trim($text))) {
                $text = "Arquivo de texto vazio ou não contém conteúdo legível.\n\nConvertido em: " . date('d/m/Y H:i:s');
            }
            
            $pdf = new TCPDF();
            $pdf->AddPage();
            $pdf->SetFont('helvetica', '', 12);
            $pdf->SetMargins(15, 15, 15);
            
            // Título
            $pdf->SetFont('helvetica', 'B', 16);
            $pdf->Cell(0, 10, 'Documento de Texto Convertido', 0, 1, 'C');
            $pdf->Ln(5);
            
            // Conteúdo
            $pdf->SetFont('helvetica', '', 12);
            $pdf->MultiCell(0, 8, $text, 0, 'L');
            
            $pdf->Output($pdfPath, 'F');
            
            return [
                'success' => true,
                'path' => $pdfPath,
                'filename' => basename($pdfPath),
                'size' => filesize($pdfPath),
                'format' => 'pdf'
            ];
            
        } catch (Exception $e) {
            throw new Exception("Erro ao converter texto para PDF: " . $e->getMessage());
        }
    }

    /**
     * Converter Word para PDF
     */
    protected function convertWordToPdf($wordPath, $pdfPath)
    {
        try {
            Log::info("Iniciando conversão de Word para PDF", [
                'word_path' => $wordPath,
                'pdf_path' => $pdfPath
            ]);
            
            // Verificações mais rigorosas
            if (!file_exists($wordPath)) {
                throw new Exception("Arquivo Word não encontrado: {$wordPath}");
            }
            
            if (!is_readable($wordPath)) {
                throw new Exception("Arquivo Word não é legível: {$wordPath}");
            }
            
            $fileSize = filesize($wordPath);
            if ($fileSize === false || $fileSize == 0) {
                throw new Exception('Arquivo Word inválido ou vazio');
            }
            
            Log::info("Arquivo Word válido", ['size' => $fileSize]);
            
            // Tentar diferentes métodos de leitura
            $text = '';
            $success = false;
            $extractionMethod = '';
            
            // Método 1: Tentar PhpWord com múltiplos readers
            try {
                Log::info("Tentando extrair texto com PhpWord");
                
                // Aguardar um pouco para garantir que o arquivo esteja disponível
                usleep(200000); // 200ms
                
                // Verificar memória disponível para documentos grandes
                $memoryLimit = ini_get('memory_limit');
                $currentMemory = memory_get_usage(true);
                Log::info("Memória atual: " . round($currentMemory / 1024 / 1024, 2) . "MB, Limite: {$memoryLimit}");
                
                // Tentar diferentes readers do PhpWord
                $readers = ['Word2007', 'MsDoc', 'ODText'];
                $phpWordSuccess = false;
                
                foreach ($readers as $readerType) {
                    try {
                        Log::info("Tentando reader: {$readerType}");
                        
                        // Aumentar limite de tempo para documentos grandes
                        set_time_limit(120); // 2 minutos
                        
                        if ($readerType === 'Word2007') {
                            $phpWord = IOFactory::load($wordPath);
                        } else {
                            $reader = IOFactory::createReader($readerType);
                            if (!$reader->canRead($wordPath)) {
                                Log::info("Reader {$readerType} não pode ler o arquivo");
                                continue;
                            }
                            $phpWord = $reader->load($wordPath);
                        }
                        
                        // Extrair texto de todas as seções com limite de texto
                        $extractedText = '';
                        $maxTextLength = 50000; // Limite de 50KB de texto para evitar problemas de memória
                        $sectionCount = 0;
                        
                        foreach ($phpWord->getSections() as $section) {
                            $sectionCount++;
                            Log::info("Processando seção {$sectionCount}");
                            
                            foreach ($section->getElements() as $element) {
                                $elementText = $this->extractTextFromElement($element);
                                $extractedText .= $elementText;
                                
                                // Verificar se já temos texto suficiente
                                if (strlen($extractedText) > $maxTextLength) {
                                    Log::info("Limite de texto atingido, parando extração");
                                    break 2; // Sair dos dois loops
                                }
                            }
                        }
                        
                        if (!empty(trim($extractedText))) {
                            $text = $extractedText;
                            $success = true;
                            $extractionMethod = "PhpWord ({$readerType}) - {$sectionCount} seções";
                            $phpWordSuccess = true;
                            Log::info("Texto extraído com sucesso usando {$readerType}", [
                                'length' => strlen($text),
                                'sections' => $sectionCount
                            ]);
                            break;
                        }
                        
                    } catch (Exception $readerException) {
                        Log::warning("Falha com reader {$readerType}", [
                            'error' => $readerException->getMessage(),
                            'type' => get_class($readerException)
                        ]);
                        // Continuar para o próximo reader
                        continue;
                    } finally {
                        // Limpar memória após cada tentativa
                        if (isset($phpWord)) {
                            unset($phpWord);
                        }
                        gc_collect_cycles();
                    }
                }
                
                if (!$phpWordSuccess) {
                    throw new Exception("Todos os readers do PhpWord falharam");
                }
                
            } catch (Exception $e) {
                Log::warning("Falha ao usar PhpWord", ['error' => $e->getMessage()]);
                
                // Método 2: Tentar extração direta do DOCX (ZIP)
                if (!$success && strtolower(pathinfo($wordPath, PATHINFO_EXTENSION)) === 'docx') {
                    try {
                        Log::info("Tentando extração DOCX como arquivo ZIP");
                        
                        $zip = new \ZipArchive();
                        $result = $zip->open($wordPath);
                        
                        if ($result === TRUE) {
                            $content = $zip->getFromName('word/document.xml');
                            $zip->close();
                            
                            if ($content !== false) {
                                // Limpar XML e extrair texto
                                $content = strip_tags($content);
                                $content = html_entity_decode($content);
                                $content = preg_replace('/\s+/', ' ', $content);
                                $content = trim($content);
                                
                                if (strlen($content) > 10) {
                                    $text = $content;
                                    $success = true;
                                    $extractionMethod = 'DOCX ZIP extraction';
                                    Log::info("Texto extraído usando método ZIP", ['length' => strlen($text)]);
                                }
                            }
                        }
                    } catch (Exception $e2) {
                        Log::warning("Falha na extração ZIP", ['error' => $e2->getMessage()]);
                    }
                }
                
                // Método 3: Tentar ler metadados básicos
                try {
                    $handle = fopen($wordPath, 'rb');
                    if ($handle) {
                        $content = fread($handle, min($fileSize, 8192)); // Ler primeiros 8KB
                        fclose($handle);
                        
                        // Procurar por texto legível no conteúdo
                        if (preg_match_all('/[a-zA-Z0-9\s.,!?;:()"\'-]{10,}/', $content, $matches)) {
                            $text = implode(' ', $matches[0]);
                            $text = preg_replace('/\s+/', ' ', $text);
                            $text = trim($text);
                            
                            if (strlen($text) > 20) {
                                $success = true;
                                $extractionMethod = 'Raw extraction';
                                Log::info("Texto extraído usando método bruto", ['length' => strlen($text)]);
                            }
                        }
                    }
                } catch (Exception $e2) {
                    Log::warning("Falha no método de extração bruta", ['error' => $e2->getMessage()]);
                }
                
                // Método 4: Fallback com informações do erro
                if (!$success) {
                    $text = "Documento Word convertido para PDF\n\n";
                    $text .= "⚠️ Nota: Não foi possível extrair o texto completo do documento.\n\n";
                    $text .= "Possíveis motivos:\n";
                    $text .= "• O documento contém formatação complexa ou comentários incompatíveis\n";
                    $text .= "• Documento protegido, criptografado ou corrompido\n";
                    $text .= "• Versão muito antiga ou muito nova do Word\n";
                    $text .= "• Documento contém principalmente imagens, tabelas ou objetos\n";
                    $text .= "• Erro de biblioteca: PhpWord não conseguiu processar o arquivo\n\n";
                    $text .= "💡 Sugestões:\n";
                    $text .= "• Tente salvar o documento em um formato mais simples (.txt)\n";
                    $text .= "• Remova comentários e formatação complexa do documento original\n";
                    $text .= "• Use 'Salvar Como' > 'Documento do Word (.docx)' para recriar o arquivo\n\n";
                    $text .= "Informações técnicas:\n";
                    $text .= "Arquivo original: " . basename($wordPath) . "\n";
                    $text .= "Tamanho: " . round($fileSize / 1024, 2) . " KB\n";
                    $text .= "Erro PhpWord: " . (isset($e) ? $e->getMessage() : 'Múltiplos erros de leitura') . "\n";
                    $text .= "Convertido em: " . date('d/m/Y H:i:s');
                    $success = true;
                    $extractionMethod = 'Informative fallback';
                }
            }
            
            if (!$success || empty(trim($text))) {
                $text = "❌ Não foi possível extrair conteúdo do documento Word.\n\n";
                $text .= "O arquivo foi processado, mas o conteúdo textual não pôde ser extraído.\n";
                $text .= "Isso pode acontecer com documentos que contêm principalmente:\n";
                $text .= "• Imagens, gráficos ou objetos incorporados\n";
                $text .= "• Tabelas complexas sem texto simples\n";
                $text .= "• Formatação muito avançada ou macros\n\n";
                $text .= "Arquivo: " . basename($wordPath) . "\n";
                $text .= "Convertido em: " . date('d/m/Y H:i:s');
                $extractionMethod = 'Final fallback';
            }
            
            Log::info("Criando PDF com texto extraído", [
                'method' => $extractionMethod,
                'text_length' => strlen($text)
            ]);
            
            // Criar PDF com TCPDF
            $pdf = new TCPDF();
            $pdf->AddPage();
            $pdf->SetFont('helvetica', '', 12);
            $pdf->SetMargins(15, 15, 15);
            
            // Título
            $pdf->SetFont('helvetica', 'B', 16);
            $pdf->Cell(0, 10, 'Documento Word Convertido', 0, 1, 'C');
            $pdf->Ln(5);
            
            // Informações do arquivo
            $pdf->SetFont('helvetica', '', 10);
            $pdf->SetTextColor(100, 100, 100);
            $pdf->Cell(0, 5, 'Método de extração: ' . $extractionMethod, 0, 1, 'L');
            $pdf->Cell(0, 5, 'Convertido em: ' . date('d/m/Y H:i:s'), 0, 1, 'L');
            $pdf->Ln(5);
            
            // Conteúdo
            $pdf->SetFont('helvetica', '', 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->MultiCell(0, 8, $text, 0, 'L');
            
            $pdf->Output($pdfPath, 'F');
            
            // Verificar se o PDF foi criado
            if (!file_exists($pdfPath)) {
                throw new Exception("PDF não foi criado");
            }
            
            $pdfSize = filesize($pdfPath);
            if ($pdfSize === false || $pdfSize == 0) {
                throw new Exception("PDF criado está vazio");
            }
            
            Log::info("Conversão Word para PDF concluída", [
                'pdf_size' => $pdfSize,
                'extraction_method' => $extractionMethod
            ]);
            
            return [
                'success' => true,
                'path' => $pdfPath,
                'filename' => basename($pdfPath),
                'size' => $pdfSize,
                'format' => 'pdf',
                'extraction_method' => $extractionMethod
            ];
            
        } catch (Exception $e) {
            Log::error("Erro ao converter Word para PDF", [
                'message' => $e->getMessage(),
                'word_path' => $wordPath,
                'trace' => $e->getTraceAsString()
            ]);
            throw new Exception("Erro ao converter Word para PDF: " . $e->getMessage());
        }
    }

    /**
     * Converter imagem para PDF
     */
    protected function convertImageToPdf($imagePath, $pdfPath)
    {
        try {
            // Verificar se o arquivo existe
            if (!file_exists($imagePath) || !is_readable($imagePath)) {
                throw new Exception("Arquivo de imagem não encontrado: {$imagePath}");
            }
            
            // Verificar se é uma imagem válida
            $imageInfo = getimagesize($imagePath);
            if ($imageInfo === false) {
                throw new Exception("Arquivo não é uma imagem válida");
            }
            
            $imageWidth = $imageInfo[0];
            $imageHeight = $imageInfo[1];
            
            if ($imageWidth <= 0 || $imageHeight <= 0) {
                throw new Exception("Dimensões da imagem inválidas");
            }
            
            $pdf = new TCPDF();
            $pdf->AddPage();
            
            // Calcular dimensões para caber na página
            $pageWidth = $pdf->getPageWidth() - 20; // margem
            $pageHeight = $pdf->getPageHeight() - 20; // margem
            
            $ratio = min($pageWidth / $imageWidth, $pageHeight / $imageHeight);
            $newWidth = $imageWidth * $ratio;
            $newHeight = $imageHeight * $ratio;
            
            // Centralizar imagem
            $x = ($pdf->getPageWidth() - $newWidth) / 2;
            $y = ($pdf->getPageHeight() - $newHeight) / 2;
            
            // Adicionar título
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 10, 'Imagem Convertida para PDF', 0, 1, 'C');
            $pdf->Ln(5);
            
            // Ajustar posição da imagem
            $y += 20;
            
            $pdf->Image($imagePath, $x, $y, $newWidth, $newHeight);
            
            // Adicionar informações no rodapé
            $pdf->SetY(-30);
            $pdf->SetFont('helvetica', 'I', 8);
            $pdf->Cell(0, 10, 'Convertido em: ' . date('d/m/Y H:i:s'), 0, 0, 'C');
            
            $pdf->Output($pdfPath, 'F');
            
            return [
                'success' => true,
                'path' => $pdfPath,
                'filename' => basename($pdfPath),
                'size' => filesize($pdfPath),
                'format' => 'pdf'
            ];
            
        } catch (Exception $e) {
            throw new Exception('Erro ao converter imagem para PDF: ' . $e->getMessage());
        }
    }

    /**
     * Extrair texto de elementos PhpWord
     */
    protected function extractTextFromElement($element)
    {
        $text = '';
        
        // Verificar se o elemento é válido
        if (!is_object($element)) {
            return '';
        }
        
        // Verificar o tipo do elemento e extrair texto apropriadamente
        $elementClass = get_class($element);
        
        try {
            switch ($elementClass) {
                case 'PhpOffice\PhpWord\Element\Text':
                    if (method_exists($element, 'getText')) {
                        $elementText = $element->getText();
                        if (is_string($elementText)) {
                            $text .= $elementText . " ";
                        }
                    }
                    break;
                    
                case 'PhpOffice\PhpWord\Element\TextRun':
                    if (method_exists($element, 'getElements')) {
                        $subElements = $element->getElements();
                        if (is_array($subElements) || is_iterable($subElements)) {
                            foreach ($subElements as $subElement) {
                                $text .= $this->extractTextFromElement($subElement);
                            }
                        }
                    }
                    break;
                    
                case 'PhpOffice\PhpWord\Element\TextBreak':
                    $text .= "\n";
                    break;
                    
                case 'PhpOffice\PhpWord\Element\Table':
                    // Extrair texto de tabelas
                    if (method_exists($element, 'getRows')) {
                        $rows = $element->getRows();
                        if (is_array($rows) || is_iterable($rows)) {
                            foreach ($rows as $row) {
                                if (method_exists($row, 'getCells')) {
                                    $cells = $row->getCells();
                                    if (is_array($cells) || is_iterable($cells)) {
                                        foreach ($cells as $cell) {
                                            if (method_exists($cell, 'getElements')) {
                                                $cellElements = $cell->getElements();
                                                if (is_array($cellElements) || is_iterable($cellElements)) {
                                                    foreach ($cellElements as $cellElement) {
                                                        $text .= $this->extractTextFromElement($cellElement);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                $text .= "\n"; // Nova linha após cada linha da tabela
                            }
                        }
                    }
                    break;
                    
                case 'PhpOffice\PhpWord\Element\ListItem':
                    // Extrair texto de listas
                    if (method_exists($element, 'getElements')) {
                        $listElements = $element->getElements();
                        if (is_array($listElements) || is_iterable($listElements)) {
                            $text .= "• "; // Marcador de lista
                            foreach ($listElements as $listElement) {
                                $text .= $this->extractTextFromElement($listElement);
                            }
                            $text .= "\n";
                        }
                    }
                    break;
                    
                default:
                    // Para outros elementos, tentar métodos comuns
                    if (method_exists($element, 'getText')) {
                        $elementText = $element->getText();
                        if (is_string($elementText)) {
                            $text .= $elementText . " ";
                        }
                    } elseif (method_exists($element, 'getElements')) {
                        $subElements = $element->getElements();
                        if (is_array($subElements) || is_iterable($subElements)) {
                            foreach ($subElements as $subElement) {
                                $text .= $this->extractTextFromElement($subElement);
                            }
                        }
                    } elseif (method_exists($element, 'getContent')) {
                        $content = $element->getContent();
                        if (is_string($content)) {
                            $text .= $content . " ";
                        }
                    }
                    break;
            }
        } catch (Exception $e) {
            // Se houver erro, continuar sem quebrar o processo
            Log::debug("Erro ao processar elemento {$elementClass}: " . $e->getMessage());
            $text .= "[Elemento não processado] ";
        }
        
        return $text;
    }

    /**
     * Adicionar assinatura eletrônica ao PDF
     */
    public function addElectronicSignature(UploadedFile $file, array $signatureData)
    {
        try {
            $originalPath = $this->saveTemporaryFile($file);
            $signedPath = $this->tempPath . '/' . uniqid('signed_') . '.pdf';

            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile($originalPath);

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $pdf->AddPage();
                $tplId = $pdf->importPage($pageNo);
                $pdf->useTemplate($tplId);

                // Adicionar assinatura apenas na primeira página
                if ($pageNo == 1) {
                    $this->addSignatureToPdf($pdf, $signatureData);
                }
            }

            $pdf->Output($signedPath, 'F');

            return [
                'success' => true,
                'path' => $signedPath,
                'filename' => basename($signedPath),
                'size' => filesize($signedPath)
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Adicionar múltiplas assinaturas eletrônicas ao PDF
     */
    public function addMultipleElectronicSignatures(UploadedFile $file, array $signatures)
    {
        try {
            $originalPath = $this->saveTemporaryFile($file);
            $signedPath = $this->tempPath . '/' . uniqid('signed_') . '.pdf';

            // Log detalhado do processo
            Log::info('Iniciando processo de assinatura múltipla', [
                'signatures_count' => count($signatures),
                'original_file' => $file->getClientOriginalName(),
                'original_size' => $file->getSize()
            ]);

            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile($originalPath);

            Log::info('PDF carregado', [
                'pages' => $pageCount,
                'signatures_to_process' => count($signatures)
            ]);

            // Agrupar assinaturas por página
            $signaturesByPage = [];
            foreach ($signatures as $index => $signature) {
                $page = intval($signature['page']);
                if (!isset($signaturesByPage[$page])) {
                    $signaturesByPage[$page] = [];
                }
                $signaturesByPage[$page][] = $signature;
                
                Log::info("Assinatura {$index} agrupada", [
                    'page' => $page,
                    'type' => $signature['type'],
                    'x' => $signature['x'],
                    'y' => $signature['y'],
                    'width' => $signature['width'],
                    'height' => $signature['height']
                ]);
            }

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                // Importar página original
                $tplId = $pdf->importPage($pageNo);
                
                // Obter dimensões da página
                $size = $pdf->getTemplateSize($tplId);
                
                // Adicionar página com as mesmas dimensões da original
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                
                // Usar o template da página original
                $pdf->useTemplate($tplId);

                Log::info("Processando página {$pageNo} de {$pageCount}");

                // Adicionar assinaturas desta página
                if (isset($signaturesByPage[$pageNo])) {
                    Log::info("Adicionando " . count($signaturesByPage[$pageNo]) . " assinaturas na página {$pageNo}");
                    
                    foreach ($signaturesByPage[$pageNo] as $index => $signature) {
                        Log::info("Processando assinatura {$index} na página {$pageNo}", [
                            'type' => $signature['type'],
                            'data_length' => strlen($signature['data']),
                            'coordinates' => [
                                'x' => $signature['x'],
                                'y' => $signature['y'],
                                'width' => $signature['width'],
                                'height' => $signature['height']
                            ]
                        ]);
                        
                        $this->addAdvancedSignatureToPdf($pdf, $signature);
                    }
                } else {
                    Log::info("Nenhuma assinatura na página {$pageNo}");
                }
            }

            $pdf->Output($signedPath, 'F');
            
            // Verificar se o arquivo foi criado corretamente
            if (!file_exists($signedPath)) {
                throw new Exception('PDF assinado não foi criado: ' . $signedPath);
            }
            
            $resultSize = filesize($signedPath);
            if ($resultSize === false || $resultSize === 0) {
                throw new Exception('PDF assinado está vazio ou corrompido');
            }
            
            // Aguardar um pouco para garantir que o arquivo está completamente escrito
            usleep(200000); // 200ms
            
            // Verificar novamente o tamanho
            clearstatcache(true, $signedPath);
            $resultSize = filesize($signedPath);
            
            if ($resultSize === false || $resultSize === 0) {
                throw new Exception('PDF assinado permanece vazio após espera');
            }
            Log::info('PDF assinado gerado', [
                'output_path' => $signedPath,
                'output_size' => $resultSize,
                'signatures_applied' => count($signatures)
            ]);

            return [
                'success' => true,
                'path' => $signedPath,
                'filename' => basename($signedPath),
                'size' => $resultSize
            ];

        } catch (Exception $e) {
            Log::error('Erro ao adicionar múltiplas assinaturas: ' . $e->getMessage(), [
                'signatures_count' => count($signatures),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Adicionar assinatura ao PDF
     */
    protected function addSignatureToPdf($pdf, $signatureData)
    {
        $x = floatval($signatureData['position_x'] ?? 50);
        $y = floatval($signatureData['position_y'] ?? 80);

        switch ($signatureData['signature_type']) {
            case 'text':
                $pdf->SetFont('Arial', 'I', 14);
                $pdf->SetTextColor(0, 0, 139);
                $pdf->SetXY($x, $y);
                $pdf->Write(10, $signatureData['signature_data']);
                
                // Adicionar linha abaixo da assinatura
                $pdf->Line($x, $y + 10, $x + 60, $y + 10);
                $pdf->SetXY($x, $y + 12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->Write(8, 'Assinatura Digital - ' . date('d/m/Y H:i'));
                break;

            case 'draw':
                // Para assinatura desenhada, adicionar texto indicativo
                $pdf->SetFont('Arial', 'I', 12);
                $pdf->SetTextColor(0, 0, 139);
                $pdf->SetXY($x, $y);
                $pdf->Write(10, '[Assinatura Digital]');
                $pdf->Line($x, $y + 8, $x + 50, $y + 8);
                break;

            case 'upload':
                // Para imagem de assinatura, adicionar placeholder
                $pdf->SetFont('Arial', 'I', 12);
                $pdf->SetTextColor(0, 0, 139);
                $pdf->SetXY($x, $y);
                $pdf->Write(10, '[Assinatura de Imagem]');
                $pdf->Line($x, $y + 8, $x + 50, $y + 8);
                break;
        }

        // Adicionar timestamp
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->SetXY($x, $y + 15);
        $pdf->Write(8, 'Assinado digitalmente em ' . date('d/m/Y às H:i:s'));
    }

    /**
     * Adicionar assinatura avançada ao PDF
     */
    protected function addAdvancedSignatureToPdf($pdf, $signature)
    {
        Log::info('=== ADICIONANDO ASSINATURA ===', [
            'type' => $signature['type'],
            'data' => substr($signature['data'], 0, 50),
            'coordinates' => [
                'x' => $signature['x'],
                'y' => $signature['y'],
                'width' => $signature['width'],
                'height' => $signature['height']
            ]
        ]);

        // Usar coordenadas reais do usuário com conversão apropriada
        // O frontend envia coordenadas do canvas, precisamos converter para coordenadas PDF
        
        // O canvas do frontend usa dimensões em pixels, o PDF usa milímetros
        // Assumindo que o canvas representa uma página A4 (595x842 pontos ou 210x297 mm)
        $canvasWidth = 595;  // Largura do canvas em pontos
        $canvasHeight = 842; // Altura do canvas em pontos
        
        // Converter coordenadas proporcionalmente
        $x = (floatval($signature['x']) / $canvasWidth) * 210; // Converter para mm
        $y = (floatval($signature['y']) / $canvasHeight) * 297; // Converter para mm  
        $width = max(50, (floatval($signature['width']) / $canvasWidth) * 210);
        $height = max(15, (floatval($signature['height']) / $canvasHeight) * 297);
        
        // Garantir que esteja dentro dos limites da página
        $x = max(5, min($x, 200)); // Margem de 5mm
        $y = max(5, min($y, 287)); // Margem de 5mm

        Log::info('Usando coordenadas reais do usuário', [
            'original' => ['x' => $signature['x'], 'y' => $signature['y'], 'width' => $signature['width'], 'height' => $signature['height']],
            'converted' => ['x' => $x, 'y' => $y, 'width' => $width, 'height' => $height],
            'canvas_dimensions' => ['width' => $canvasWidth, 'height' => $canvasHeight]
        ]);

        try {
            // Adicionar a assinatura baseada no tipo
            switch ($signature['type']) {
                case 'text':
                    // Configurar fonte e cor para texto
                    $pdf->SetFont('Arial', 'I', 14);
                    $pdf->SetTextColor(0, 0, 139); // Azul escuro
                    $pdf->SetXY($x, $y);
                    $pdf->Write(0, $signature['data']);
                    
                    // Adicionar linha decorativa abaixo
                    $pdf->Line($x, $y + 12, $x + $width, $y + 12);
                    break;
                    
                case 'draw':
                    $this->addDrawnSignatureToPdf($pdf, $signature, $x, $y, $width, $height);
                    break;
                    
                case 'upload':
                    $this->addImageSignatureToPdf($pdf, $signature, $x, $y, $width, $height);
                    break;
            }
            
            // Adicionar timestamp discreto
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetTextColor(128, 128, 128);
            $pdf->SetXY($x, $y + $height + 5);
            $pdf->Write(0, 'Assinado digitalmente em ' . date('d/m/Y às H:i:s'));
            
            Log::info('Assinatura adicionada com sucesso', [
                'type' => $signature['type'],
                'position' => ['x' => $x, 'y' => $y, 'width' => $width, 'height' => $height]
            ]);
            
        } catch (Exception $e) {
            Log::error('ERRO ao adicionar assinatura: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Fallback absoluto
            try {
                $pdf->SetFont('Arial', 'B', 14);
                $pdf->SetTextColor(255, 0, 0);
                $pdf->SetXY(20, 20);
                $pdf->Write(0, 'ERRO NA ASSINATURA');
            } catch (Exception $fallbackError) {
                Log::error('Fallback também falhou: ' . $fallbackError->getMessage());
            }
        }
    }

    /**
     * Adicionar assinatura de texto
     */
    protected function addTextSignatureToPdf($pdf, $signature, $x, $y, $width, $height)
    {
        try {
            $fontSize = isset($signature['fontSize']) ? max(8, min(24, intval($signature['fontSize']))) : 12;
            $text = $signature['data'];
            
            Log::info('Adicionando assinatura de texto', [
                'text' => $text,
                'fontSize' => $fontSize,
                'position' => ['x' => $x, 'y' => $y, 'width' => $width, 'height' => $height]
            ]);
            
            // Configurar fonte e cor
            $pdf->SetFont('Arial', 'I', $fontSize);
            $pdf->SetTextColor(0, 0, 255); // Azul para ser mais visível
            
            // Posicionar e adicionar texto
            $pdf->SetXY($x, $y);
            
            // Adicionar fundo branco para garantir visibilidade
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Rect($x, $y, $width, $height, 'F');
            
            // Adicionar texto com borda para debugging
            $pdf->SetXY($x, $y);
            $pdf->Cell($width, $height, $text, 1, 0, 'C', false);
            
            Log::info('Assinatura de texto adicionada com sucesso');
            
        } catch (Exception $e) {
            Log::error('Erro ao adicionar assinatura de texto: ' . $e->getMessage());
            
            // Fallback muito simples
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetTextColor(255, 0, 0);
            $pdf->SetXY($x, $y);
            $pdf->Write(0, 'ASSINATURA: ' . $signature['data']);
        }
    }

    /**
     * Adicionar assinatura desenhada
     */
    protected function addDrawnSignatureToPdf($pdf, $signature, $x, $y, $width, $height)
    {
        try {
            Log::info('Adicionando assinatura desenhada', [
                'data_length' => strlen($signature['data']),
                'position' => ['x' => $x, 'y' => $y, 'width' => $width, 'height' => $height]
            ]);
            
            // Extrair dados base64
            $base64Data = preg_replace('#^data:image/[^;]+;base64,#', '', $signature['data']);
            
            if (empty($base64Data)) {
                throw new Exception('Dados base64 vazios para assinatura desenhada');
            }
            
            $imageData = base64_decode($base64Data);
            if ($imageData === false) {
                throw new Exception('Falha ao decodificar base64 para assinatura desenhada');
            }
            
            Log::info('Base64 decodificado', [
                'original_length' => strlen($base64Data),
                'decoded_length' => strlen($imageData)
            ]);
            
            // Criar arquivo temporário
            $tempImagePath = $this->tempPath . '/' . uniqid('signature_') . '.png';
            
            // Verificar se o diretório existe
            if (!file_exists($this->tempPath)) {
                mkdir($this->tempPath, 0755, true);
            }
            
            // Salvar imagem temporária
            if (file_put_contents($tempImagePath, $imageData) === false) {
                throw new Exception('Falha ao salvar imagem temporária');
            }
            
            // Verificar se a imagem foi criada corretamente
            if (!file_exists($tempImagePath) || filesize($tempImagePath) === 0) {
                throw new Exception('Imagem temporária não foi criada corretamente');
            }
            
            Log::info('Imagem temporária criada', [
                'path' => $tempImagePath,
                'size' => filesize($tempImagePath)
            ]);
            
            // Adicionar imagem ao PDF
            $pdf->Image($tempImagePath, $x, $y, $width, $height, 'PNG');
            
            Log::info('Imagem adicionada ao PDF com sucesso');
            
            // Limpar arquivo temporário
            @unlink($tempImagePath);
            
        } catch (Exception $e) {
            Log::error('Erro ao processar assinatura desenhada: ' . $e->getMessage());
            
            // Fallback: adicionar texto indicativo com borda visível
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetTextColor(255, 0, 0);
            $pdf->SetXY($x, $y);
            $pdf->Cell($width, $height, '[Assinatura Digital]', 1, 0, 'C');
        }
    }

    /**
     * Adicionar assinatura de imagem
     */
    protected function addImageSignatureToPdf($pdf, $signature, $x, $y, $width, $height)
    {
        try {
            Log::info('Adicionando assinatura de imagem upload', [
                'data_length' => strlen($signature['data']),
                'position' => ['x' => $x, 'y' => $y, 'width' => $width, 'height' => $height]
            ]);
            
            // Extrair dados base64
            $base64Data = preg_replace('#^data:image/[^;]+;base64,#', '', $signature['data']);
            
            if (empty($base64Data)) {
                throw new Exception('Dados base64 vazios para assinatura de imagem');
            }
            
            $imageData = base64_decode($base64Data);
            if ($imageData === false) {
                throw new Exception('Falha ao decodificar base64 para assinatura de imagem');
            }
            
            // Detectar tipo de imagem
            $imageInfo = @getimagesizefromstring($imageData);
            $format = 'JPEG'; // Padrão
            $extension = 'jpg';
            
            if ($imageInfo && isset($imageInfo['mime'])) {
                if (strpos($imageInfo['mime'], 'png') !== false) {
                    $format = 'PNG';
                    $extension = 'png';
                } elseif (strpos($imageInfo['mime'], 'gif') !== false) {
                    $format = 'GIF';
                    $extension = 'gif';
                }
            }
            
            Log::info('Tipo de imagem detectado', [
                'format' => $format,
                'extension' => $extension,
                'image_info' => $imageInfo
            ]);
            
            // Criar arquivo temporário
            $tempImagePath = $this->tempPath . '/' . uniqid('signature_img_') . '.' . $extension;
            
            // Verificar se o diretório existe
            if (!file_exists($this->tempPath)) {
                mkdir($this->tempPath, 0755, true);
            }
            
            // Salvar imagem temporária
            if (file_put_contents($tempImagePath, $imageData) === false) {
                throw new Exception('Falha ao salvar imagem temporária');
            }
            
            // Verificar se a imagem foi criada corretamente
            if (!file_exists($tempImagePath) || filesize($tempImagePath) === 0) {
                throw new Exception('Imagem temporária não foi criada corretamente');
            }
            
            Log::info('Imagem temporária criada', [
                'path' => $tempImagePath,
                'size' => filesize($tempImagePath)
            ]);
            
            // Adicionar imagem ao PDF
            $pdf->Image($tempImagePath, $x, $y, $width, $height, $format);
            
            Log::info('Imagem de upload adicionada ao PDF com sucesso');
            
            // Limpar arquivo temporário
            @unlink($tempImagePath);
            
        } catch (Exception $e) {
            Log::error('Erro ao processar assinatura de imagem: ' . $e->getMessage());
            
            // Fallback: adicionar texto indicativo com borda visível
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetTextColor(255, 0, 0);
            $pdf->SetXY($x, $y);
            $pdf->Cell($width, $height, '[Assinatura de Imagem]', 1, 0, 'C');
        }
    }

    /**
     * Adicionar metadados da assinatura
     */
    protected function addSignatureMetadata($pdf, $x, $y)
    {
        $pdf->SetFont('Arial', '', 6);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->SetXY($x, $y);
        $pdf->Write(6, 'Assinado digitalmente via UNIDOC em ' . date('d/m/Y às H:i:s'));
    }

    /**
     * Comprimir PDF
     */
    public function compressPdf(UploadedFile $file, string $compressionLevel, array $options = [])
    {
        try {
            $originalPath = $this->saveTemporaryFile($file);
            $compressedPath = $this->tempPath . '/' . uniqid('compressed_') . '.pdf';

            // Simular compressão copiando e otimizando
            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile($originalPath);

            // Configurar compressão baseada no nível
            switch ($compressionLevel) {
                case 'low':
                    $pdf->SetCompression(false);
                    break;
                case 'medium':
                    $pdf->SetCompression(true);
                    break;
                case 'high':
                    $pdf->SetCompression(true);
                    break;
            }

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $pdf->AddPage();
                $tplId = $pdf->importPage($pageNo);
                $pdf->useTemplate($tplId);
            }

            $pdf->Output($compressedPath, 'F');

            // Calcular estatísticas
            $originalSize = filesize($originalPath);
            $compressedSize = filesize($compressedPath);
            $reduction = $originalSize - $compressedSize;
            $reductionPercent = $originalSize > 0 ? round(($reduction / $originalSize) * 100, 1) : 0;

            return [
                'success' => true,
                'path' => $compressedPath,
                'filename' => basename($compressedPath),
                'original_size' => $this->formatBytes($originalSize),
                'compressed_size' => $this->formatBytes($compressedSize),
                'reduction' => $this->formatBytes($reduction),
                'reduction_percent' => $reductionPercent . '%'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Salvar arquivo temporário
     */
    protected function saveTemporaryFile(UploadedFile $file)
    {
        try {
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $mimeType = $file->getMimeType();
            $size = $file->getSize();
            $tempFilePath = $file->getPathname();
            
            Log::info("Iniciando salvamento de arquivo temporário", [
                'original_name' => $originalName,
                'extension' => $extension,
                'mime_type' => $mimeType,
                'size' => $size,
                'temp_path' => $tempFilePath,
                'temp_exists' => file_exists($tempFilePath),
                'temp_readable' => is_readable($tempFilePath)
            ]);
            
            // Verificar se o arquivo é válido
            if (!$file->isValid()) {
                $uploadError = $file->getError();
                $errorMessage = $file->getErrorMessage();
                Log::error("Arquivo de upload inválido", [
                    'error_code' => $uploadError,
                    'error_message' => $errorMessage
                ]);
                throw new Exception("Arquivo inválido: " . $errorMessage);
            }
            
            // Verificar se o arquivo temporário existe
            if (!file_exists($tempFilePath)) {
                throw new Exception("Arquivo temporário não encontrado: {$tempFilePath}");
            }
            
            if (!is_readable($tempFilePath)) {
                throw new Exception("Arquivo temporário não é legível: {$tempFilePath}");
            }
            
            // Verificar se o diretório temporário existe e é gravável
            if (!file_exists($this->tempPath)) {
                Log::info("Criando diretório temporário: {$this->tempPath}");
                if (!mkdir($this->tempPath, 0755, true)) {
                    throw new Exception("Não foi possível criar diretório temporário: {$this->tempPath}");
                }
            }
            
            if (!is_writable($this->tempPath)) {
                // Tentar corrigir permissões
                chmod($this->tempPath, 0755);
                if (!is_writable($this->tempPath)) {
                    throw new Exception("Diretório temporário não é gravável: {$this->tempPath}");
                }
            }
            
            $filename = uniqid('temp_') . '.' . $extension;
            $destinationPath = $this->tempPath . '/' . $filename;
            
            Log::info("Tentando salvar arquivo", [
                'destination' => $destinationPath,
                'temp_dir_writable' => is_writable($this->tempPath),
                'temp_file_size' => filesize($tempFilePath)
            ]);
            
            // Tentar diferentes métodos de salvamento
            $success = false;
            
            // Verificar se o arquivo temporário ainda existe
            $tempFilePath = $file->getPathname();
            if (!file_exists($tempFilePath)) {
                throw new Exception("Arquivo temporário do upload não encontrado ou já foi movido: {$tempFilePath}");
            }
            
            // Método 1: copy() primeiro (mais seguro para arquivos temporários)
            try {
                if (copy($tempFilePath, $destinationPath)) {
                    $success = true;
                    Log::info("Arquivo copiado com sucesso usando copy()");
                }
            } catch (Exception $e) {
                Log::warning("Falha ao usar copy(): " . $e->getMessage());
            }
            
            // Método 2: storeAs() se copy() falhar
            if (!$success) {
                try {
                    $storedPath = $file->storeAs('temp', $filename, 'local');
                    if ($storedPath) {
                        $destinationPath = storage_path('app/' . $storedPath);
                        $success = true;
                        Log::info("Arquivo salvo com sucesso usando storeAs(): {$destinationPath}");
                    }
                } catch (Exception $e) {
                    Log::warning("Falha ao usar storeAs(): " . $e->getMessage());
                }
            }
            
            // Método 3: move() como último recurso
            if (!$success) {
                try {
                    if ($file->move($this->tempPath, $filename)) {
                        $success = true;
                        Log::info("Arquivo movido com sucesso usando move()");
                    }
                } catch (Exception $e) {
                    Log::warning("Falha ao usar move(): " . $e->getMessage());
                }
            }
            
            if (!$success) {
                throw new Exception("Todas as tentativas de salvar o arquivo falharam");
            }
            
            // Verificar se o arquivo foi salvo corretamente e aguardar um pouco
            usleep(100000); // Esperar 100ms para garantir que o arquivo esteja disponível
            
            if (!file_exists($destinationPath)) {
                throw new Exception("Arquivo não foi criado: {$destinationPath}");
            }
            
            if (!is_readable($destinationPath)) {
                throw new Exception("Arquivo não é legível: {$destinationPath}");
            }
            
            $savedSize = filesize($destinationPath);
            if ($savedSize === false || $savedSize == 0) {
                throw new Exception("Arquivo salvo está vazio ou corrompido");
            }
            
            Log::info("Arquivo temporário salvo com sucesso", [
                'path' => $destinationPath,
                'size' => $savedSize,
                'readable' => is_readable($destinationPath),
                'writable' => is_writable($destinationPath)
            ]);
            
            return $destinationPath;
            
        } catch (Exception $e) {
            Log::error("Erro ao salvar arquivo temporário", [
                'message' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
                'temp_path' => $this->tempPath
            ]);
            throw new Exception("Erro ao processar arquivo: " . $e->getMessage());
        }
    }

    /**
     * Formatar bytes
     */
    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Limpar arquivos temporários
     */
    public function cleanupTempFiles()
    {
        $files = glob($this->tempPath . '/temp_*');
        $files = array_merge($files, glob($this->tempPath . '/edited_*'));
        $files = array_merge($files, glob($this->tempPath . '/signed_*'));
        $files = array_merge($files, glob($this->tempPath . '/compressed_*'));
        
        foreach ($files as $file) {
            if (file_exists($file) && (time() - filemtime($file)) > 3600) { // 1 hora
                unlink($file);
            }
        }
    }

    /**
     * Gerar URL para download temporário
     */
    public function getTempDownloadUrl($filePath)
    {
        try {
            if (!file_exists($filePath)) {
                throw new Exception("Arquivo não encontrado: {$filePath}");
            }
            
            $originalSize = filesize($filePath);
            if ($originalSize === false || $originalSize === 0) {
                throw new Exception("Arquivo vazio ou corrompido: {$filePath}");
            }
            
            $filename = basename($filePath);
            $publicPath = 'temp/' . $filename;
            
            // Criar diretório público temporário
            $publicTempDir = public_path('temp');
            if (!file_exists($publicTempDir)) {
                if (!mkdir($publicTempDir, 0755, true)) {
                    throw new Exception("Não foi possível criar diretório temporário público");
                }
            }
            
            $destinationPath = $publicTempDir . '/' . $filename;
            
            // Copiar arquivo
            if (!copy($filePath, $destinationPath)) {
                throw new Exception("Falha ao copiar arquivo para diretório público");
            }
            
            // Verificar se a cópia foi bem-sucedida
            if (!file_exists($destinationPath)) {
                throw new Exception("Arquivo não foi copiado para o diretório público");
            }
            
            $copiedSize = filesize($destinationPath);
            if ($copiedSize === false || $copiedSize === 0) {
                throw new Exception("Arquivo copiado está vazio");
            }
            
            if ($copiedSize !== $originalSize) {
                Log::warning("Tamanho do arquivo copiado diferente do original", [
                    'original_size' => $originalSize,
                    'copied_size' => $copiedSize
                ]);
            }
            
            Log::info("Arquivo copiado para download", [
                'original_path' => $filePath,
                'public_path' => $destinationPath,
                'original_size' => $originalSize,
                'copied_size' => $copiedSize
            ]);
            
            return url('temp/' . $filename);
            
        } catch (Exception $e) {
            Log::error("Erro ao preparar download: " . $e->getMessage());
            throw $e;
        }
    }
}