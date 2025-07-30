<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Empresa;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateTestTrialAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:create-trial-account {--email=} {--company-name=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Criar uma conta empresarial de teste com trial expirado';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email') ?: 'teste' . time() . '@trialexpirado.com';
        $companyName = $this->option('company-name') ?: 'Empresa Teste Trial ' . time();
        
        $this->info('Criando conta empresarial de teste com trial expirado...');
        
        try {
            // 1. Criar a empresa
            $empresa = Empresa::create([
                'nome' => $companyName,
                'email' => str_replace('@', '+empresa@', $email),
                'endereco' => 'Luanda, Angola - Teste',
                'approval_status' => 'approved',
                'approved_at' => now(),
                'trial_start_date' => now()->subDays(35), // Trial iniciado há 35 dias
                'trial_end_date' => now()->subDays(5),   // Trial expirado há 5 dias
                'has_paid' => false,
                'subscription_end_date' => null,
            ]);

            $this->info("✅ Empresa criada: ID {$empresa->id} - {$empresa->nome}");

            // 2. Criar o usuário admin da empresa
            $user = User::create([
                'name' => 'Admin ' . explode(' ', $companyName)[0],
                'email' => $email,
                'password' => Hash::make('123456'),
                'role' => 'admin',
                'empresa_id' => $empresa->id,
                'account_type' => 'company', // Definir como conta empresarial
                'approval_status' => 'approved',
                'approved_at' => now(),
                'trial_start_date' => now()->subDays(35), // Trial iniciado há 35 dias
                'trial_end_date' => now()->subDays(5),   // Trial expirado há 5 dias
                'has_paid' => false,
                'subscription_end_date' => null,
                'payment_processed_at' => null,
            ]);

            $this->info("✅ Usuário criado: ID {$user->id} - {$user->name}");

            // 3. Verificar status
            $this->info("\n📊 VERIFICAÇÃO DE STATUS:");
            $this->line("Trial expirado? Empresa: " . ($empresa->isTrialExpired() ? '✅ SIM' : '❌ NÃO'));
            $this->line("Trial expirado? Usuário: " . ($user->isTrialExpired() ? '✅ SIM' : '❌ NÃO'));
            $this->line("Conta ativa? Empresa: " . ($empresa->isAccountActive() ? '❌ SIM (erro!)' : '✅ NÃO'));
            $this->line("Conta ativa? Usuário: " . ($user->isAccountActive() ? '❌ SIM (erro!)' : '✅ NÃO'));

            // 4. Mostrar credenciais
            $this->info("\n🔑 CREDENCIAIS PARA TESTE:");
            $this->line("Email: {$email}");
            $this->line("Senha: 123456");
            $this->line("Empresa: {$companyName}");

            // 5. Instruções
            $this->info("\n📝 INSTRUÇÕES:");
            $this->line("1. Faça login com as credenciais acima");
            $this->line("2. Deve ser redirecionado para /payment-required");
            $this->line("3. Faça upload de comprovativo de pagamento");
            $this->line("4. No super admin, aprove o pagamento");
            $this->line("5. Teste se consegue acessar o dashboard");

            // 6. Comando para ativar manualmente
            $this->info("\n🛠️ COMANDO PARA ATIVAR MANUALMENTE:");
            $this->line("php artisan test:activate-account {$user->id}");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("❌ Erro ao criar conta de teste: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
