<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Empresa;
use App\Models\AccountNotification;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckAccountExpirations extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'accounts:check-expiration';

    /**
     * The console command description.
     */
    protected $description = 'Verifica contas que estão prestes a expirar e suspende contas expiradas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Verificando expiração de contas...');
        
        $this->checkPersonalAccounts();
        $this->checkCompanyAccounts();
        
        $this->info('Verificação concluída!');
    }
    
    /**
     * Verificar contas pessoais
     */
    private function checkPersonalAccounts()
    {
        $this->info('Verificando contas pessoais...');
        
        // Buscar usuários com subscrição ativa ou em período de teste
        $users = User::where('role', 'personal_user')
            ->whereIn('account_status', ['trial', 'active'])
            ->get();
        
        foreach ($users as $user) {
            $this->processUserAccount($user);
        }
        
        $this->info("Processadas {$users->count()} contas pessoais.");
    }
    
    /**
     * Verificar contas empresariais
     */
    private function checkCompanyAccounts()
    {
        $this->info('Verificando contas empresariais...');
        
        // Buscar empresas aprovadas
        $empresas = Empresa::where('approval_status', 'approved')
            ->whereIn('account_status', ['trial', 'active'])
            ->get();
        
        foreach ($empresas as $empresa) {
            $this->processCompanyAccount($empresa);
        }
        
        $this->info("Processadas {$empresas->count()} contas empresariais.");
    }
    
    /**
     * Processar conta de usuário
     */
    private function processUserAccount($user)
    {
        // Verificar se está em período de teste
        if ($user->account_status === 'trial' || $user->isInTrialPeriod()) {
            $daysLeft = $user->getTrialDaysRemaining();
            
            if ($daysLeft <= 0) {
                // Período de teste expirou - suspender conta se não tiver subscrição ativa
                if (!$user->isAccountActive()) {
                    $user->suspendAccount();
                    $this->warn("Conta pessoal suspensa: {$user->name} (#{$user->id}) - Período de teste expirado");
                }
            } elseif ($user->needsExpiryNotification()) {
                // Criar notificação de aviso
                AccountNotification::createExpiryWarning($user, 'user', $daysLeft);
                $user->last_notification_sent = now();
                $user->save();
                $this->info("Notificação enviada para: {$user->name} (#{$user->id}) - {$daysLeft} dias restantes");
            }
        }
        
        // Verificar subscrição ativa
        if ($user->account_status === 'active') {
            $daysLeft = $user->getDaysUntilExpiry();
            
            if ($daysLeft <= 0) {
                // Subscrição expirou - suspender conta
                $user->suspendAccount();
                $this->warn("Conta pessoal suspensa: {$user->name} (#{$user->id}) - Subscrição expirada");
            } elseif ($user->needsExpiryNotification()) {
                // Criar notificação de aviso
                AccountNotification::createExpiryWarning($user, 'user', $daysLeft);
                $user->last_notification_sent = now();
                $user->save();
                $this->info("Notificação enviada para: {$user->name} (#{$user->id}) - {$daysLeft} dias restantes");
            }
        }
    }
    
    /**
     * Processar conta de empresa
     */
    private function processCompanyAccount($empresa)
    {
        // Verificar se está em período de teste
        if ($empresa->account_status === 'trial' || $empresa->isInTrialPeriod()) {
            $daysLeft = $empresa->getTrialDaysRemaining();
            
            if ($daysLeft <= 0) {
                // Período de teste expirou - suspender conta se não tiver subscrição ativa
                if (!$empresa->isAccountActive()) {
                    $empresa->suspendAccount();
                    $this->warn("Conta empresarial suspensa: {$empresa->nome} (#{$empresa->id}) - Período de teste expirado");
                }
            } elseif ($empresa->needsExpiryNotification()) {
                // Criar notificação de aviso
                AccountNotification::createExpiryWarning($empresa, 'empresa', $daysLeft);
                $empresa->last_notification_sent = now();
                $empresa->save();
                $this->info("Notificação enviada para: {$empresa->nome} (#{$empresa->id}) - {$daysLeft} dias restantes");
            }
        }
        
        // Verificar subscrição ativa
        if ($empresa->account_status === 'active') {
            $daysLeft = $empresa->getDaysUntilExpiry();
            
            if ($daysLeft <= 0) {
                // Subscrição expirou - suspender conta
                $empresa->suspendAccount();
                $this->warn("Conta empresarial suspensa: {$empresa->nome} (#{$empresa->id}) - Subscrição expirada");
            } elseif ($empresa->needsExpiryNotification()) {
                // Criar notificação de aviso
                AccountNotification::createExpiryWarning($empresa, 'empresa', $daysLeft);
                $empresa->last_notification_sent = now();
                $empresa->save();
                $this->info("Notificação enviada para: {$empresa->nome} (#{$empresa->id}) - {$daysLeft} dias restantes");
            }
        }
    }
}
