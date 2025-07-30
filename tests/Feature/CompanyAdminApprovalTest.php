<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Empresa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyAdminApprovalTest extends TestCase
{
    use RefreshDatabase;
    
    protected $empresa;
    protected $companyAdmin;
    protected $pendingUser;
    protected $outraEmpresa;
    protected $pendingUserOutraEmpresa;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Criar empresa
        $this->empresa = Empresa::factory()->create([
            'nome' => 'Empresa Teste',
            'email' => 'empresa@test.com',
            'approval_status' => 'approved'
        ]);
        
        // Criar company admin
        $this->companyAdmin = User::factory()->create([
            'name' => 'Company Admin',
            'email' => 'admin@test.com',
            'role' => 'admin', // Changed from company_admin to admin as per role middleware
            'empresa_id' => $this->empresa->id,
            'approval_status' => 'approved'
        ]);
        
        // Criar usuário pendente para a mesma empresa
        $this->pendingUser = User::factory()->create([
            'name' => 'Usuário Pendente',
            'email' => 'pending@test.com',
            'role' => 'general_technician',
            'empresa_id' => $this->empresa->id,
            'approval_status' => 'pending'
        ]);
        
        // Criar usuário pendente para outra empresa
        $this->outraEmpresa = Empresa::factory()->create([
            'nome' => 'Outra Empresa',
            'email' => 'outra@test.com',
            'approval_status' => 'approved'
        ]);
        
        $this->pendingUserOutraEmpresa = User::factory()->create([
            'name' => 'Usuário Outra Empresa',
            'email' => 'pending-outra@test.com',
            'role' => 'general_technician',
            'empresa_id' => $this->outraEmpresa->id,
            'approval_status' => 'pending'
        ]);
    }

    public function test_company_admin_pode_visualizar_usuarios_pendentes_da_sua_empresa()
    {
        $response = $this->actingAs($this->companyAdmin)
            ->get(route('empresa.users'));
        
        $response->assertStatus(200);
        
        // Verificar se a view contém os dados dos usuários pendentes
        $response->assertViewHas('usuariosPendentes');
        $usuariosPendentes = $response->viewData('usuariosPendentes');
        
        // Deve conter o usuário pendente da mesma empresa
        $this->assertTrue($usuariosPendentes->pluck('id')->contains($this->pendingUser->id));
        
        // Não deve conter usuário de outra empresa
        $this->assertFalse($usuariosPendentes->pluck('id')->contains($this->pendingUserOutraEmpresa->id));
    }

    public function test_company_admin_pode_aprovar_usuario_da_sua_empresa()
    {
        $response = $this->actingAs($this->companyAdmin)
            ->post(route('empresa.users.approve', $this->pendingUser->id));
        
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Usuário aprovado com sucesso!');
        
        // Verificar se o usuário foi aprovado no banco
        $this->pendingUser->refresh();
        $this->assertEquals('approved', $this->pendingUser->approval_status);
        $this->assertEquals($this->companyAdmin->id, $this->pendingUser->approved_by);
        $this->assertNotNull($this->pendingUser->approved_at);
    }

    public function test_company_admin_pode_rejeitar_usuario_da_sua_empresa()
    {
        $response = $this->actingAs($this->companyAdmin)
            ->post(route('empresa.users.reject', $this->pendingUser->id));
        
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Usuário rejeitado.');
        
        // Verificar se o usuário foi rejeitado no banco
        $this->pendingUser->refresh();
        $this->assertEquals('rejected', $this->pendingUser->approval_status);
        $this->assertEquals($this->companyAdmin->id, $this->pendingUser->approved_by);
        $this->assertNotNull($this->pendingUser->approved_at);
    }

    public function test_company_admin_nao_pode_aprovar_usuario_de_outra_empresa()
    {
        $response = $this->actingAs($this->companyAdmin)
            ->post(route('empresa.users.approve', $this->pendingUserOutraEmpresa->id));
        
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Você só pode aprovar usuários da sua empresa.');
        
        // Verificar se o usuário não foi alterado
        $this->pendingUserOutraEmpresa->refresh();
        $this->assertEquals('pending', $this->pendingUserOutraEmpresa->approval_status);
    }

    public function test_company_admin_nao_pode_rejeitar_usuario_de_outra_empresa()
    {
        $response = $this->actingAs($this->companyAdmin)
            ->post(route('empresa.users.reject', $this->pendingUserOutraEmpresa->id));
        
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Você só pode rejeitar usuários da sua empresa.');
        
        // Verificar se o usuário não foi alterado
        $this->pendingUserOutraEmpresa->refresh();
        $this->assertEquals('pending', $this->pendingUserOutraEmpresa->approval_status);
    }

    public function test_company_admin_nao_pode_processar_usuario_ja_aprovado()
    {
        // Primeiro aprovar o usuário
        $this->pendingUser->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $this->companyAdmin->id
        ]);
        
        // Tentar aprovar novamente
        $response = $this->actingAs($this->companyAdmin)
            ->post(route('empresa.users.approve', $this->pendingUser->id));
        
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Este usuário já foi processado.');
    }

    public function test_company_admin_nao_pode_processar_usuario_ja_rejeitado()
    {
        // Primeiro rejeitar o usuário
        $this->pendingUser->update([
            'approval_status' => 'rejected',
            'approved_at' => now(),
            'approved_by' => $this->companyAdmin->id
        ]);
        
        // Tentar rejeitar novamente
        $response = $this->actingAs($this->companyAdmin)
            ->post(route('empresa.users.reject', $this->pendingUser->id));
        
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Este usuário já foi processado.');
    }

    public function test_usuario_comum_nao_pode_acessar_rotas_de_aprovacao()
    {
        $user = User::factory()->create([
            'role' => 'general_technician',
            'empresa_id' => $this->empresa->id,
            'approval_status' => 'approved'
        ]);
        
        $response = $this->actingAs($user)
            ->post(route('empresa.users.approve', $this->pendingUser->id));
        
        $response->assertStatus(403);
    }

    public function test_lista_principal_de_usuarios_mostra_apenas_usuarios_aprovados()
    {
        $response = $this->actingAs($this->companyAdmin)
            ->get(route('empresa.users'));
        
        $response->assertStatus(200);
        
        $users = $response->viewData('users');
        
        // Verificar se todos os usuários na lista principal estão aprovados
        foreach ($users as $user) {
            $this->assertEquals('approved', $user->approval_status);
        }
        
        // Verificar se o usuário pendente não está na lista principal
        $userIds = $users->pluck('id')->toArray();
        $this->assertNotContains($this->pendingUser->id, $userIds);
    }
}
