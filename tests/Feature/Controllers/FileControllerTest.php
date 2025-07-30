<?php

use App\Models\Category;
use App\Models\Empresa;
use App\Models\File;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    // Configurar storage fake para os testes
    Storage::fake('private');
});

// =====================================================
// TESTES DE LISTAGEM DE ARQUIVOS
// =====================================================

it('admin pode ver todos os arquivos da empresa', function () {
    $empresa = Empresa::factory()->create();
    $admin = User::factory()->create(['role' => 'admin', 'empresa_id' => $empresa->id]);
    
    File::factory()->count(3)->create(['empresa_id' => $empresa->id]);
    File::factory()->count(2)->create(); // Arquivos de outras empresas

    $response = $this->actingAs($admin)->get(route('files.index'));

    $response->assertOk();
    expect($response->viewData('files'))->toHaveCount(3);
});

it('técnico geral vê todos os arquivos da empresa', function () {
    $empresa = Empresa::factory()->create();
    $tecnico = User::factory()->create(['role' => 'general_technician', 'empresa_id' => $empresa->id]);
    
    File::factory()->count(5)->create(['empresa_id' => $empresa->id]);

    $response = $this->actingAs($tecnico)->get(route('files.index'));

    $response->assertOk();
    expect($response->viewData('files'))->toHaveCount(5);
});

it('técnico normal vê apenas arquivos da sua categoria', function () {
    $empresa = Empresa::factory()->create();
    $category1 = Category::factory()->create(['empresa_id' => $empresa->id]);
    $category2 = Category::factory()->create(['empresa_id' => $empresa->id]);
    
    $tecnicoNormal = User::factory()->create([
        'role' => 'normal_technician',
        'empresa_id' => $empresa->id,
        'category_id' => $category1->id,
    ]);

    File::factory()->count(2)->create(['empresa_id' => $empresa->id, 'category_id' => $category1->id]);
    File::factory()->count(3)->create(['empresa_id' => $empresa->id, 'category_id' => $category2->id]);

    $response = $this->actingAs($tecnicoNormal)->get(route('files.index'));

    $response->assertOk();
    expect($response->viewData('files'))->toHaveCount(2);
});

// =====================================================
// TESTES DE UPLOAD DE ARQUIVOS
// =====================================================

it('admin pode fazer upload de arquivo', function () {
    $empresa = Empresa::factory()->create();
    $admin = User::factory()->create(['role' => 'admin', 'empresa_id' => $empresa->id]);
    $category = Category::factory()->create(['empresa_id' => $empresa->id]);

    $file = UploadedFile::fake()->create('documento.pdf', 1024);

    $response = $this->actingAs($admin)->post(route('files.store'), [
        'titulo' => 'Documento de Teste',
        'descricao' => 'Descrição do documento',
        'category_id' => $category->id,
        'file' => $file,
    ]);

    $response->assertRedirect(route('files.index'));
    $this->assertDatabaseHas('files', [
        'titulo' => 'Documento de Teste',
        'user_id' => $admin->id,
        'empresa_id' => $empresa->id,
        'category_id' => $category->id,
    ]);
});

it('técnico geral pode fazer upload para qualquer categoria da empresa', function () {
    $empresa = Empresa::factory()->create();
    $tecnico = User::factory()->create(['role' => 'general_technician', 'empresa_id' => $empresa->id]);
    $category = Category::factory()->create(['empresa_id' => $empresa->id]);

    $file = UploadedFile::fake()->create('planilha.xlsx', 512);

    $response = $this->actingAs($tecnico)->post(route('files.store'), [
        'titulo' => 'Planilha de Dados',
        'category_id' => $category->id,
        'file' => $file,
    ]);

    $response->assertRedirect(route('files.index'));
    $this->assertDatabaseHas('files', [
        'titulo' => 'Planilha de Dados',
        'user_id' => $tecnico->id,
        'category_id' => $category->id,
    ]);
});

it('técnico normal pode fazer upload apenas na sua categoria', function () {
    $empresa = Empresa::factory()->create();
    $category = Category::factory()->create(['empresa_id' => $empresa->id]);
    $tecnicoNormal = User::factory()->create([
        'role' => 'normal_technician',
        'empresa_id' => $empresa->id,
        'category_id' => $category->id,
    ]);

    $file = UploadedFile::fake()->create('relatorio.pdf', 256);

    $response = $this->actingAs($tecnicoNormal)->post(route('files.store'), [
        'titulo' => 'Relatório Mensal',
        'category_id' => $category->id,
        'file' => $file,
    ]);

    $response->assertRedirect(route('files.index'));
    $this->assertDatabaseHas('files', [
        'titulo' => 'Relatório Mensal',
        'user_id' => $tecnicoNormal->id,
        'category_id' => $category->id,
    ]);
});

it('técnico normal não pode fazer upload em categoria diferente', function () {
    $empresa = Empresa::factory()->create();
    $category1 = Category::factory()->create(['empresa_id' => $empresa->id]);
    $category2 = Category::factory()->create(['empresa_id' => $empresa->id]);
    
    $tecnicoNormal = User::factory()->create([
        'role' => 'normal_technician',
        'empresa_id' => $empresa->id,
        'category_id' => $category1->id,
    ]);

    $file = UploadedFile::fake()->create('documento.pdf', 256);

    $response = $this->actingAs($tecnicoNormal)->post(route('files.store'), [
        'titulo' => 'Documento Proibido',
        'category_id' => $category2->id, // Categoria diferente da atribuída
        'file' => $file,
    ]);

    $response->assertForbidden();
    $this->assertDatabaseMissing('files', [
        'titulo' => 'Documento Proibido',
    ]);
});

it('valida tipos de arquivo permitidos', function () {
    $empresa = Empresa::factory()->create();
    $admin = User::factory()->create(['role' => 'admin', 'empresa_id' => $empresa->id]);
    $category = Category::factory()->create(['empresa_id' => $empresa->id]);

    // Arquivo não permitido
    $file = UploadedFile::fake()->create('virus.exe', 100);

    $response = $this->actingAs($admin)->post(route('files.store'), [
        'titulo' => 'Arquivo Malicioso',
        'category_id' => $category->id,
        'file' => $file,
    ]);

    $response->assertSessionHasErrors('file');
});

it('valida tamanho máximo de arquivo', function () {
    $empresa = Empresa::factory()->create();
    $admin = User::factory()->create(['role' => 'admin', 'empresa_id' => $empresa->id]);
    $category = Category::factory()->create(['empresa_id' => $empresa->id]);

    // Arquivo muito grande (11MB)
    $file = UploadedFile::fake()->create('arquivo_grande.pdf', 11264);

    $response = $this->actingAs($admin)->post(route('files.store'), [
        'titulo' => 'Arquivo Muito Grande',
        'category_id' => $category->id,
        'file' => $file,
    ]);

    $response->assertSessionHasErrors('file');
});

// =====================================================
// TESTES DE VISUALIZAÇÃO DE ARQUIVOS
// =====================================================

it('admin pode ver qualquer arquivo da empresa', function () {
    $empresa = Empresa::factory()->create();
    $admin = User::factory()->create(['role' => 'admin', 'empresa_id' => $empresa->id]);
    $file = File::factory()->create(['empresa_id' => $empresa->id]);

    $response = $this->actingAs($admin)->get(route('files.show', $file));

    $response->assertOk();
});

it('usuário não pode ver arquivo de outra empresa', function () {
    $empresa1 = Empresa::factory()->create();
    $empresa2 = Empresa::factory()->create();
    $user = User::factory()->create(['empresa_id' => $empresa1->id]);
    $file = File::factory()->create(['empresa_id' => $empresa2->id]);

    $response = $this->actingAs($user)->get(route('files.show', $file));

    $response->assertForbidden();
});

it('técnico normal não pode ver arquivo de categoria diferente', function () {
    $empresa = Empresa::factory()->create();
    $category1 = Category::factory()->create(['empresa_id' => $empresa->id]);
    $category2 = Category::factory()->create(['empresa_id' => $empresa->id]);
    
    $tecnicoNormal = User::factory()->create([
        'role' => 'normal_technician',
        'empresa_id' => $empresa->id,
        'category_id' => $category1->id,
    ]);

    $file = File::factory()->create([
        'empresa_id' => $empresa->id,
        'category_id' => $category2->id,
    ]);

    $response = $this->actingAs($tecnicoNormal)->get(route('files.show', $file));

    $response->assertForbidden();
});

// =====================================================
// TESTES DE DOWNLOAD
// =====================================================

it('usuário pode fazer download de arquivo que tem permissão', function () {
    Storage::fake('private');
    
    $empresa = Empresa::factory()->create();
    $user = User::factory()->create(['empresa_id' => $empresa->id]);
    
    // Criar arquivo real no storage fake
    $content = 'Conteúdo do arquivo de teste';
    Storage::disk('private')->put('files/teste.pdf', $content);
    
    $file = File::factory()->create([
        'empresa_id' => $empresa->id,
        'file_path' => 'files/teste.pdf',
        'original_name' => 'documento.pdf',
        'mime_type' => 'application/pdf',
    ]);

    $response = $this->actingAs($user)->get(route('files.download', $file));

    $response->assertOk();
    expect($response->headers->get('content-type'))->toBe('application/pdf');
});

it('retorna 404 para arquivo não encontrado no storage', function () {
    $empresa = Empresa::factory()->create();
    $user = User::factory()->create(['empresa_id' => $empresa->id]);
    
    $file = File::factory()->create([
        'empresa_id' => $empresa->id,
        'file_path' => 'files/arquivo_inexistente.pdf',
    ]);

    $response = $this->actingAs($user)->get(route('files.download', $file));

    $response->assertNotFound();
});

// =====================================================
// TESTES DE EDIÇÃO E EXCLUSÃO
// =====================================================

it('dono do arquivo pode editá-lo', function () {
    $user = User::factory()->create();
    $file = File::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get(route('files.edit', $file));

    $response->assertOk();
});

it('admin pode editar qualquer arquivo da empresa', function () {
    $empresa = Empresa::factory()->create();
    $admin = User::factory()->create(['role' => 'admin', 'empresa_id' => $empresa->id]);
    $file = File::factory()->create(['empresa_id' => $empresa->id]);

    $response = $this->actingAs($admin)->get(route('files.edit', $file));

    $response->assertOk();
});

it('usuário não pode editar arquivo de outro usuário', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $file = File::factory()->create(['user_id' => $user1->id]);

    $response = $this->actingAs($user2)->get(route('files.edit', $file));

    $response->assertForbidden();
});

it('pode deletar arquivo e remove do storage', function () {
    Storage::fake('private');
    
    $user = User::factory()->create();
    
    // Criar arquivo real no storage
    Storage::disk('private')->put('files/teste.pdf', 'conteúdo teste');
    
    $file = File::factory()->create([
        'user_id' => $user->id,
        'file_path' => 'files/teste.pdf',
    ]);

    expect(Storage::disk('private')->exists('files/teste.pdf'))->toBeTrue();

    $response = $this->actingAs($user)->delete(route('files.destroy', $file));

    $response->assertRedirect(route('files.index'));
    $this->assertDatabaseMissing('files', ['id' => $file->id]);
    expect(Storage::disk('private')->exists('files/teste.pdf'))->toBeFalse();
});

// =====================================================
// TESTES DE API SUBCATEGORIAS
// =====================================================

it('retorna subcategorias de uma categoria via AJAX', function () {
    $empresa = Empresa::factory()->create();
    $user = User::factory()->create(['empresa_id' => $empresa->id]);
    $category = Category::factory()->create(['empresa_id' => $empresa->id]);
    
    $subcategories = Subcategory::factory()->count(3)->create(['category_id' => $category->id]);

    $response = $this->actingAs($user)->get(route('api.subcategories', $category));

    $response->assertOk();
    $response->assertJsonCount(3);
    
    $responseData = $response->json();
    expect($responseData[0]['nome'])->toBe($subcategories[0]->nome);
});

it('técnico normal não pode acessar subcategorias de categoria não permitida', function () {
    $empresa = Empresa::factory()->create();
    $category1 = Category::factory()->create(['empresa_id' => $empresa->id]);
    $category2 = Category::factory()->create(['empresa_id' => $empresa->id]);
    
    $tecnicoNormal = User::factory()->create([
        'role' => 'normal_technician',
        'empresa_id' => $empresa->id,
        'category_id' => $category1->id,
    ]);

    $response = $this->actingAs($tecnicoNormal)->get(route('api.subcategories', $category2));

    $response->assertForbidden();
}); 