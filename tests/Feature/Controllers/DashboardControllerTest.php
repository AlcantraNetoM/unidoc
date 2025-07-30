<?php

use App\Models\Category;
use App\Models\Empresa;
use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\DB;

// =====================================================
// TESTES DE DASHBOARD DO ADMINISTRADOR
// =====================================================

it('admin vê dashboard administrativo com estatísticas completas', function () {
    $empresa = Empresa::factory()->create();
    $admin = User::factory()->create([
        'role' => 'admin',
        'empresa_id' => $empresa->id,
    ]);

    // Criar dados para estatísticas
    User::factory()->count(5)->create(['empresa_id' => $empresa->id]);
    $categories = Category::factory()->count(3)->create(['empresa_id' => $empresa->id]);
    File::factory()->count(10)->create(['empresa_id' => $empresa->id]);
    File::factory()->count(3)->create([
        'empresa_id' => $empresa->id,
        'created_at' => now(),
    ]);

    $response = $this->actingAs($admin)->get(route('dashboard'));

    $response->assertOk();
    $response->assertViewIs('dashboard.admin');
    
    // Verificar se as estatísticas estão corretas
    $stats = $response->viewData('stats');
    expect($stats['total_usuarios'])->toBe(6); // 5 + admin
    expect($stats['total_categorias'])->toBe(3);
    expect($stats['total_arquivos'])->toBe(13);
    expect($stats['arquivos_hoje'])->toBe(3);
});

it('admin vê arquivos recentes de toda a empresa', function () {
    $empresa = Empresa::factory()->create();
    $admin = User::factory()->create([
        'role' => 'admin',
        'empresa_id' => $empresa->id,
    ]);

    $arquivos = File::factory()->count(5)->create(['empresa_id' => $empresa->id]);

    $response = $this->actingAs($admin)->get(route('dashboard'));

    $response->assertOk();
    $arquivosRecentes = $response->viewData('arquivos_recentes');
    expect($arquivosRecentes)->toHaveCount(5);
});

it('admin vê categorias da empresa', function () {
    $empresa = Empresa::factory()->create();
    $admin = User::factory()->create([
        'role' => 'admin',
        'empresa_id' => $empresa->id,
    ]);

    $categories = Category::factory()->count(4)->create(['empresa_id' => $empresa->id]);

    $response = $this->actingAs($admin)->get(route('dashboard'));

    $response->assertOk();
    $categorias = $response->viewData('categorias');
    expect($categorias)->toHaveCount(4);
});

// =====================================================
// TESTES DE DASHBOARD DO TÉCNICO GERAL
// =====================================================

it('técnico geral vê dashboard com seus arquivos e categorias disponíveis', function () {
    $empresa = Empresa::factory()->create();
    $tecnicoGeral = User::factory()->create([
        'role' => 'general_technician',
        'empresa_id' => $empresa->id,
    ]);

    // Criar arquivos do técnico e de outros usuários
    File::factory()->count(3)->create([
        'user_id' => $tecnicoGeral->id,
        'empresa_id' => $empresa->id,
    ]);
    File::factory()->count(2)->create([
        'user_id' => $tecnicoGeral->id,
        'empresa_id' => $empresa->id,
        'created_at' => now(),
    ]);
    File::factory()->count(5)->create(['empresa_id' => $empresa->id]); // Outros usuários

    $categories = Category::factory()->count(3)->create(['empresa_id' => $empresa->id]);

    $response = $this->actingAs($tecnicoGeral)->get(route('dashboard'));

    $response->assertOk();
    $response->assertViewIs('dashboard.general-technician');
    
    $stats = $response->viewData('stats');
    expect($stats['meus_arquivos'])->toBe(5);
    expect($stats['arquivos_hoje'])->toBe(2);
    expect($stats['categorias_disponiveis'])->toBe(3);
});

it('técnico geral vê apenas seus próprios arquivos no dashboard', function () {
    $empresa = Empresa::factory()->create();
    $tecnicoGeral = User::factory()->create([
        'role' => 'general_technician',
        'empresa_id' => $empresa->id,
    ]);
    $outroUsuario = User::factory()->create(['empresa_id' => $empresa->id]);

    // Arquivos do técnico geral
    File::factory()->count(3)->create([
        'user_id' => $tecnicoGeral->id,
        'empresa_id' => $empresa->id,
    ]);
    
    // Arquivos de outro usuário
    File::factory()->count(2)->create([
        'user_id' => $outroUsuario->id,
        'empresa_id' => $empresa->id,
    ]);

    $response = $this->actingAs($tecnicoGeral)->get(route('dashboard'));

    $response->assertOk();
    $meusArquivos = $response->viewData('meus_arquivos');
    expect($meusArquivos)->toHaveCount(3);
    
    // Verificar se todos os arquivos pertencem ao usuário
    foreach ($meusArquivos as $arquivo) {
        expect($arquivo->user_id)->toBe($tecnicoGeral->id);
    }
});

// =====================================================
// TESTES DE DASHBOARD DO TÉCNICO NORMAL
// =====================================================

it('técnico normal vê dashboard limitado à sua categoria', function () {
    $empresa = Empresa::factory()->create();
    $category = Category::factory()->create(['empresa_id' => $empresa->id]);
    $tecnicoNormal = User::factory()->create([
        'role' => 'normal_technician',
        'empresa_id' => $empresa->id,
        'category_id' => $category->id,
    ]);

    File::factory()->count(4)->create([
        'user_id' => $tecnicoNormal->id,
        'empresa_id' => $empresa->id,
        'category_id' => $category->id,
    ]);
    
    File::factory()->count(1)->create([
        'user_id' => $tecnicoNormal->id,
        'empresa_id' => $empresa->id,
        'category_id' => $category->id,
        'created_at' => now(),
    ]);

    $response = $this->actingAs($tecnicoNormal)->get(route('dashboard'));

    $response->assertOk();
    $response->assertViewIs('dashboard.normal-technician');
    
    $stats = $response->viewData('stats');
    expect($stats['meus_arquivos'])->toBe(5);
    expect($stats['arquivos_hoje'])->toBe(1);
    expect($stats['categoria_nome'])->toBe($category->nome);
});

it('técnico normal sem categoria vê dashboard de sem categoria', function () {
    $empresa = Empresa::factory()->create();
    $tecnicoNormal = User::factory()->create([
        'role' => 'normal_technician',
        'empresa_id' => $empresa->id,
        'category_id' => null,
    ]);

    $response = $this->actingAs($tecnicoNormal)->get(route('dashboard'));

    $response->assertOk();
    $response->assertViewIs('dashboard.no-category');
    
    $user = $response->viewData('user');
    expect($user->id)->toBe($tecnicoNormal->id);
});

it('técnico normal vê subcategorias da sua categoria', function () {
    $empresa = Empresa::factory()->create();
    $category = Category::factory()->create(['empresa_id' => $empresa->id]);
    $tecnicoNormal = User::factory()->create([
        'role' => 'normal_technician',
        'empresa_id' => $empresa->id,
        'category_id' => $category->id,
    ]);

    $subcategories = \App\Models\Subcategory::factory()->count(3)->create([
        'category_id' => $category->id,
    ]);

    $response = $this->actingAs($tecnicoNormal)->get(route('dashboard'));

    $response->assertOk();
    $subcategorias = $response->viewData('subcategorias');
    expect($subcategorias)->toHaveCount(3);
    
    $stats = $response->viewData('stats');
    expect($stats['subcategorias_disponiveis'])->toBe(3);
});

// =====================================================
// TESTES DE REDIRECIONAMENTO BASEADO EM PAPEL
// =====================================================

it('redireciona para dashboard correto baseado no papel do usuário', function () {
    $empresa = Empresa::factory()->create();
    
    // Teste para cada papel
    $roles = [
        'admin' => 'dashboard.admin',
        'general_technician' => 'dashboard.general-technician',
        'normal_technician' => 'dashboard.normal-technician'
    ];

    foreach ($roles as $role => $expectedView) {
        $user = User::factory()->create([
            'role' => $role,
            'empresa_id' => $empresa->id,
            'category_id' => $role === 'normal_technician' ? Category::factory()->create(['empresa_id' => $empresa->id])->id : null,
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertViewIs($expectedView);
    }
});

it('rejeita papel inválido com 403', function () {
    $empresa = Empresa::factory()->create();
    $user = User::factory()->create([
        'role' => 'papel_inexistente',
        'empresa_id' => $empresa->id,
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertForbidden();
});

// =====================================================
// TESTES DE AUTENTICAÇÃO
// =====================================================

it('requer autenticação para acessar dashboard', function () {
    $response = $this->get(route('dashboard'));

    $response->assertRedirect(route('login'));
});

it('usuário logado tem acesso ao dashboard', function () {
    $user = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk();
});

// =====================================================
// TESTES DE PERFORMANCE E OTIMIZAÇÃO
// =====================================================

it('dashboard carrega com eager loading adequado', function () {
    $empresa = Empresa::factory()->create();
    $admin = User::factory()->create([
        'role' => 'admin',
        'empresa_id' => $empresa->id,
    ]);

    // Criar dados relacionados
    $categories = Category::factory()->count(3)->create(['empresa_id' => $empresa->id]);
    foreach ($categories as $category) {
        \App\Models\Subcategory::factory()->count(2)->create(['category_id' => $category->id]);
        File::factory()->count(5)->create([
            'empresa_id' => $empresa->id,
            'category_id' => $category->id,
        ]);
    }

    $response = $this->actingAs($admin)->get(route('dashboard'));

    $response->assertOk();
    
    // Verificar que a view correta é carregada
    $response->assertViewIs('dashboard.admin');
    
    // Verificar que os dados são carregados corretamente
    $stats = $response->viewData('stats');
    expect($stats)->toBeArray();
    expect($stats)->toHaveKey('total_usuarios');
}); 