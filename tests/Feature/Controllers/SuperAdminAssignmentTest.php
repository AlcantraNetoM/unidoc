<?php

use App\Models\Category;
use App\Models\Empresa;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// =====================================================
// TESTES DA FUNCIONALIDADE DE ATRIBUIÇÃO DE EMPRESA
// =====================================================

beforeEach(function () {
    // Criar super admin para os testes
    $this->superAdmin = User::factory()->create([
        'role' => 'super_admin',
        'name' => 'Super Admin',
        'email' => 'superadmin@test.com'
    ]);
    
    // Criar empresas de teste
    $this->empresa1 = Empresa::factory()->create([
        'nome' => 'Empresa Teste 1',
        'email' => 'empresa1@test.com'
    ]);
    
    $this->empresa2 = Empresa::factory()->create([
        'nome' => 'Empresa Teste 2', 
        'email' => 'empresa2@test.com'
    ]);
    
    // Criar categorias para as empresas
    $this->category1 = Category::factory()->create([
        'empresa_id' => $this->empresa1->id,
        'nome' => 'Categoria 1'
    ]);
    
    $this->category2 = Category::factory()->create([
        'empresa_id' => $this->empresa2->id,
        'nome' => 'Categoria 2'
    ]);
    
    // Criar usuários sem empresa (disponíveis para atribuição)
    $this->userAvailable1 = User::factory()->create([
        'name' => 'Usuário Disponível 1',
        'email' => 'user1@test.com',
        'empresa_id' => null,
        'role' => 'admin'
    ]);
    
    $this->userAvailable2 = User::factory()->create([
        'name' => 'Usuário Disponível 2',
        'email' => 'user2@test.com',
        'empresa_id' => null,
        'role' => 'general_technician'
    ]);
    
    $this->userAvailable3 = User::factory()->create([
        'name' => 'Usuário Disponível 3',
        'email' => 'user3@test.com',
        'empresa_id' => null,
        'role' => 'normal_technician'
    ]);
});

// =====================================================
// TESTES DE ACESSO À PÁGINA DE ATRIBUIÇÃO
// =====================================================

it('super admin pode acessar página de atribuição', function () {
    $response = $this->actingAs($this->superAdmin)
        ->get(route('super_admin.users.assign.page'));
    
    $response->assertStatus(200);
    $response->assertViewIs('super_admin.users.assign');
    $response->assertViewHas(['usuariosDisponiveis', 'empresas']);
});

it('usuário comum não pode acessar página de atribuição', function () {
    $user = User::factory()->create(['role' => 'admin']);
    
    $response = $this->actingAs($user)
        ->get(route('super_admin.users.assign.page'));
    
    $response->assertStatus(403);
});

it('usuário não autenticado é redirecionado para login', function () {
    $response = $this->get(route('super_admin.users.assign.page'));
    
    $response->assertRedirect(route('login'));
});

// =====================================================
// TESTES DE DADOS DA PÁGINA DE ATRIBUIÇÃO  
// =====================================================

it('página de atribuição mostra usuários disponíveis corretamente', function () {
    $response = $this->actingAs($this->superAdmin)
        ->get(route('super_admin.users.assign.page'));
    
    $response->assertStatus(200);
    
    // Verificar se os usuários disponíveis estão na view
    $usuariosDisponiveis = $response->viewData('usuariosDisponiveis');
    
    expect($usuariosDisponiveis)->toHaveCount(3);
    expect($usuariosDisponiveis->pluck('id')->toArray())
        ->toContain($this->userAvailable1->id)
        ->toContain($this->userAvailable2->id)
        ->toContain($this->userAvailable3->id);
    
    // Verificar se super admin não está na lista
    expect($usuariosDisponiveis->pluck('id')->toArray())
        ->not->toContain($this->superAdmin->id);
});

it('página de atribuição mostra empresas disponíveis', function () {
    $response = $this->actingAs($this->superAdmin)
        ->get(route('super_admin.users.assign.page'));
    
    $response->assertStatus(200);
    
    $empresas = $response->viewData('empresas');
    
    expect($empresas)->toHaveCount(2);
    expect($empresas->pluck('id')->toArray())
        ->toContain($this->empresa1->id)
        ->toContain($this->empresa2->id);
});

it('usuários já atribuídos não aparecem na lista disponível', function () {
    // Atribuir um usuário a uma empresa
    $this->userAvailable1->update(['empresa_id' => $this->empresa1->id]);
    
    $response = $this->actingAs($this->superAdmin)
        ->get(route('super_admin.users.assign.page'));
    
    $usuariosDisponiveis = $response->viewData('usuariosDisponiveis');
    
    expect($usuariosDisponiveis)->toHaveCount(2);
    expect($usuariosDisponiveis->pluck('id')->toArray())
        ->not->toContain($this->userAvailable1->id);
});

// =====================================================
// TESTES DE ATRIBUIÇÃO DE USUÁRIOS
// =====================================================

it('pode atribuir usuário como admin a uma empresa', function () {
    $response = $this->actingAs($this->superAdmin)
        ->post(route('super_admin.users.assign'), [
            'user_id' => $this->userAvailable1->id,
            'empresa_id' => $this->empresa1->id,
            'role' => 'admin'
        ]);
    
    $response->assertRedirect();
    $response->assertSessionHas('success', 'Usuário atribuído à empresa com sucesso!');
    
    // Verificar se o usuário foi atualizado no banco
    $this->userAvailable1->refresh();
    expect($this->userAvailable1->empresa_id)->toBe($this->empresa1->id);
    expect($this->userAvailable1->role)->toBe('admin');
    expect($this->userAvailable1->category_id)->toBeNull();
});

it('pode atribuir usuário como técnico geral a uma empresa', function () {
    $response = $this->actingAs($this->superAdmin)
        ->post(route('super_admin.users.assign'), [
            'user_id' => $this->userAvailable2->id,
            'empresa_id' => $this->empresa1->id,
            'role' => 'general_technician'
        ]);
    
    $response->assertRedirect();
    $response->assertSessionHas('success');
    
    $this->userAvailable2->refresh();
    expect($this->userAvailable2->empresa_id)->toBe($this->empresa1->id);
    expect($this->userAvailable2->role)->toBe('general_technician');
    expect($this->userAvailable2->category_id)->toBeNull();
});

it('pode atribuir usuário como técnico normal com categoria', function () {
    $response = $this->actingAs($this->superAdmin)
        ->post(route('super_admin.users.assign'), [
            'user_id' => $this->userAvailable3->id,
            'empresa_id' => $this->empresa1->id,
            'role' => 'normal_technician',
            'category_id' => $this->category1->id
        ]);
    
    $response->assertRedirect();
    $response->assertSessionHas('success');
    
    $this->userAvailable3->refresh();
    expect($this->userAvailable3->empresa_id)->toBe($this->empresa1->id);
    expect($this->userAvailable3->role)->toBe('normal_technician');
    expect($this->userAvailable3->category_id)->toBe($this->category1->id);
});

// =====================================================
// TESTES DE VALIDAÇÃO
// =====================================================

it('falha ao atribuir sem user_id', function () {
    $response = $this->actingAs($this->superAdmin)
        ->post(route('super_admin.users.assign'), [
            'empresa_id' => $this->empresa1->id,
            'role' => 'admin'
        ]);
    
    $response->assertSessionHasErrors(['user_id']);
});

it('falha ao atribuir sem empresa_id', function () {
    $response = $this->actingAs($this->superAdmin)
        ->post(route('super_admin.users.assign'), [
            'user_id' => $this->userAvailable1->id,
            'role' => 'admin'
        ]);
    
    $response->assertSessionHasErrors(['empresa_id']);
});

it('falha ao atribuir sem role', function () {
    $response = $this->actingAs($this->superAdmin)
        ->post(route('super_admin.users.assign'), [
            'user_id' => $this->userAvailable1->id,
            'empresa_id' => $this->empresa1->id
        ]);
    
    $response->assertSessionHasErrors(['role']);
});

it('falha ao atribuir com user_id inválido', function () {
    $response = $this->actingAs($this->superAdmin)
        ->post(route('super_admin.users.assign'), [
            'user_id' => 99999,
            'empresa_id' => $this->empresa1->id,
            'role' => 'admin'
        ]);
    
    $response->assertSessionHasErrors(['user_id']);
});

it('falha ao atribuir com empresa_id inválido', function () {
    $response = $this->actingAs($this->superAdmin)
        ->post(route('super_admin.users.assign'), [
            'user_id' => $this->userAvailable1->id,
            'empresa_id' => 99999,
            'role' => 'admin'
        ]);
    
    $response->assertSessionHasErrors(['empresa_id']);
});

it('falha ao atribuir com role inválido', function () {
    $response = $this->actingAs($this->superAdmin)
        ->post(route('super_admin.users.assign'), [
            'user_id' => $this->userAvailable1->id,
            'empresa_id' => $this->empresa1->id,
            'role' => 'invalid_role'
        ]);
    
    $response->assertSessionHasErrors(['role']);
});

// =====================================================
// TESTES DE VALIDAÇÃO DE CATEGORIA PARA TÉCNICOS NORMAIS
// =====================================================

it('falha ao atribuir técnico normal sem categoria', function () {
    $response = $this->actingAs($this->superAdmin)
        ->post(route('super_admin.users.assign'), [
            'user_id' => $this->userAvailable3->id,
            'empresa_id' => $this->empresa1->id,
            'role' => 'normal_technician'
            // category_id omitido
        ]);
    
    $response->assertSessionHasErrors(['category_id']);
});

it('falha ao atribuir técnico normal com categoria de empresa diferente', function () {
    $response = $this->actingAs($this->superAdmin)
        ->post(route('super_admin.users.assign'), [
            'user_id' => $this->userAvailable3->id,
            'empresa_id' => $this->empresa1->id, // Empresa 1
            'role' => 'normal_technician',
            'category_id' => $this->category2->id // Categoria da Empresa 2
        ]);
    
    $response->assertSessionHasErrors(['category_id']);
});

it('falha ao atribuir técnico normal com category_id inválido', function () {
    $response = $this->actingAs($this->superAdmin)
        ->post(route('super_admin.users.assign'), [
            'user_id' => $this->userAvailable3->id,
            'empresa_id' => $this->empresa1->id,
            'role' => 'normal_technician',
            'category_id' => 99999
        ]);
    
    $response->assertSessionHasErrors(['category_id']);
});

it('admin e técnico geral não precisam de categoria', function () {
    // Teste para admin
    $response1 = $this->actingAs($this->superAdmin)
        ->post(route('super_admin.users.assign'), [
            'user_id' => $this->userAvailable1->id,
            'empresa_id' => $this->empresa1->id,
            'role' => 'admin',
            'category_id' => $this->category1->id // Categoria fornecida mas não necessária
        ]);
    
    $response1->assertRedirect();
    $response1->assertSessionHas('success');
    
    $this->userAvailable1->refresh();
    expect($this->userAvailable1->category_id)->toBeNull(); // Categoria deve ser nula
    
    // Teste para técnico geral
    $response2 = $this->actingAs($this->superAdmin)
        ->post(route('super_admin.users.assign'), [
            'user_id' => $this->userAvailable2->id,
            'empresa_id' => $this->empresa1->id,
            'role' => 'general_technician',
            'category_id' => $this->category1->id // Categoria fornecida mas não necessária
        ]);
    
    $response2->assertRedirect();
    $response2->assertSessionHas('success');
    
    $this->userAvailable2->refresh();
    expect($this->userAvailable2->category_id)->toBeNull(); // Categoria deve ser nula
});

// =====================================================
// TESTES DA API DE CATEGORIAS POR EMPRESA
// =====================================================

it('pode buscar categorias por empresa via API', function () {
    $response = $this->actingAs($this->superAdmin)
        ->get(route('super_admin.categories.by_company', $this->empresa1->id));
    
    $response->assertStatus(200);
    $response->assertJson([
        [
            'id' => $this->category1->id,
            'nome' => $this->category1->nome
        ]
    ]);
});

it('retorna categorias vazias para empresa sem categorias', function () {
    $empresaSemCategorias = Empresa::factory()->create();
    
    $response = $this->actingAs($this->superAdmin)
        ->get(route('super_admin.categories.by_company', $empresaSemCategorias->id));
    
    $response->assertStatus(200);
    $response->assertJson([]);
});

it('falha ao buscar categorias com empresa_id inválido', function () {
    $response = $this->actingAs($this->superAdmin)
        ->get('/super-admin/categories/company/99999');
    
    $response->assertStatus(404);
});

// =====================================================
// TESTES DE AUTORIZAÇÃO
// =====================================================

it('usuário comum não pode atribuir usuários', function () {
    $user = User::factory()->create(['role' => 'admin']);
    
    $response = $this->actingAs($user)
        ->post(route('super_admin.users.assign'), [
            'user_id' => $this->userAvailable1->id,
            'empresa_id' => $this->empresa1->id,
            'role' => 'admin'
        ]);
    
    $response->assertStatus(403);
});

it('admin de empresa não pode atribuir usuários', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'empresa_id' => $this->empresa1->id
    ]);
    
    $response = $this->actingAs($admin)
        ->post(route('super_admin.users.assign'), [
            'user_id' => $this->userAvailable1->id,
            'empresa_id' => $this->empresa1->id,
            'role' => 'admin'
        ]);
    
    $response->assertStatus(403);
});

// =====================================================
// TESTES DE INTEGRAÇÃO COMPLETA
// =====================================================

it('processo completo de atribuição funciona corretamente', function () {
    // 1. Acessar página de atribuição
    $getResponse = $this->actingAs($this->superAdmin)
        ->get(route('super_admin.users.assign.page'));
    
    $getResponse->assertStatus(200);
    
    // 2. Verificar dados disponíveis
    $usuariosDisponiveis = $getResponse->viewData('usuariosDisponiveis');
    $empresas = $getResponse->viewData('empresas');
    
    expect($usuariosDisponiveis)->toHaveCount(3);
    expect($empresas)->toHaveCount(2);
    
    // 3. Buscar categorias da empresa via API
    $categoriesResponse = $this->actingAs($this->superAdmin)
        ->get(route('super_admin.categories.by_company', $this->empresa1->id));
    
    $categoriesResponse->assertStatus(200);
    $categories = $categoriesResponse->json();
    expect($categories)->toHaveCount(1);
    
    // 4. Atribuir usuário como técnico normal
    $assignResponse = $this->actingAs($this->superAdmin)
        ->post(route('super_admin.users.assign'), [
            'user_id' => $this->userAvailable3->id,
            'empresa_id' => $this->empresa1->id,
            'role' => 'normal_technician',
            'category_id' => $this->category1->id
        ]);
    
    $assignResponse->assertRedirect();
    $assignResponse->assertSessionHas('success');
    
    // 5. Verificar atribuição no banco
    $this->userAvailable3->refresh();
    expect($this->userAvailable3->empresa_id)->toBe($this->empresa1->id);
    expect($this->userAvailable3->role)->toBe('normal_technician');
    expect($this->userAvailable3->category_id)->toBe($this->category1->id);
    
    // 6. Verificar que usuário não aparece mais na lista disponível
    $finalGetResponse = $this->actingAs($this->superAdmin)
        ->get(route('super_admin.users.assign.page'));
    
    $usuariosFinais = $finalGetResponse->viewData('usuariosDisponiveis');
    expect($usuariosFinais)->toHaveCount(2);
    expect($usuariosFinais->pluck('id')->toArray())
        ->not->toContain($this->userAvailable3->id);
});

it('múltiplas atribuições funcionam corretamente', function () {
    // Atribuir usuário 1 como admin
    $response1 = $this->actingAs($this->superAdmin)
        ->post(route('super_admin.users.assign'), [
            'user_id' => $this->userAvailable1->id,
            'empresa_id' => $this->empresa1->id,
            'role' => 'admin'
        ]);
    
    $response1->assertSessionHas('success');
    
    // Atribuir usuário 2 como técnico geral
    $response2 = $this->actingAs($this->superAdmin)
        ->post(route('super_admin.users.assign'), [
            'user_id' => $this->userAvailable2->id,
            'empresa_id' => $this->empresa2->id,
            'role' => 'general_technician'
        ]);
    
    $response2->assertSessionHas('success');
    
    // Atribuir usuário 3 como técnico normal
    $response3 = $this->actingAs($this->superAdmin)
        ->post(route('super_admin.users.assign'), [
            'user_id' => $this->userAvailable3->id,
            'empresa_id' => $this->empresa1->id,
            'role' => 'normal_technician',
            'category_id' => $this->category1->id
        ]);
    
    $response3->assertSessionHas('success');
    
    // Verificar todas as atribuições
    $this->userAvailable1->refresh();
    $this->userAvailable2->refresh();
    $this->userAvailable3->refresh();
    
    expect($this->userAvailable1->empresa_id)->toBe($this->empresa1->id);
    expect($this->userAvailable1->role)->toBe('admin');
    
    expect($this->userAvailable2->empresa_id)->toBe($this->empresa2->id);
    expect($this->userAvailable2->role)->toBe('general_technician');
    
    expect($this->userAvailable3->empresa_id)->toBe($this->empresa1->id);
    expect($this->userAvailable3->role)->toBe('normal_technician');
    expect($this->userAvailable3->category_id)->toBe($this->category1->id);
    
    // Verificar que não há mais usuários disponíveis
    $finalResponse = $this->actingAs($this->superAdmin)
        ->get(route('super_admin.users.assign.page'));
    
    $usuariosFinais = $finalResponse->viewData('usuariosDisponiveis');
    expect($usuariosFinais)->toHaveCount(0);
});
