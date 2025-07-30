<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Payment;

class FixTrialAfterPayment extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'payment:fix-trial-issues {--dry-run : Apenas mostrar o que seria corrigido sem aplicar mudanças}';

    /**
     * The console command description.
     */
    protected $description = 'Corrige problemas de trial não limpo após pagamentos aprovados';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('🔍 MODO DRY-RUN: Apenas mostrando problemas, sem aplicar correções');
        } else {
            $this->info('🔧 MODO CORREÇÃO: Aplicando correções nos problemas encontrados');
        }
        
        $this->line('');
        
        // 1. Verificar usuários com pagamentos aprovados mas trial ainda ativo
        $this->info('1. Verificando usuários com trial não limpo...');
        $this->fixUsersWithTrialIssues($dryRun);
        
        $this->line('');
        
        // 2. Verificar empresas com pagamentos aprovados mas trial ainda ativo
        $this->info('2. Verificando empresas com trial não limpo...');
        $this->fixEmpresasWithTrialIssues($dryRun);
        
        $this->line('');
        $this->info('✅ Verificação completa!');
    }
    
    private function fixUsersWithTrialIssues($dryRun)
    {
        // Buscar usuários que têm pagamentos aprovados mas ainda têm trial ativo/expirado
        $problematicUsers = User::whereHas('payments', function ($query) {
            $query->where('status', 'approved');
        })
        ->where(function ($query) {
            $query->whereNotNull('trial_start_date')
                  ->orWhereNotNull('trial_end_date')
                  ->orWhereNotNull('trial_start')
                  ->orWhereNotNull('trial_end');
        })
        ->get();
        
        if ($problematicUsers->count() === 0) {
            $this->info('   ✅ Nenhum usuário com problema encontrado');
            return;
        }
        
        $this->warn("   ⚠️  Encontrados {$problematicUsers->count()} usuários com problemas:");
        
        foreach ($problematicUsers as $user) {
            $lastPayment = $user->payments()->where('status', 'approved')->latest()->first();
            
            $this->line("   - ID: {$user->id} | Email: {$user->email}");
            $this->line("     Trial End: {$user->trial_end_date} | Último Pagamento: {$lastPayment->created_at}");
            
            if (!$dryRun) {
                $user->clearTrialAfterPayment();
                $this->info("     ✅ Corrigido!");
            }
        }
    }
    
    private function fixEmpresasWithTrialIssues($dryRun)
    {
        // Buscar empresas que têm pagamentos aprovados mas ainda têm trial ativo/expirado
        $problematicEmpresas = Empresa::whereHas('payments', function ($query) {
            $query->where('status', 'approved');
        })
        ->where(function ($query) {
            $query->whereNotNull('trial_start_date')
                  ->orWhereNotNull('trial_end_date')
                  ->orWhereNotNull('trial_start')
                  ->orWhereNotNull('trial_end');
        })
        ->get();
        
        if ($problematicEmpresas->count() === 0) {
            $this->info('   ✅ Nenhuma empresa com problema encontrada');
            return;
        }
        
        $this->warn("   ⚠️  Encontradas {$problematicEmpresas->count()} empresas com problemas:");
        
        foreach ($problematicEmpresas as $empresa) {
            $lastPayment = $empresa->payments()->where('status', 'approved')->latest()->first();
            
            $this->line("   - ID: {$empresa->id} | Nome: {$empresa->nome}");
            $this->line("     Trial End: {$empresa->trial_end_date} | Último Pagamento: {$lastPayment->created_at}");
            
            if (!$dryRun) {
                $empresa->clearTrialAfterPayment();
                $this->info("     ✅ Corrigido!");
            }
        }
    }
}
