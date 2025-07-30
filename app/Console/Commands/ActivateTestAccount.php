<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Empresa;
use Illuminate\Console\Command;

class ActivateTestAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:activate-account {user_id} {--months=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ativar manualmente uma conta de teste simulando pagamento aprovado';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $months = $this->option('months');
        
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("❌ Usuário com ID {$userId} não encontrado.");
            return Command::FAILURE;
        }

        $this->info("🔄 Ativando conta do usuário: {$user->name} ({$user->email})");
        
        try {
            // 1. Limpar trial do usuário
            $user->clearTrialAfterPayment();
            
            // 2. Definir campos de pagamento
            $user->update([
                'has_paid' => true,
                'payment_processed_at' => now(),
                'subscription_end_date' => now()->addMonths($months),
                'account_status' => 'active',
            ]);
            
            $this->info("✅ Trial do usuário limpo");

            // 3. Se for usuário de empresa, limpar trial da empresa também
            if ($user->empresa) {
                $empresa = $user->empresa;
                
                $empresa->clearTrialAfterPayment();
                
                $empresa->update([
                    'has_paid' => true,
                    'payment_processed_at' => now(),
                    'subscription_end_date' => now()->addMonths($months),
                    'account_status' => 'active',
                ]);
                
                $this->info("✅ Trial da empresa limpo: {$empresa->nome}");
            }

            // 4. Verificar status final
            $user->refresh();
            if ($user->empresa) {
                $user->empresa->refresh();
            }

            $this->info("\n📊 STATUS FINAL:");
            $this->line("Trial expirado? Usuário: " . ($user->isTrialExpired() ? '❌ SIM (erro!)' : '✅ NÃO'));
            $this->line("Conta ativa? Usuário: " . ($user->isAccountActive() ? '✅ SIM' : '❌ NÃO (erro!)'));
            
            if ($user->empresa) {
                $empresa = $user->empresa;
                $this->line("Trial expirado? Empresa: " . ($empresa->isTrialExpired() ? '❌ SIM (erro!)' : '✅ NÃO'));
                $this->line("Conta ativa? Empresa: " . ($empresa->isAccountActive() ? '✅ SIM' : '❌ NÃO (erro!)'));
            }

            // 5. Verificar campos específicos
            $this->info("\n🔍 CAMPOS TRIAL DO USUÁRIO:");
            $this->line("trial_start_date: " . ($user->trial_start_date ?? 'NULL'));
            $this->line("trial_end_date: " . ($user->trial_end_date ?? 'NULL'));
            $this->line("trial_start: " . ($user->trial_start ?? 'NULL'));
            $this->line("trial_end: " . ($user->trial_end ?? 'NULL'));
            $this->line("has_paid: " . ($user->has_paid ? 'true' : 'false'));
            $this->line("subscription_end_date: " . ($user->subscription_end_date ?? 'NULL'));

            if ($user->empresa) {
                $empresa = $user->empresa;
                $this->info("\n🔍 CAMPOS TRIAL DA EMPRESA:");
                $this->line("trial_start_date: " . ($empresa->trial_start_date ?? 'NULL'));
                $this->line("trial_end_date: " . ($empresa->trial_end_date ?? 'NULL'));
                $this->line("trial_start: " . ($empresa->trial_start ?? 'NULL'));
                $this->line("trial_end: " . ($empresa->trial_end ?? 'NULL'));
                $this->line("has_paid: " . ($empresa->has_paid ? 'true' : 'false'));
                $this->line("subscription_end_date: " . ($empresa->subscription_end_date ?? 'NULL'));
            }

            $this->info("\n✅ Conta ativada com sucesso! Agora você deve conseguir acessar o dashboard.");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("❌ Erro ao ativar conta: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
