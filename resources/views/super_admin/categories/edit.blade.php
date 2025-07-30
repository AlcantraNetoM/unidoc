@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Editar Categoria</h1>
                <p class="text-gray-600 mt-2">Modificar informações da categoria {{ $category->name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('super_admin.categories.show', $category) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Ver Detalhes
                </a>
                <a href="{{ route('super_admin.categories.index') }}" 
                   class="inline-block bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition-colors">
                    ← Voltar às Categorias
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <h4 class="font-medium mb-2">Erro de validação:</h4>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Formulário Principal -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <form action="{{ route('super_admin.categories.update', $category) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nome da Categoria *
                                </label>
                                <input type="text" 
                                       id="nome" 
                                       name="nome" 
                                       value="{{ old('nome', $category->nome) }}"
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Digite o nome da categoria">
                            </div>

                            <div>
                                <label for="empresa_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Empresa
                                </label>
                                <select id="empresa_id" 
                                        name="empresa_id" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Categoria Global (Todas as Empresas)</option>
                                    @foreach(\App\Models\Empresa::all() as $empresa)
                                        <option value="{{ $empresa->id }}" 
                                                {{ old('empresa_id', $category->empresa_id) == $empresa->id ? 'selected' : '' }}>
                                            {{ $empresa->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Deixe vazio para manter como categoria global</p>
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Descrição
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Descreva o propósito desta categoria...">{{ old('description', $category->description) }}</textarea>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <h3 class="font-medium text-yellow-900 mb-2">⚠️ Atenção</h3>
                            <ul class="text-yellow-800 text-sm space-y-1">
                                <li>• Alterar o nome da categoria pode afetar arquivos existentes</li>
                                <li>• Mudanças de escopo (global ↔ específica) devem ser feitas com cuidado</li>
                                <li>• Verifique se não há conflitos de nome antes de salvar</li>
                            </ul>
                        </div>

                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('super_admin.categories.show', $category) }}" 
                               class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Painel Lateral -->
            <div class="space-y-6">
                <!-- Informações Atuais -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informações Atuais</h2>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Nome Atual</label>
                            <p class="text-sm font-medium text-gray-900">{{ $category->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Empresa Atual</label>
                            <p class="text-sm text-gray-900">
                                {{ $category->empresa->name ?? 'Global' }}
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $category->empresa_id ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $category->empresa_id ? 'Específica' : 'Global' }}
                                </span>
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Criado em</label>
                            <p class="text-sm text-gray-900">{{ $category->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Última atualização</label>
                            <p class="text-sm text-gray-900">{{ $category->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Estatísticas -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Impacto das Alterações</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Subcategorias afetadas:</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $category->subcategories->count() }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Arquivos relacionados:</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $category->files->count() ?? 0 }}</span>
                        </div>
                        
                        @if($category->subcategories->count() > 0 || ($category->files->count() ?? 0) > 0)
                            <div class="bg-orange-50 border border-orange-200 rounded p-3 mt-4">
                                <p class="text-xs text-orange-800">
                                    <strong>Cuidado:</strong> Esta categoria possui dados relacionados. 
                                    Alterações podem afetar a organização existente.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Ações Avançadas -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Ações Avançadas</h2>
                    
                    <div class="space-y-3">
                        <a href="{{ route('super_admin.categories.show', $category) }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Ver Detalhes Completos
                        </a>
                        
                        <form action="{{ route('super_admin.categories.destroy', $category) }}" 
                              method="POST" 
                              onsubmit="return confirm('ATENÇÃO: Excluir esta categoria removerá todos os dados relacionados. Esta ação não pode ser desfeita. Tem certeza?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Excluir Categoria
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
