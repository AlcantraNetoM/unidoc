<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategoryController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware(['auth']);
        // Removido middleware role:admin para permitir que as policies controlem o acesso
    }

    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $categories = Category::where('empresa_id', Auth::user()->empresa_id)
            ->withCount(['subcategories', 'files'])
            ->orderBy('nome')
            ->paginate(15);

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'subcategorias' => 'nullable|array',
            'subcategorias.*' => 'string|max:255',
        ]);

        // Verificar se já existe categoria com este nome na empresa
        $existingCategory = Category::where('empresa_id', Auth::user()->empresa_id)
            ->where('nome', $request->nome)
            ->first();

        if ($existingCategory) {
            return back()
                ->withErrors(['nome' => 'Já existe uma categoria com este nome.'])
                ->withInput();
        }

        $category = Category::create([
            'nome' => $request->nome,
            'empresa_id' => Auth::user()->empresa_id,
        ]);

        // Criar subcategorias se fornecidas
        if ($request->subcategorias) {
            foreach ($request->subcategorias as $subcategoriaNome) {
                if (!empty(trim($subcategoriaNome))) {
                    Subcategory::create([
                        'nome' => trim($subcategoriaNome),
                        'category_id' => $category->id,
                    ]);
                }
            }
        }

        return redirect()
            ->route('categories.index')
            ->with('success', 'Categoria criada com sucesso!');
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        $this->authorize('view', $category);

        $category->load(['subcategories', 'files.user']);
        
        $files = $category->files()
            ->with(['user', 'subcategory'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('categories.show', compact('category', 'files'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        $this->authorize('update', $category);

        $category->load('subcategories');

        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $request->validate([
            'nome' => 'required|string|max:255',
            'subcategorias' => 'nullable|array',
            'subcategorias.*' => 'string|max:255',
        ]);

        // Verificar se já existe outra categoria com este nome na empresa
        $existingCategory = Category::where('empresa_id', Auth::user()->empresa_id)
            ->where('nome', $request->nome)
            ->where('id', '!=', $category->id)
            ->first();

        if ($existingCategory) {
            return back()
                ->withErrors(['nome' => 'Já existe uma categoria com este nome.'])
                ->withInput();
        }

        $category->update([
            'nome' => $request->nome,
        ]);

        // Gerenciar subcategorias
        if ($request->has('subcategorias')) {
            // Remover subcategorias existentes que não estão na nova lista
            $novasSubcategorias = array_filter(array_map('trim', $request->subcategorias));
            
            // Excluir subcategorias que não existem mais
            $category->subcategories()
                ->whereNotIn('nome', $novasSubcategorias)
                ->delete();

            // Adicionar novas subcategorias
            foreach ($novasSubcategorias as $subcategoriaNome) {
                if (!empty($subcategoriaNome)) {
                    Subcategory::firstOrCreate([
                        'nome' => $subcategoriaNome,
                        'category_id' => $category->id,
                    ]);
                }
            }
        } else {
            // Se não há subcategorias no request, manter as existentes
        }

        return redirect()
            ->route('categories.index')
            ->with('success', 'Categoria atualizada com sucesso!');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        // Verificar se há arquivos associados
        if ($category->files()->count() > 0) {
            return back()->with('error', 'Não é possível excluir uma categoria que possui arquivos associados.');
        }

        // Verificar se há usuários associados
        if ($category->users()->count() > 0) {
            return back()->with('error', 'Não é possível excluir uma categoria que possui usuários associados.');
        }

        $nomeCategoria = $category->nome;
        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', "Categoria '{$nomeCategoria}' excluída com sucesso!");
    }

    /**
     * Show subcategories for a specific category (AJAX)
     */
    public function subcategories(Category $category)
    {
        // Verificar se o usuário pode acessar esta categoria
        if ($category->empresa_id !== Auth::user()->empresa_id) {
            abort(403);
        }

        // Para técnicos normais, verificar se podem acessar esta categoria
        if (Auth::user()->role === 'normal_technician' && Auth::user()->category_id !== $category->id) {
            abort(403);
        }

        $subcategories = $category->subcategories()
            ->orderBy('nome')
            ->get(['id', 'nome']);

        return response()->json($subcategories);
    }

    /**
     * Create a new subcategory via AJAX
     */
    public function storeSubcategory(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        // Verificar se já existe subcategoria com este nome na categoria
        $existingSubcategory = $category->subcategories()
            ->where('nome', $request->nome)
            ->first();

        if ($existingSubcategory) {
            return response()->json([
                'error' => 'Já existe uma subcategoria com este nome nesta categoria.'
            ], 422);
        }

        $subcategory = Subcategory::create([
            'nome' => $request->nome,
            'category_id' => $category->id,
        ]);

        return response()->json([
            'success' => true,
            'subcategory' => $subcategory,
            'message' => 'Subcategoria criada com sucesso!'
        ]);
    }

    /**
     * Delete a subcategory via AJAX
     */
    public function destroySubcategory(Category $category, Subcategory $subcategory)
    {
        $this->authorize('update', $category);

        // Verificar se a subcategoria pertence à categoria
        if ($subcategory->category_id !== $category->id) {
            abort(404);
        }

        // Verificar se há arquivos associados
        if ($subcategory->files()->count() > 0) {
            return response()->json([
                'error' => 'Não é possível excluir uma subcategoria que possui arquivos associados.'
            ], 422);
        }

        $nomeSubcategoria = $subcategory->nome;
        $subcategory->delete();

        return response()->json([
            'success' => true,
            'message' => "Subcategoria '{$nomeSubcategoria}' excluída com sucesso!"
        ]);
    }
}
