<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'vambert_quaresma@icloud.com'],
            [
                'name' => 'Vambert Quaresma',
                'email' => 'vambert_quaresma@icloud.com',
                'email_verified_at' => now(),
                'password' => Hash::make('narut456'),
                'role' => 'super_admin',
                'account_type' => 'company',
                'approval_status' => 'approved',
                'approved_at' => now(),
            ]
        );
    }
} 