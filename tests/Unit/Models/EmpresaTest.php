<?php

use App\Models\Category;
use App\Models\Empresa;
use App\Models\File;
use App\Models\User;

// Testes básicos do modelo
it('pode criar uma empresa', function () {
    $empresa = Empresa::factory()->create([
        'nome' => 'Empresa Teste',
        'email' => 'teste@empresa.com',
        'endereco' => 'Rua Teste, 123',
    ]);

    expect($empresa->nome)->toBe('Empresa Teste');
    expect($empresa->email)->toBe('teste@empresa.com');
    expect($empresa->endereco)->toBe('Rua Teste, 123');
    expect($empresa->logo_path)->toBeNull();
});

it('pode ter logo', function () {
    $empresa = Empresa::factory()->withLogo()->create();

    expect($empresa->logo_path)->toBe('logos/test-logo.png');
});

it('tem relacionamento com usuários', function () {
    $empresa = Empresa::factory()->create();
    $user = User::factory()->create(['empresa_id' => $empresa->id]);

    expect($empresa->users)->toHaveCount(1);
    expect($empresa->users->first()->id)->toBe($user->id);
});

it('tem relacionamento com categorias', function () {
    $empresa = Empresa::factory()->create();
    $category = Category::factory()->create(['empresa_id' => $empresa->id]);

    expect($empresa->categories)->toHaveCount(1);
    expect($empresa->categories->first()->id)->toBe($category->id);
});

it('tem relacionamento com arquivos', function () {
    $empresa = Empresa::factory()->create();
    $file = File::factory()->create(['empresa_id' => $empresa->id]);

    expect($empresa->files)->toHaveCount(1);
    expect($empresa->files->first()->id)->toBe($file->id);
});

// Testes de validação
it('requer nome', function () {
    $this->expectException(\Illuminate\Database\QueryException::class);
    Empresa::factory()->create(['nome' => '']);
});

it('requer email válido', function () {
    $this->expectException(\Illuminate\Database\QueryException::class);
    Empresa::factory()->create(['email' => 'email-inválido']);
});

it('requer email único', function () {
    Empresa::factory()->create(['email' => 'teste@empresa.com']);

    $this->expectException(\Illuminate\Database\QueryException::class);
    Empresa::factory()->create(['email' => 'teste@empresa.com']);
});

// Testes de accessors
it('retorna logo_url quando tem logo', function () {
    $empresa = Empresa::factory()->withLogo()->create();

    expect($empresa->logo_url)->toContain('empresa.logo');
    expect($empresa->logo_url)->toContain($empresa->id);
});

it('retorna null para logo_url quando não tem logo', function () {
    $empresa = Empresa::factory()->create(['logo_path' => null]);

    expect($empresa->logo_url)->toBeNull();
});

// Testes de exclusão em cascata
it('remove usuários ao ser excluída', function () {
    $empresa = Empresa::factory()->create();
    $user = User::factory()->create(['empresa_id' => $empresa->id]);

    expect(User::count())->toBe(1);

    $empresa->delete();

    expect(User::count())->toBe(0);
});

it('remove categorias ao ser excluída', function () {
    $empresa = Empresa::factory()->create();
    $category = Category::factory()->create(['empresa_id' => $empresa->id]);

    expect(Category::count())->toBe(1);

    $empresa->delete();

    expect(Category::count())->toBe(0);
});

it('remove arquivos ao ser excluída', function () {
    $empresa = Empresa::factory()->create();
    $file = File::factory()->create(['empresa_id' => $empresa->id]);

    expect(File::count())->toBe(1);

    $empresa->delete();

    expect(File::count())->toBe(0);
}); 