<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empresa = Auth::user()->empresa;

        if (!$empresa) {
            return redirect()->route('dashboard')
                ->with('warning', 'Você precisa estar associado a uma empresa para gerenciar usuários.');
        }

        $users = $empresa->users()
            ->with('category')
            ->orderBy('name')
            ->paginate(20);

        $categories = $empresa->categories()->orderBy('nome')->get();

        return view('empresa.users', compact('users', 'categories', 'empresa'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('users.index')->with('info', 'Utilize o botão "Novo Usuário" na lista.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $empresa = Auth::user()->empresa;
        if (!$empresa) {
            return back()->withErrors(['error' => 'Administrador não associado a uma empresa.'])->withInput();
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,general_technician,normal_technician',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        if ($request->category_id) {
            $category = Category::find($request->category_id);
            if (!$category || $category->empresa_id !== $empresa->id) {
                return back()->withErrors(['category_id' => 'Categoria inválida ou não pertence à sua empresa.'])->withInput();
            }
        }

        if ($request->role === 'normal_technician' && !$request->category_id) {
            return back()->withErrors(['category_id' => 'Técnicos normais devem ter uma categoria atribuída.'])->withInput();
        }
        if ($request->role !== 'normal_technician' && $request->category_id) {
            $request->merge(['category_id' => null]);
        }

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'category_id' => $request->category_id,
                'empresa_id' => $empresa->id,
                'approval_status' => 'approved', // Usuários criados por company admin são aprovados automaticamente
                'approved_at' => now(),
                'approved_by' => Auth::id(),
            ]);

            return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao criar usuário: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $empresa = Auth::user()->empresa;

        if (!$empresa) {
            return back()->withErrors(['error' => 'Administrador não associado a uma empresa.']);
        }

        // Verificar se o usuário pertence à empresa
        if ($user->empresa_id !== $empresa->id) {
            abort(403, 'Usuário não pertence à sua empresa.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,general_technician,normal_technician',
            'category_id' => 'nullable|exists:categories,id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Validar categoria baseada no papel
        if ($request->role === 'normal_technician' && !$request->category_id) {
            return back()->withErrors(['category_id' => 'Técnicos normais devem ter uma categoria atribuída.']);
        }

        if ($request->role !== 'normal_technician' && $request->category_id) {
            return back()->withErrors(['category_id' => 'Apenas técnicos normais podem ter categoria atribuída.']);
        }

        // Verificar se a categoria pertence à empresa
        if ($request->category_id) {
            $category = Category::find($request->category_id);
            if (!$category || $category->empresa_id !== $empresa->id) {
                return back()->withErrors(['category_id' => 'Categoria inválida.']);
            }
        }

        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'category_id' => $request->category_id,
            ];

            // Atualizar senha se fornecida
            if ($request->password) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso!');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Erro ao atualizar usuário.'])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $adminLogado = Auth::user();
        $empresaDoAdmin = $adminLogado->empresa;

        if (!$empresaDoAdmin) {
            return redirect()->route('dashboard')->with('error', 'Administrador não associado a uma empresa.');
        }

        if (!$user->empresa_id || $user->empresa_id !== $empresaDoAdmin->id) {
            return back()->with('error', 'Usuário não pertence à sua empresa ou não pode ser excluído.');
        }

        if ($user->id === $adminLogado->id) {
            return back()->with('error', 'Você não pode excluir sua própria conta.');
        }

        if (method_exists($user, 'files') && $user->files()->count() > 0) {
            return back()->with('error', 'Não é possível excluir usuário que possui arquivos associados. Remova os arquivos primeiro.');
        }

        try {
            $userName = $user->name;
            $user->delete();

            return redirect()->route('users.index')->with('success', "Usuário '$userName' excluído com sucesso!");

        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir usuário: ' . $e->getMessage());
        }
    }
}
