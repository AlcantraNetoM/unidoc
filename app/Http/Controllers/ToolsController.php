<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Services\PdfService;

class ToolsController extends Controller
{
    protected $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }
    /**
     * Exibe o editor avançado de PDF
     */
    public function advancedEditPdf()
    {
        return view('tools.advanced-edit-pdf');
    }

    /**
     * Exibe a página de conversão de PDF
     */
    public function convertPdf()
    {
        return view('tools.convert-pdf');
    }

    /**
     * Processa a conversão de PDF
     */
    public function processConvertPdf(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // Max 10MB
            'output_format' => 'required|in:pdf'
        ]);

        try {
            $file = $request->file('file');
            $outputFormat = $request->input('output_format');
            
            $result = $this->pdfService->convertPdf($file, $outputFormat);
            
            if ($result['success']) {
                $downloadUrl = $this->pdfService->getTempDownloadUrl($result['path']);
                
                return response()->json([
                    'success' => true,
                    'message' => "Arquivo convertido para {$outputFormat} com sucesso!",
                    'download_url' => $downloadUrl,
                    'filename' => $result['filename'],
                    'size' => $this->formatBytes($result['size']),
                    'format' => $result['format']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao converter arquivo: ' . $result['error']
                ], 500);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao converter arquivo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exibe a página de assinaturas eletrônicas
     */
    public function electronicSignature()
    {
        return view('tools.electronic-signature');
    }

    /**
     * Processa a assinatura eletrônica (versão avançada)
     */
    public function processElectronicSignature(Request $request)
    {
        // Log detalhado dos dados recebidos para debug
        \Illuminate\Support\Facades\Log::info('Electronic Signature Request Debug', [
            'method' => $request->method(),
            'content_type' => $request->header('Content-Type'),
            'content_length' => $request->header('Content-Length'),
            'has_pdf_file' => $request->hasFile('pdf_file'),
            'pdf_file_name' => $request->hasFile('pdf_file') ? $request->file('pdf_file')->getClientOriginalName() : null,
            'pdf_file_size' => $request->hasFile('pdf_file') ? $request->file('pdf_file')->getSize() : null,
            'pdf_pages' => $request->input('pdf_pages'),
            'signatures_length' => strlen($request->input('signatures', '')),
            'signatures_start' => substr($request->input('signatures', ''), 0, 200),
            'all_inputs' => array_keys($request->all()),
            'request_size' => $request->server('CONTENT_LENGTH')
        ]);

        // Verificar se é uma requisição POST válida
        if (!$request->isMethod('POST')) {
            return response()->json([
                'success' => false,
                'message' => 'Método de requisição inválido.'
            ], 405);
        }

        // Verificar se o Content-Type é multipart/form-data
        $contentType = $request->header('Content-Type', '');
        if (!str_contains($contentType, 'multipart/form-data')) {
            \Illuminate\Support\Facades\Log::warning('Invalid content type', [
                'content_type' => $contentType,
                'expected' => 'multipart/form-data'
            ]);
        }

        try {
            // Verificar se todos os dados essenciais estão presentes antes da validação
            if (!$request->hasFile('pdf_file')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Arquivo PDF não foi enviado.'
                ], 400);
            }

            if (!$request->has('signatures')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dados de assinaturas não foram enviados.'
                ], 400);
            }

            if (!$request->has('pdf_pages')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Número de páginas do PDF não foi informado.'
                ], 400);
            }

            // Validação mais flexível
            $request->validate([
                'pdf_file' => 'required|file|mimes:pdf|max:10240',
                'signatures' => 'required', // Remover restrição de string
                'pdf_pages' => 'required|numeric|min:1'  // Mudar de integer para numeric
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::error('Validation failed', [
                'errors' => $e->errors(),
                'input' => [
                    'pdf_file' => $request->hasFile('pdf_file') ? 'file present' : 'no file',
                    'signatures' => substr($request->input('signatures', ''), 0, 100),
                    'pdf_pages' => $request->input('pdf_pages'),
                    'pdf_pages_type' => gettype($request->input('pdf_pages'))
                ]
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos: ' . collect($e->errors())->flatten()->first()
            ], 422);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Request parsing error', [
                'error' => $e->getMessage(),
                'type' => get_class($e),
                'content_length' => $request->header('Content-Length'),
                'content_type' => $request->header('Content-Type')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar a requisição. Tente recarregar a página e enviar novamente.'
            ], 400);
        }

        try {
            $file = $request->file('pdf_file');
            $signaturesInput = $request->input('signatures');
            $pdfPages = intval($request->input('pdf_pages'));
            
            \Illuminate\Support\Facades\Log::info('Processing signatures', [
                'signatures_type' => gettype($signaturesInput),
                'signatures_content' => is_string($signaturesInput) ? substr($signaturesInput, 0, 500) : $signaturesInput,
                'pdf_pages' => $pdfPages
            ]);
            
            // Decodificar JSON se for string
            if (is_string($signaturesInput)) {
                $signatures = json_decode($signaturesInput, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    \Illuminate\Support\Facades\Log::error('JSON decode error', [
                        'error' => json_last_error_msg(),
                        'json_string' => $signaturesInput
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Erro ao decodificar dados de assinaturas: ' . json_last_error_msg()
                    ], 400);
                }
            } else {
                $signatures = $signaturesInput;
            }
            
            // Validar estrutura das assinaturas
            if (!is_array($signatures) || empty($signatures)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhuma assinatura fornecida.'
                ], 400);
            }

            // Validar cada assinatura
            foreach ($signatures as $signature) {
                if (!isset($signature['type']) || !in_array($signature['type'], ['text', 'draw', 'upload'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tipo de assinatura inválido.'
                    ], 400);
                }

                if (!isset($signature['data']) || empty($signature['data'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Dados da assinatura não fornecidos.'
                    ], 400);
                }
                
                // Validar dados específicos por tipo
                if ($signature['type'] === 'text') {
                    if (empty(trim($signature['data']))) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Texto da assinatura não pode estar vazio.'
                        ], 400);
                    }
                    
                    // Validar se o texto não tem caracteres problemáticos
                    if (strlen($signature['data']) > 200) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Texto da assinatura muito longo (máx. 200 caracteres).'
                        ], 400);
                    }
                }
                
                if ($signature['type'] === 'draw' || $signature['type'] === 'upload') {
                    // Validar formato base64
                    if (!preg_match('/^data:image\/(png|jpeg|jpg|gif);base64,/', $signature['data'])) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Formato de imagem inválido para assinatura.'
                        ], 400);
                    }
                    
                    // Validar tamanho do base64 (não deve ser muito grande)
                    if (strlen($signature['data']) > 1500000) { // ~1MB em base64
                        return response()->json([
                            'success' => false,
                            'message' => 'Imagem da assinatura muito grande. Use uma imagem menor.'
                        ], 400);
                    }
                    
                    // Verificar se o base64 é válido
                    $base64Data = preg_replace('/^data:image\/[^;]+;base64,/', '', $signature['data']);
                    if (!base64_decode($base64Data, true)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Dados de imagem corrompidos.'
                        ], 400);
                    }
                }

                $requiredFields = ['x', 'y', 'width', 'height', 'page'];
                foreach ($requiredFields as $field) {
                    if (!isset($signature[$field]) || !is_numeric($signature[$field])) {
                        return response()->json([
                            'success' => false,
                            'message' => "Campo '{$field}' da assinatura é obrigatório e deve ser numérico."
                        ], 400);
                    }
                }

                // Validar página
                if ($signature['page'] < 1 || $signature['page'] > $pdfPages) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Página da assinatura inválida.'
                    ], 400);
                }
            }
            
            $result = $this->pdfService->addMultipleElectronicSignatures($file, $signatures);
            
            if ($result['success']) {
                $downloadUrl = $this->pdfService->getTempDownloadUrl($result['path']);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Documento assinado com sucesso! (' . count($signatures) . ' assinatura' . (count($signatures) > 1 ? 's' : '') . ' adicionada' . (count($signatures) > 1 ? 's' : '') . ')',
                    'download_url' => $downloadUrl,
                    'filename' => $result['filename'],
                    'size' => $this->formatBytes($result['size']),
                    'signatures_count' => count($signatures)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao assinar documento: ' . $result['error']
                ], 500);
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::error('Validation error in electronic signature', [
                'errors' => $e->errors(),
                'input_keys' => array_keys($request->all())
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro de validação: ' . collect($e->errors())->flatten()->first()
            ], 422);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erro na assinatura eletrônica: ' . $e->getMessage(), [
                'exception_class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => [
                    'has_file' => $request->hasFile('pdf_file'),
                    'signatures_type' => gettype($request->input('signatures')),
                    'pdf_pages' => $request->input('pdf_pages')
                ]
            ]);

            // Detectar tipos específicos de erro
            $errorMessage = 'Erro ao processar documento: ';
            
            if (strpos($e->getMessage(), 'pattern') !== false) {
                $errorMessage .= 'Formato de dados inválido. Verifique se as assinaturas estão corretas.';
            } elseif (strpos($e->getMessage(), 'validation') !== false) {
                $errorMessage .= 'Dados de validação inválidos.';
            } elseif (strpos($e->getMessage(), 'JSON') !== false) {
                $errorMessage .= 'Erro no formato JSON das assinaturas.';
            } else {
                $errorMessage .= $e->getMessage();
            }

            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ], 500);
        }
    }

    /**
     * Exibe a página de compressão de PDF
     */
    public function compressPdf()
    {
        return view('tools.compress-pdf');
    }

    /**
     * Processa a compressão de PDF
     */
    public function processCompressPdf(Request $request)
    {
        $request->validate([
            'pdf_file' => 'required|file|mimes:pdf|max:50240', // Max 50MB para compressão
            'compression_level' => 'required|in:low,medium,high'
        ]);

        try {
            $file = $request->file('pdf_file');
            $compressionLevel = $request->input('compression_level');
            $options = [];
            
            // Verificar opções de compressão se fornecidas
            if ($request->has('options')) {
                $options = json_decode($request->input('options'), true) ?: [];
            }
            
            $result = $this->pdfService->compressPdf($file, $compressionLevel, $options);
            
            if ($result['success']) {
                $downloadUrl = $this->pdfService->getTempDownloadUrl($result['path']);
                
                return response()->json([
                    'success' => true,
                    'message' => 'PDF comprimido com sucesso!',
                    'download_url' => $downloadUrl,
                    'filename' => $result['filename'],
                    'original_size' => $result['original_size'],
                    'compressed_size' => $result['compressed_size'],
                    'reduction' => $result['reduction'],
                    'reduction_percent' => $result['reduction_percent']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao comprimir PDF: ' . $result['error']
                ], 500);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao comprimir PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Salva o PDF editado
     */
    public function saveEditedPdf(Request $request)
    {
        $request->validate([
            'edited_pdf' => 'required|file|max:50240', // Max 50MB para PDFs editados
        ]);

        try {
            $file = $request->file('edited_pdf');
            $filename = 'edited_' . time() . '.pdf';
            $path = $file->storeAs('temp', $filename, 'public');

            return response()->json([
                'success' => true,
                'message' => 'PDF salvo com sucesso!',
                'download_url' => Storage::url($path),
                'filename' => $filename
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Limpar arquivos temporários
     */
    public function cleanupTemp()
    {
        $this->pdfService->cleanupTempFiles();
        
        return response()->json([
            'success' => true,
            'message' => 'Arquivos temporários limpos com sucesso!'
        ]);
    }

    /**
     * Formata bytes em formato legível
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
