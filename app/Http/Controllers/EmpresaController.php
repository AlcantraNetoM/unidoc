<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EmpresaController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth')->except(['showRegistrationForm', 'store', 'logo']);
        $this->middleware('role:admin')->except(['showRegistrationForm', 'store', 'logo']);
    }

    /**
     * Exibe o formulário de cadastro de empresa
     */
    public function create()
    {
        return view('empresa.register');
    }

    /**
     * Mostrar formulário de registro da empresa (público)
     */
    public function showRegistrationForm()
    {
        return view('empresa.register');
    }

    /**
     * Registrar nova empresa (público)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:empresas,email',
            'endereco' => 'required|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255|unique:users,email',
            'admin_password' => 'required|string|min:8|confirmed',
        ]);

        try {
            // Salvar logo se fornecida
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('logos', 'public');
            }

            // Criar empresa
            $empresa = Empresa::create([
                'nome' => $request->nome,
                'email' => $request->email,
                'endereco' => $request->endereco,
                'logo_path' => $logoPath,
            ]);

            // Criar usuário administrador
            $admin = User::create([
                'name' => $request->admin_name,
                'email' => $request->admin_email,
                'password' => Hash::make($request->admin_password),
                'role' => 'admin',
                'empresa_id' => $empresa->id,
                'approval_status' => 'pending', // Registro público precisa de aprovação
            ]);

            // Login automático
            Auth::login($admin);

            return redirect()
                ->route('dashboard')
                ->with('success', 'Empresa registrada com sucesso! Bem-vindo ao sistema.');

        } catch (\Exception $e) {
            // Se houve erro, remover logo se foi salva
            if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                Storage::disk('public')->delete($logoPath);
            }

            return back()
                ->withErrors(['error' => 'Erro ao registrar empresa. Tente novamente.'])
                ->withInput();
        }
    }

    /**
     * Exibe o logo da empresa
     */
    public function logo(Empresa $empresa)
    {
        if (!$empresa->logo_path || !Storage::disk('public')->exists($empresa->logo_path)) {
            abort(404);
        }

        $file = Storage::disk('public')->get($empresa->logo_path);
        $path = Storage::disk('public')->path($empresa->logo_path);
        $mimeType = mime_content_type($path) ?: 'image/jpeg';
        
        return response($file, 200)->header('Content-Type', $mimeType);
    }

    /**
     * Lista todas as empresas (para super admin - futuro)
     */
    public function index()
    {
        $empresas = Empresa::withCount(['users', 'categories', 'files'])->paginate(15);
        return view('empresa.index', compact('empresas'));
    }

    /**
     * Exibe detalhes de uma empresa
     * Como esta rota é para o admin ver detalhes da SUA empresa,
     * vamos redirecionar/reutilizar a lógica de settings().
     */
    public function show(Empresa $empresa)
    {
        // Garante que estamos sempre mostrando a empresa do usuário autenticado,
        // independentemente do que for passado via route model binding (se houver).
        // Ou, idealmente, a rota não deveria ter o parâmetro {empresa} se é sempre a do Auth.
        // Por ora, vamos apenas chamar o método settings().
        return $this->settings();
    }

    /**
     * Exibe formulário de edição da empresa
     */
    public function edit(Empresa $empresa)
    {
        // TODO: Implementar Policy para autorização
        return view('empresa.edit', compact('empresa'));
    }

    /**
     * Mostrar configurações da empresa (apenas admin)
     */
    public function settings()
    {
        $user = Auth::user();
        $empresa = $user->empresa;
        
        // Verificar se o usuário tem uma empresa associada
        if (!$empresa) {
            return redirect()->route('dashboard')
                ->with('error', 'Você não está associado a nenhuma empresa.');
        }
        
        $empresa->load(['users', 'categories']);
        
        // Informações de pagamento
        $paymentInfo = [
            'account_status' => $empresa->account_status ?? 'trial',
            'total_months_paid' => $empresa->total_months_paid ?? 0,
            'subscription_end_date' => $empresa->subscription_end_date,
            'monthly_price' => 10000, // 10.000 Kz para empresas
            'can_make_payments' => true,
        ];
        
        $stats = [
            'total_usuarios' => $empresa->users->count(),
            'total_categorias' => $empresa->categories->count(),
            'total_arquivos' => $empresa->files()->count(),
            'admins' => $empresa->users->where('role', 'admin')->count(),
            'general_technicians' => $empresa->users->where('role', 'general_technician')->count(),
            'normal_technicians' => $empresa->users->where('role', 'normal_technician')->count(),
        ];

        return view('empresa.settings', compact('empresa', 'stats', 'paymentInfo'));
    }

    /**
     * Atualizar dados da empresa (apenas admin)
     */
    public function update(Request $request)
    {
        $empresa = Auth::user()->empresa;

        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('empresas')->ignore($empresa->id)],
            'endereco' => 'required|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'primary_color' => 'nullable|string|max:20',
            'secondary_color' => 'nullable|string|max:20',
        ]);

        try {
            $data = [
                'nome' => $request->nome,
                'email' => $request->email,
                'endereco' => $request->endereco,
                'primary_color' => $request->primary_color,
                'secondary_color' => $request->secondary_color,
            ];

            // Atualizar logo se fornecida
            if ($request->hasFile('logo')) {
                // Remover logo antiga
                if ($empresa->logo_path && Storage::disk('public')->exists($empresa->logo_path)) {
                    Storage::disk('public')->delete($empresa->logo_path);
                }
                
                $data['logo_path'] = $request->file('logo')->store('logos', 'public');
            }

            $empresa->update($data);

            return back()->with('success', 'Dados da empresa atualizados com sucesso!');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Erro ao atualizar dados da empresa.'])
                ->withInput();
        }
    }

    /**
     * Remover logo da empresa (apenas admin)
     */
    public function removeLogo()
    {
        $empresa = Auth::user()->empresa;

        if ($empresa->logo_path && Storage::disk('public')->exists($empresa->logo_path)) {
            Storage::disk('public')->delete($empresa->logo_path);
            $empresa->update(['logo_path' => null]);
            
            return back()->with('success', 'Logo removido com sucesso!');
        }

        return back()->with('error', 'Nenhum logo encontrado para remover.');
    }

    /**
     * Listar usuários da empresa (apenas admin)
     */
    public function users()
    {
        $empresa = Auth::user()->empresa;
        
        // Buscar apenas usuários aprovados para a lista principal
        $users = $empresa->users()
            ->where('approval_status', 'approved')
            ->with('category')
            ->orderBy('name')
            ->paginate(20);

        // Buscar usuários pendentes para a empresa
        $usuariosPendentes = User::where('empresa_id', $empresa->id)
            ->where('approval_status', 'pending')
            ->with('category')
            ->latest()
            ->get();

        $categories = $empresa->categories()->orderBy('nome')->get();

        return view('empresa.users', compact('users', 'categories', 'empresa', 'usuariosPendentes'));
    }

    /**
     * Aprovar usuário (apenas admin de empresa)
     */
    public function approveUser(User $user)
    {
        $empresa = Auth::user()->empresa;

        // Verificar se o usuário pertence à empresa do admin
        if ($user->empresa_id !== $empresa->id) {
            return back()->with('error', 'Você só pode aprovar usuários da sua empresa.');
        }

        // Verificar se o usuário está pendente
        if ($user->approval_status !== 'pending') {
            return back()->with('error', 'Este usuário já foi processado.');
        }

        $user->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'Usuário aprovado com sucesso!');
    }

    /**
     * Rejeitar usuário (apenas admin de empresa)
     */
    public function rejectUser(User $user)
    {
        $empresa = Auth::user()->empresa;

        // Verificar se o usuário pertence à empresa do admin
        if ($user->empresa_id !== $empresa->id) {
            return back()->with('error', 'Você só pode rejeitar usuários da sua empresa.');
        }

        // Verificar se o usuário está pendente
        if ($user->approval_status !== 'pending') {
            return back()->with('error', 'Este usuário já foi processado.');
        }

        $user->update([
            'approval_status' => 'rejected',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'Usuário rejeitado.');
    }

    /**
     * Criar novo usuário (apenas admin)
     */
    public function storeUser(Request $request)
    {
        $empresa = Auth::user()->empresa;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/',
            'role' => 'required|in:admin,general_technician,normal_technician',
            'category_id' => 'nullable|exists:categories,id',
        ], [
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação da senha não confere.',
            'password.regex' => 'A senha deve conter pelo menos: 1 letra minúscula, 1 maiúscula e 1 número.',
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
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'categoria_id' => $request->category_id,
                'empresa_id' => $empresa->id,
                'approval_status' => 'approved', // Usuários criados por company admin são aprovados automaticamente
                'approved_at' => now(),
                'approved_by' => Auth::id(),
            ]);

            return back()->with('success', 'Usuário criado com sucesso!');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Erro ao criar usuário.'])
                ->withInput();
        }
    }

    /**
     * Atualizar usuário (apenas admin)
     */
    public function updateUser(Request $request, User $user)
    {
        // Verificar se o usuário pertence à empresa
        if ($user->empresa_id !== Auth::user()->empresa_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,general_technician,normal_technician',
            'category_id' => 'nullable|exists:categories,id',
            'password' => 'nullable|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/',
        ], [
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação da senha não confere.',
            'password.regex' => 'A senha deve conter pelo menos: 1 letra minúscula, 1 maiúscula e 1 número.',
        ]);

        // Validar categoria baseada no papel
        if ($request->role === 'normal_technician' && !$request->category_id) {
            return back()->withErrors(['category_id' => 'Técnicos normais devem ter uma categoria atribuída.']);
        }

        if ($request->role !== 'normal_technician' && $request->category_id) {
            return back()->withErrors(['category_id' => 'Apenas técnicos normais podem ter categoria atribuída.']);
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

            return back()->with('success', 'Usuário atualizado com sucesso!');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Erro ao atualizar usuário.'])
                ->withInput();
        }
    }

    /**
     * Excluir usuário (apenas admin)
     */
    public function destroyUser(User $user)
    {
        // Verificar se o usuário pertence à empresa
        if ($user->empresa_id !== Auth::user()->empresa_id) {
            abort(403);
        }

        // Impedir que o admin exclua a si mesmo
        if ($user->id === Auth::user()->id) {
            return back()->with('error', 'Você não pode excluir sua própria conta.');
        }

        // Verificar se há arquivos associados
        if ($user->files()->count() > 0) {
            return back()->with('error', 'Não é possível excluir usuário que possui arquivos associados.');
        }

        try {
            $userName = $user->name;
            $user->delete();

            return back()->with('success', "Usuário '{$userName}' excluído com sucesso!");

        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir usuário.');
        }
    }

    /**
     * Mostrar relatórios da empresa (apenas admin)
     */
    public function reports()
    {
        $empresa = Auth::user()->empresa;
        
        // Estatísticas gerais
        $stats = [
            'total_usuarios' => $empresa->users()->count(),
            'total_categorias' => $empresa->categories()->count(),
            'total_subcategorias' => $empresa->categories()->withCount('subcategories')->get()->sum('subcategories_count'),
            'total_arquivos' => $empresa->files()->count(),
            'arquivos_mes' => $empresa->files()->whereMonth('created_at', now()->month)->count(),
            'tamanho_total' => $empresa->files()->sum('size'),
        ];

        // Arquivos por categoria
        $arquivosPorCategoria = $empresa->categories()
            ->withCount('files')
            ->orderByDesc('files_count')
            ->get();

        // Usuários por papel
        $usuariosPorPapel = $empresa->users()
            ->selectRaw('role, COUNT(*) as count')
            ->groupBy('role')
            ->get()
            ->pluck('count', 'role');

        // Arquivos por mês (últimos 12 meses)
        $arquivosPorMes = $empresa->files()
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return view('empresa.reports', compact(
            'empresa',
            'stats',
            'arquivosPorCategoria',
            'usuariosPorPapel',
            'arquivosPorMes'
        ));
    }

    /**
     * Atribuir categoria a um usuário (apenas admin)
     */
    public function assignCategory(Request $request, User $user)
    {
        $empresa = Auth::user()->empresa;

        // Verificar se o usuário pertence à empresa
        if ($user->empresa_id !== $empresa->id) {
            return back()->with('error', 'Você só pode atribuir categorias a usuários da sua empresa.');
        }

        // Validar a categoria
        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
        ]);

        // Verificar se a categoria pertence à empresa (se fornecida)
        if ($request->category_id) {
            $category = Category::find($request->category_id);
            if (!$category || $category->empresa_id !== $empresa->id) {
                return back()->with('error', 'Categoria inválida ou não pertence à sua empresa.');
            }
        }

        // Verificar se só técnicos normais podem ter categoria
        if ($request->category_id && $user->role !== 'normal_technician') {
            return back()->with('error', 'Apenas técnicos normais podem ter categoria atribuída.');
        }

        try {
            $user->update(['category_id' => $request->category_id]);

            $message = $request->category_id 
                ? 'Categoria atribuída com sucesso!' 
                : 'Categoria removida com sucesso!';

            return back()->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar categoria do usuário.');
        }
    }

    /**
     * Buscar dados do usuário para edição (apenas admin)
     */
    public function getUserData(User $user)
    {
        $empresa = Auth::user()->empresa;

        // Verificar se o usuário pertence à empresa
        if ($user->empresa_id !== $empresa->id) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'category_id' => $user->category_id,
        ]);
    }
}
