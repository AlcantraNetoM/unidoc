<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\FinancialDashboardSeeder;

class PopulateDashboard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:populate {--fresh : Reset database before seeding}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Popular o banco de dados com dados de exemplo para o dashboard financeiro';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Populando Dashboard Financeiro...');
        
        if ($this->option('fresh')) {
            $this->warn('⚠️  ATENÇÃO: Isso irá APAGAR todos os dados existentes!');
            if ($this->confirm('Tem certeza que deseja continuar?')) {
                $this->info('🔄 Resetando banco de dados...');
                $this->call('migrate:fresh');
                $this->call('db:seed', ['--class' => 'SuperAdminSeeder']);
            } else {
                $this->info('❌ Operação cancelada.');
                return;
            }
        }

        $this->info('📊 Criando dados de exemplo...');
        $this->call('db:seed', ['--class' => 'FinancialDashboardSeeder']);
        
        $this->newLine();
        $this->info('✅ Dashboard populado com sucesso!');
        $this->info('🌐 Acesse: /super-admin/financial-dashboard');
        
        // Mostrar estatísticas
        $this->newLine();
        $this->info('📈 Resumo dos dados criados:');
        
        try {
            $stats = [
                'Pagamentos' => \App\Models\Payment::count(),
                'Aprovados' => \App\Models\Payment::where('status', 'approved')->count(),
                'Pendentes' => \App\Models\Payment::where('status', 'pending')->count(),
                'Empresas' => \App\Models\Empresa::count(),
                'Usuários Pessoais' => \App\Models\User::where('role', 'personal_user')->count(),
            ];
            
            foreach ($stats as $label => $count) {
                $this->line("   • $label: <info>$count</info>");
            }
            
            $revenue = \App\Models\Payment::where('status', 'approved')->sum('amount');
            $this->line("   • Receita Total: <info>" . number_format($revenue, 0, ',', '.') . " Kz</info>");
            
        } catch (\Exception $e) {
            $this->warn('Não foi possível obter estatísticas detalhadas.');
        }
        
        $this->newLine();
        $this->comment('💡 Dica: Use --fresh para resetar completamente o banco antes de popular');
    }
}
