<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PdfService;

class CleanupTempFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tools:cleanup-temp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpar arquivos temporários das ferramentas PDF';

    protected $pdfService;

    public function __construct(PdfService $pdfService)
    {
        parent::__construct();
        $this->pdfService = $pdfService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando limpeza de arquivos temporários...');
        
        try {
            $this->pdfService->cleanupTempFiles();
            
            // Limpar também o diretório público temp
            $publicTempDir = public_path('temp');
            if (is_dir($publicTempDir)) {
                $files = glob($publicTempDir . '/*');
                $cleaned = 0;
                
                foreach ($files as $file) {
                    if (is_file($file) && (time() - filemtime($file)) > 3600) { // 1 hora
                        unlink($file);
                        $cleaned++;
                    }
                }
                
                $this->info("Removidos {$cleaned} arquivos temporários públicos.");
            }
            
            $this->info('Limpeza concluída com sucesso!');
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Erro durante a limpeza: ' . $e->getMessage());
            return 1;
        }
    }
}
