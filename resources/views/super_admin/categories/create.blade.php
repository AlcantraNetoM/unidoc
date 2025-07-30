@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Nova Categoria</h1>
                <p class="text-gray-600 mt-2">Criar uma nova categoria no sistema</p>
            </div>
            <a href="{{ route('super_admin.categories.index') }}" 
               class="inline-block bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition-colors">
                ← Voltar às Categorias
            </a>
        </div>

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

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('super_admin.categories.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome da Categoria *
                        </label>
                        <input type="text" 
                               id="nome" 
                               name="nome" 
                               value="{{ old('nome') }}"
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
                                <option value="{{ $empresa->id }}" {{ old('empresa_id') == $empresa->id ? 'selected' : '' }}>
                                    {{ $empresa->nome }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Deixe vazio para criar uma categoria global</p>
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
                              placeholder="Descreva o propósito desta categoria...">{{ old('description') }}</textarea>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="font-medium text-blue-900 mb-2">ℹ️ Informações sobre Categorias</h3>
                    <ul class="text-blue-800 text-sm space-y-1">
                        <li>• <strong>Categoria Global:</strong> Disponível para todas as empresas do sistema</li>
                        <li>• <strong>Categoria Específica:</strong> Disponível apenas para a empresa selecionada</li>
                        <li>• <strong>Nome:</strong> Deve ser único dentro do escopo (global ou por empresa)</li>
                    </ul>
                </div>

                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('super_admin.categories.index') }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        Criar Categoria
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
