@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detalhes da Categoria</h1>
                <p class="text-gray-600 mt-2">Informações completas sobre a categoria {{ $category->name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('super_admin.categories.edit', $category) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar
                </a>
                <a href="{{ route('super_admin.categories.index') }}" 
                   class="inline-block bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition-colors">
                    ← Voltar às Categorias
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informações Principais -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Informações Básicas</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Nome</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $category->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Empresa</label>
                            <p class="text-lg text-gray-900">
                                @if($category->empresa)
                                    {{ $category->empresa->name }}
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                        Específica
                                    </span>
                                @else
                                    Sistema Global
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2">
                                        Global
                                    </span>
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Criado em</label>
                            <p class="text-lg text-gray-900">
                                {{ $category->created_at->format('d/m/Y H:i') }}
                                <span class="text-sm text-gray-500">({{ $category->created_at->diffForHumans() }})</span>
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Última atualização</label>
                            <p class="text-lg text-gray-900">
                                {{ $category->updated_at->format('d/m/Y H:i') }}
                                <span class="text-sm text-gray-500">({{ $category->updated_at->diffForHumans() }})</span>
                            </p>
                        </div>
                    </div>
                    
                    @if($category->description)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-500 mb-2">Descrição</label>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-900">{{ $category->description }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Subcategorias -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">Subcategorias</h2>
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ $category->subcategories->count() }} total
                        </span>
                    </div>
                    
                    @if($category->subcategories->count() > 0)
                        <div class="space-y-3">
                            @foreach($category->subcategories as $subcategory)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <h3 class="font-medium text-gray-900">{{ $subcategory->name }}</h3>
                                        @if($subcategory->description)
                                            <p class="text-sm text-gray-600">{{ $subcategory->description }}</p>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $subcategory->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma subcategoria</h3>
                            <p class="mt-1 text-sm text-gray-500">Esta categoria ainda não possui subcategorias.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Painel Lateral -->
            <div class="space-y-6">
                <!-- Estatísticas -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Estatísticas</h2>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Subcategorias:</span>
                            <span class="font-semibold text-gray-900">{{ $category->subcategories->count() }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Arquivos:</span>
                            <span class="font-semibold text-gray-900">{{ $category->files->count() ?? 0 }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Tipo:</span>
                            <span class="font-semibold text-{{ $category->empresa_id ? 'blue' : 'green' }}-600">
                                {{ $category->empresa_id ? 'Específica' : 'Global' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Ações -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Ações</h2>
                    
                    <div class="space-y-3">
                        <a href="{{ route('super_admin.categories.edit', $category) }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar Categoria
                        </a>
                        
                        <form action="{{ route('super_admin.categories.destroy', $category) }}" 
                              method="POST" 
                              onsubmit="return confirm('Tem certeza que deseja excluir esta categoria? Esta ação não pode ser desfeita.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Excluir Categoria
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Informações do Sistema -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-medium text-gray-900 mb-2">Informações do Sistema</h3>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p><strong>ID:</strong> {{ $category->id }}</p>
                        <p><strong>Empresa ID:</strong> {{ $category->empresa_id ?? 'Global' }}</p>
                        <p><strong>Slug:</strong> {{ $category->slug ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
