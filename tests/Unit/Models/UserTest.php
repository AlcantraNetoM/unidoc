<?php

use App\Models\Category;
use App\Models\Empresa;
use App\Models\File;
use App\Models\User;

// Testes básicos do modelo
it('pode criar um usuário', function () {
    $user = User::factory()->create([
        'name' => 'João Silva',
        'email' => 'joao@teste.com',
        'role' => 'admin',
    ]);

    expect($user->name)->toBe('João Silva');
    expect($user->email)->toBe('joao@teste.com');
    expect($user->role)->toBe('admin');
});

it('tem papel padrão como normal_technician', function () {
    $user = User::factory()->create();

    expect($user->role)->toBe('normal_technician');
});

// Testes de relacionamentos
it('pertence a uma empresa', function () {
    $empresa = Empresa::factory()->create();
    $user = User::factory()->create(['empresa_id' => $empresa->id]);

    expect($user->empresa->id)->toBe($empresa->id);
});

it('pode pertencer a uma categoria', function () {
    $category = Category::factory()->create();
    $user = User::factory()->create(['category_id' => $category->id]);

    expect($user->category->id)->toBe($category->id);
});

it('tem arquivos', function () {
    $user = User::factory()->create();
    $file = File::factory()->create(['user_id' => $user->id]);

    expect($user->files)->toHaveCount(1);
    expect($user->files->first()->id)->toBe($file->id);
});

// Testes dos métodos helper de papel
it('identifica admin corretamente', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $tecnico = User::factory()->create(['role' => 'general_technician']);

    expect($admin->isAdmin())->toBeTrue();
    expect($tecnico->isAdmin())->toBeFalse();
});

it('identifica técnico geral corretamente', function () {
    $tecnicoGeral = User::factory()->create(['role' => 'general_technician']);
    $admin = User::factory()->create(['role' => 'admin']);

    expect($tecnicoGeral->isGeneralTechnician())->toBeTrue();
    expect($admin->isGeneralTechnician())->toBeFalse();
});

it('identifica técnico normal corretamente', function () {
    $tecnicoNormal = User::factory()->create(['role' => 'normal_technician']);
    $admin = User::factory()->create(['role' => 'admin']);

    expect($tecnicoNormal->isNormalTechnician())->toBeTrue();
    expect($admin->isNormalTechnician())->toBeFalse();
});

// Testes de permissões de upload
it('admin pode fazer upload em qualquer categoria da empresa', function () {
    $empresa = Empresa::factory()->create();
    $admin = User::factory()->create([
        'role' => 'admin',
        'empresa_id' => $empresa->id,
    ]);
    $category = Category::factory()->create(['empresa_id' => $empresa->id]);

    expect($admin->canUploadToCategory($category))->toBeTrue();
});

it('admin não pode fazer upload em categoria de outra empresa', function () {
    $empresa1 = Empresa::factory()->create();
    $empresa2 = Empresa::factory()->create();
    $admin = User::factory()->create([
        'role' => 'admin',
        'empresa_id' => $empresa1->id,
    ]);
    $category = Category::factory()->create(['empresa_id' => $empresa2->id]);

    expect($admin->canUploadToCategory($category))->toBeFalse();
});

it('técnico geral pode fazer upload em qualquer categoria da empresa', function () {
    $empresa = Empresa::factory()->create();
    $tecnico = User::factory()->create([
        'role' => 'general_technician',
        'empresa_id' => $empresa->id,
    ]);
    $category = Category::factory()->create(['empresa_id' => $empresa->id]);

    expect($tecnico->canUploadToCategory($category))->toBeTrue();
});

it('técnico normal só pode fazer upload na sua categoria', function () {
    $empresa = Empresa::factory()->create();
    $category1 = Category::factory()->create(['empresa_id' => $empresa->id]);
    $category2 = Category::factory()->create(['empresa_id' => $empresa->id]);
    
    $tecnicoNormal = User::factory()->create([
        'role' => 'normal_technician',
        'empresa_id' => $empresa->id,
        'category_id' => $category1->id,
    ]);

    expect($tecnicoNormal->canUploadToCategory($category1))->toBeTrue();
    expect($tecnicoNormal->canUploadToCategory($category2))->toBeFalse();
});

it('técnico normal não pode fazer upload sem categoria atribuída', function () {
    $empresa = Empresa::factory()->create();
    $tecnicoNormal = User::factory()->create([
        'role' => 'normal_technician',
        'empresa_id' => $empresa->id,
        'category_id' => null,
    ]);
    $category = Category::factory()->create(['empresa_id' => $empresa->id]);

    expect($tecnicoNormal->canUploadToCategory($category))->toBeFalse();
});

// Testes de validação
it('requer papel válido', function () {
    $this->expectException(\Illuminate\Database\QueryException::class);
    User::factory()->create(['role' => 'papel_inexistente']);
});

it('requer email único', function () {
    User::factory()->create(['email' => 'teste@email.com']);

    $this->expectException(\Illuminate\Database\QueryException::class);
    User::factory()->create(['email' => 'teste@email.com']);
});

// Testes de segurança
it('hash da senha é criado automaticamente', function () {
    $user = User::factory()->create(['password' => 'senha123']);

    expect($user->password)->not()->toBe('senha123');
    expect(strlen($user->password))->toBeGreaterThan(50); // Hash bcrypt
});

it('senha não é visível na serialização', function () {
    $user = User::factory()->create();

    $array = $user->toArray();

    expect($array)->not()->toHaveKey('password');
    expect($array)->not()->toHaveKey('remember_token');
}); 