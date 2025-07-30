<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Payment;
use App\Models\AccountNotification;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class FinancialDashboardSeeder extends Seeder
{
    /**
     * Seeder para popular dados de pagamentos para o dashboard financeiro
     * Execute: php artisan db:seed --class=FinancialDashboardSeeder
     */
    public function run(): void
    {
        $this->command->info('🚀 Iniciando criação de dados para o Dashboard Financeiro...');

        // 1. Criar empresas de exemplo
        $this->createEmpresasComPagamentos();
        
        // 2. Criar usuários pessoais com pagamentos
        $this->createUsuariosPessoaisComPagamentos();
        
        // 3. Criar pagamentos variados (aprovados, pendentes, rejeitados)
        $this->createPagamentosVariados();
        
        // 4. Criar notificações de exemplo
        $this->createNotificacoes();

        $this->command->info('✅ Dados criados com sucesso!');
        $this->command->info('📊 Agora você pode acessar o dashboard financeiro e ver todos os gráficos funcionando!');
        $this->command->info('🌐 Acesse: /super-admin/financial-dashboard');
    }

    private function createEmpresasComPagamentos()
    {
        $this->command->info('📈 Criando empresas com pagamentos...');

        $empresas = [
            [
                'nome' => 'TechSol Angola',
                'email' => 'admin@techsol.ao',
                'endereco' => 'Rua da Misericórdia, 123, Luanda',
                'account_status' => 'active',
                'subscription_end_date' => now()->addMonths(6),
                'total_months_paid' => 12,
            ],
            [
                'nome' => 'ProBusiness Lda',
                'email' => 'contato@probusiness.ao',
                'endereco' => 'Avenida 4 de Fevereiro, 456, Luanda',
                'account_status' => 'active',
                'subscription_end_date' => now()->addMonths(3),
                'total_months_paid' => 8,
            ],
            [
                'nome' => 'InovaCorp',
                'email' => 'info@inovacorp.ao',
                'endereco' => 'Rua Amílcar Cabral, 789, Benguela',
                'account_status' => 'active',
                'subscription_end_date' => now()->addMonths(2),
                'total_months_paid' => 6,
            ],
            [
                'nome' => 'DataFlow Solutions',
                'email' => 'admin@dataflow.ao',
                'endereco' => 'Rua da Liberdade, 321, Huambo',
                'account_status' => 'trial',
                'subscription_end_date' => null,
                'total_months_paid' => 0,
            ],
            [
                'nome' => 'NextGen Angola',
                'email' => 'contato@nextgen.ao',
                'endereco' => 'Avenida Agostinho Neto, 654, Lobito',
                'account_status' => 'expired',
                'subscription_end_date' => now()->subDays(15),
                'total_months_paid' => 4,
            ],
        ];

        $empresasCriadas = 0;
        foreach ($empresas as $index => $empresaData) {
            // Verificar se a empresa já existe
            $empresaExistente = Empresa::where('email', $empresaData['email'])->first();
            if ($empresaExistente) {
                $this->command->warn("Empresa {$empresaData['nome']} já existe, pulando...");
                continue;
            }

            $empresa = Empresa::create($empresaData);
            $empresasCriadas++;
            
            // Criar admin para cada empresa
            $adminExistente = User::where('email', $empresaData['email'])->first();
            if (!$adminExistente) {
                $admin = User::create([
                    'name' => "Admin {$empresa->nome}",
                    'email' => $empresaData['email'],
                    'password' => Hash::make('password'),
                    'role' => 'company_admin',
                    'account_type' => 'company',
                    'empresa_id' => $empresa->id,
                    'approval_status' => 'approved',
                    'account_status' => $empresa->account_status,
                    'subscription_end_date' => $empresa->subscription_end_date,
                    'total_months_paid' => $empresa->total_months_paid,
                ]);
            } else {
                $admin = $adminExistente;
            }

            // Verificar se já tem pagamentos
            $pagamentosExistentes = Payment::where('payment_type', 'empresa')
                ->where('entity_id', $empresa->id)
                ->count();

            if ($pagamentosExistentes == 0) {
                // Criar pagamentos históricos para empresas com status ativo
                if ($empresa->account_status === 'active') {
                    $this->createPagamentosEmpresa($empresa, $admin);
                }

                // Criar pagamento pendente para empresas expiradas
                if ($empresa->account_status === 'expired') {
                    Payment::create([
                        'payment_type' => 'empresa',
                        'entity_id' => $empresa->id,
                        'months_paid' => 3,
                        'amount' => 45000, // 3 meses x 15.000 Kz
                        'payment_proof_path' => 'payment-proofs/comprovativo_' . $empresa->id . '.pdf',
                        'status' => 'pending',
                        'payment_date' => now()->subDays(2),
                        'plan_type' => 'business',
                    ]);
                }
            }
        }

        $this->command->info("✓ " . $empresasCriadas . " empresas criadas com pagamentos");
    }

    private function createUsuariosPessoaisComPagamentos()
    {
        $this->command->info('👤 Criando usuários pessoais com pagamentos...');

        $usuarios = [
            [
                'name' => 'João Silva',
                'email' => 'joao.silva@email.com',
                'account_status' => 'active',
                'subscription_end_date' => now()->addMonths(4),
                'total_months_paid' => 8,
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria.santos@email.com',
                'account_status' => 'active',
                'subscription_end_date' => now()->addMonths(2),
                'total_months_paid' => 5,
            ],
            [
                'name' => 'Carlos Mendes',
                'email' => 'carlos.mendes@email.com',
                'account_status' => 'active',
                'subscription_end_date' => now()->addMonths(1),
                'total_months_paid' => 3,
            ],
            [
                'name' => 'Ana Costa',
                'email' => 'ana.costa@email.com',
                'account_status' => 'trial',
                'subscription_end_date' => null,
                'total_months_paid' => 0,
            ],
            [
                'name' => 'Pedro Rocha',
                'email' => 'pedro.rocha@email.com',
                'account_status' => 'expired',
                'subscription_end_date' => now()->subDays(10),
                'total_months_paid' => 2,
            ],
            [
                'name' => 'Luísa Fernandes',
                'email' => 'luisa.fernandes@email.com',
                'account_status' => 'active',
                'subscription_end_date' => now()->addMonths(5),
                'total_months_paid' => 10,
            ],
        ];

        $usuariosCriados = 0;
        $usuariosCriados = 0;
        foreach ($usuarios as $userData) {
            // Verificar se o usuário já existe
            $userExistente = User::where('email', $userData['email'])->first();
            if ($userExistente) {
                $this->command->warn("Usuário {$userData['name']} já existe, pulando...");
                continue;
            }

            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password'),
                'role' => 'personal_user',
                'account_type' => 'personal',
                'approval_status' => 'approved',
                'account_status' => $userData['account_status'],
                'subscription_end_date' => $userData['subscription_end_date'],
                'total_months_paid' => $userData['total_months_paid'],
            ]);
            $usuariosCriados++;

            // Verificar se já tem pagamentos
            $pagamentosExistentes = Payment::where('payment_type', 'user')
                ->where('entity_id', $user->id)
                ->count();

            if ($pagamentosExistentes == 0) {
                // Criar pagamentos históricos para usuários ativos
                if ($user->account_status === 'active') {
                    $this->createPagamentosUsuario($user);
                }

                // Criar pagamento pendente para usuários expirados
                if ($user->account_status === 'expired') {
                    Payment::create([
                        'payment_type' => 'user',
                        'entity_id' => $user->id,
                        'months_paid' => 2,
                        'amount' => 10000, // 2 meses x 5.000 Kz
                        'payment_proof_path' => 'payment-proofs/comprovativo_' . $user->id . '.jpg',
                        'status' => 'pending',
                        'payment_date' => now()->subDays(1),
                        'plan_type' => 'personal',
                    ]);
                }
            }
        }

        $this->command->info("✓ " . $usuariosCriados . " usuários pessoais criados com pagamentos");
    }

    private function createPagamentosEmpresa($empresa, $admin)
    {
        // Criar histórico de pagamentos nos últimos 12 meses
        $mesesPagos = $empresa->total_months_paid;
        $dataInicio = now()->subMonths($mesesPagos);

        for ($i = 0; $i < $mesesPagos; $i++) {
            $dataPagamento = $dataInicio->copy()->addMonths($i);
            
            Payment::create([
                'payment_type' => 'empresa',
                'entity_id' => $empresa->id,
                'months_paid' => rand(1, 3), // Varia entre 1-3 meses por pagamento
                'amount' => rand(1, 3) * 15000, // 15.000 Kz por mês
                'payment_proof_path' => 'payment-proofs/empresa_' . $empresa->id . '_' . $i . '.pdf',
                'status' => 'approved',
                'payment_date' => $dataPagamento,
                'approved_at' => $dataPagamento->copy()->addHours(rand(1, 24)),
                'approved_by' => 1, // Assumindo que existe um super admin com ID 1
                'plan_type' => 'business',
                'notes' => 'Pagamento aprovado automaticamente - dados de exemplo',
            ]);
        }
    }

    private function createPagamentosUsuario($user)
    {
        // Criar histórico de pagamentos nos últimos meses
        $mesesPagos = $user->total_months_paid;
        $dataInicio = now()->subMonths($mesesPagos);

        for ($i = 0; $i < $mesesPagos; $i++) {
            $dataPagamento = $dataInicio->copy()->addMonths($i);
            
            Payment::create([
                'payment_type' => 'user',
                'entity_id' => $user->id,
                'months_paid' => rand(1, 2), // Varia entre 1-2 meses por pagamento
                'amount' => rand(1, 2) * 5000, // 5.000 Kz por mês
                'payment_proof_path' => 'payment-proofs/user_' . $user->id . '_' . $i . '.jpg',
                'status' => 'approved',
                'payment_date' => $dataPagamento,
                'approved_at' => $dataPagamento->copy()->addHours(rand(1, 12)),
                'approved_by' => 1, // Assumindo que existe um super admin com ID 1
                'plan_type' => 'personal',
                'notes' => 'Pagamento aprovado automaticamente - dados de exemplo',
            ]);
        }
    }

    private function createPagamentosVariados()
    {
        $this->command->info('💳 Criando pagamentos variados (aprovados, pendentes, rejeitados)...');

        // Pagamentos pendentes adicionais
        for ($i = 0; $i < 5; $i++) {
            $isEmpresa = rand(0, 1);
            
            if ($isEmpresa) {
                $empresa = Empresa::inRandomOrder()->first();
                if ($empresa) {
                    Payment::create([
                        'payment_type' => 'empresa',
                        'entity_id' => $empresa->id,
                        'months_paid' => rand(1, 6),
                        'amount' => rand(1, 6) * 15000,
                        'payment_proof_path' => 'payment-proofs/pending_empresa_' . $i . '.pdf',
                        'status' => 'pending',
                        'payment_date' => now()->subDays(rand(1, 7)),
                        'plan_type' => 'business',
                    ]);
                }
            } else {
                $user = User::where('role', 'personal_user')->inRandomOrder()->first();
                if ($user) {
                    Payment::create([
                        'payment_type' => 'user',
                        'entity_id' => $user->id,
                        'months_paid' => rand(1, 4),
                        'amount' => rand(1, 4) * 5000,
                        'payment_proof_path' => 'payment-proofs/pending_user_' . $i . '.jpg',
                        'status' => 'pending',
                        'payment_date' => now()->subDays(rand(1, 5)),
                        'plan_type' => 'personal',
                    ]);
                }
            }
        }

        // Pagamentos rejeitados
        for ($i = 0; $i < 3; $i++) {
            $user = User::where('role', 'personal_user')->inRandomOrder()->first();
            if ($user) {
                Payment::create([
                    'payment_type' => 'user',
                    'entity_id' => $user->id,
                    'months_paid' => rand(1, 3),
                    'amount' => rand(1, 3) * 5000,
                    'payment_proof_path' => 'payment-proofs/rejected_' . $i . '.jpg',
                    'status' => 'rejected',
                    'payment_date' => now()->subDays(rand(5, 15)),
                    'approved_at' => now()->subDays(rand(3, 10)),
                    'approved_by' => 1,
                    'plan_type' => 'personal',
                    'notes' => 'Comprovativo inválido ou ilegível',
                ]);
            }
        }

        $this->command->info('✓ Pagamentos variados criados');
    }

    private function createNotificacoes()
    {
        $this->command->info('🔔 Criando notificações de exemplo...');

        // Notificações para empresas
        $empresas = Empresa::limit(3)->get();
        foreach ($empresas as $empresa) {
            AccountNotification::create([
                'notifiable_type' => 'empresa',
                'notifiable_id' => $empresa->id,
                'type' => 'payment_approved',
                'message' => 'Seu pagamento foi aprovado e sua conta foi estendida.',
                'is_read' => rand(0, 1),
                'sent_at' => now()->subDays(rand(1, 10)),
            ]);
        }

        // Notificações para usuários
        $users = User::where('role', 'personal_user')->limit(3)->get();
        foreach ($users as $user) {
            AccountNotification::create([
                'notifiable_type' => 'user',
                'notifiable_id' => $user->id,
                'type' => 'expiry_warning',
                'message' => 'Sua subscrição expira em breve. Renove para manter o acesso.',
                'is_read' => rand(0, 1),
                'sent_at' => now()->subDays(rand(1, 5)),
            ]);
        }

        $this->command->info('✓ Notificações criadas');
    }
}
