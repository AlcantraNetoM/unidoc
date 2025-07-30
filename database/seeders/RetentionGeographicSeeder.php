<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Payment;
use Carbon\Carbon;

class RetentionGeographicSeeder extends Seeder
{
    public function run()
    {
        // Atualizar alguns usuários existentes com dados geográficos
        $provinces = [
            'Luanda' => ['Luanda', 'Viana', 'Cacuaco'],
            'Benguela' => ['Benguela', 'Lobito', 'Catumbela'],
            'Huíla' => ['Lubango', 'Matala', 'Chibia'],
            'Cabinda' => ['Cabinda', 'Buco-Zau', 'Belize'],
            'Kwanza Sul' => ['Sumbe', 'Porto Amboim', 'Gabela'],
            'Namibe' => ['Namibe', 'Tombua', 'Bibala']
        ];

        $users = User::where('role', '!=', 'super_admin')->get();
        
        foreach ($users as $user) {
            $province = array_rand($provinces);
            $cities = $provinces[$province];
            $city = $cities[array_rand($cities)];
            
            $user->update([
                'provincia' => $province,
                'cidade' => $city,
                'pais' => 'Angola',
                'last_login_at' => now()->subDays(rand(0, 90)) // Simular últimos logins
            ]);
        }

        // Atualizar alguns pagamentos existentes com dados de retenção
        $payments = Payment::all();
        
        foreach ($payments as $index => $payment) {
            $isRenewal = $index > 10 && rand(0, 100) < 30; // 30% chance de ser renovação
            $subscriptionMonth = $isRenewal ? rand(2, 12) : 1;
            $startDate = Carbon::parse($payment->payment_date)->subMonths($subscriptionMonth - 1);
            
            $payment->update([
                'is_renewal' => $isRenewal,
                'subscription_month' => $subscriptionMonth,
                'subscription_start_date' => $startDate,
                'subscription_end_date' => $startDate->copy()->addMonth(),
                'is_churned' => rand(0, 100) < 15, // 15% de churn
                'churn_date' => rand(0, 100) < 15 ? now()->subDays(rand(1, 30)) : null
            ]);
        }

        $this->command->info('Dados de retenção e geográficos atualizados com sucesso!');
    }
}
