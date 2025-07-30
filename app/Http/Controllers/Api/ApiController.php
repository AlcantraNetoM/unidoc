<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Empresa;
use App\Models\File;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    /**
     * Resposta padrão da API
     */
    protected function apiResponse($data = null, $message = 'Success', $status = 200)
    {
        return response()->json([
            'success' => $status >= 200 && $status < 300,
            'message' => $message,
            'data' => $data,
            'timestamp' => now()->toISOString()
        ], $status);
    }

    /**
     * Resposta de erro da API
     */
    protected function apiError($message = 'Error', $status = 400, $errors = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'timestamp' => now()->toISOString()
        ], $status);
    }

    // ========================================
    // AUTENTICAÇÃO
    // ========================================

    /**
     * Login do usuário
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->apiError('Dados inválidos', 422, $validator->errors());
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->apiError('Credenciais inválidas', 401);
        }

        // Verificar se a conta está aprovada
        if (!$user->isApproved()) {
            return $this->apiError('Sua conta ainda não foi aprovada.', 403);
        }

        // Verificar se o trial não expirou
        if ($user->isTrialExpired()) {
            return $this->apiError('Seu período de teste expirou. Efetue o pagamento para continuar.', 403);
        }

        // Criar token
        $token = $user->createToken('mobile-app')->plainTextToken;

        return $this->apiResponse([
            'user' => $this->formatUserData($user),
            'token' => $token,
            'token_type' => 'Bearer'
        ], 'Login realizado com sucesso');
    }

    /**
     * Logout do usuário
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->apiResponse(null, 'Logout realizado com sucesso');
    }

    /**
     * Dados do usuário autenticado
     */
    public function me(Request $request)
    {
        return $this->apiResponse([
            'user' => $this->formatUserData($request->user())
        ]);
    }

    // ========================================
    // GESTÃO DE ARQUIVOS
    // ========================================

    /**
     * Listar arquivos do usuário
     */
    public function files(Request $request)
    {
        $user = $request->user();
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');

        $query = File::where(function($q) use ($user) {
            if ($user->role === 'admin') {
                $q->where('empresa_id', $user->empresa_id);
            } else {
                $q->where('user_id', $user->id);
            }
        })->with(['category', 'subcategory', 'user']);

        // Aplicar filtros
        if ($search) {
            $query->where('original_name', 'like', "%{$search}%");
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('subcategory_id')) {
            $query->where('subcategory_id', $request->subcategory_id);
        }

        $files = $query->latest()->paginate($perPage);

        return $this->apiResponse([
            'files' => $files->items(),
            'pagination' => [
                'current_page' => $files->currentPage(),
                'total_pages' => $files->lastPage(),
                'per_page' => $files->perPage(),
                'total' => $files->total()
            ]
        ]);
    }

    /**
     * Upload de arquivo
     */
    public function uploadFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'files.*' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png|max:10240',
            'category_id' => 'nullable|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'tags' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return $this->apiError('Dados inválidos', 422, $validator->errors());
        }

        $user = $request->user();
        $uploadedFiles = [];

        foreach ($request->file('files') as $file) {
            try {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('files', $fileName, 'private');

                $fileRecord = File::create([
                    'original_name' => $file->getClientOriginalName(),
                    'stored_name' => $fileName,
                    'file_path' => $filePath,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'user_id' => $user->id,
                    'empresa_id' => $user->empresa_id,
                    'category_id' => $request->category_id,
                    'subcategory_id' => $request->subcategory_id,
                    'tags' => $request->tags,
                ]);

                $uploadedFiles[] = $fileRecord;

            } catch (\Exception $e) {
                return $this->apiError('Erro ao fazer upload do arquivo: ' . $e->getMessage(), 500);
            }
        }

        return $this->apiResponse([
            'files' => $uploadedFiles
        ], 'Arquivo(s) enviado(s) com sucesso');
    }

    /**
     * Download de arquivo
     */
    public function downloadFile($id, Request $request)
    {
        $user = $request->user();
        
        $file = File::where('id', $id)
            ->where(function($q) use ($user) {
                if ($user->role === 'admin') {
                    $q->where('empresa_id', $user->empresa_id);
                } else {
                    $q->where('user_id', $user->id);
                }
            })
            ->first();

        if (!$file) {
            return $this->apiError('Arquivo não encontrado', 404);
        }

        if (!Storage::disk('private')->exists($file->file_path)) {
            return $this->apiError('Arquivo não existe no servidor', 404);
        }

        return response()->download(
            Storage::disk('private')->path($file->file_path), 
            $file->original_name
        );
    }

    /**
     * Excluir arquivo
     */
    public function deleteFile($id, Request $request)
    {
        $user = $request->user();
        
        $file = File::where('id', $id)
            ->where(function($q) use ($user) {
                if ($user->role === 'admin') {
                    $q->where('empresa_id', $user->empresa_id);
                } else {
                    $q->where('user_id', $user->id);
                }
            })
            ->first();

        if (!$file) {
            return $this->apiError('Arquivo não encontrado', 404);
        }

        // Excluir arquivo físico
        if (Storage::disk('private')->exists($file->file_path)) {
            Storage::disk('private')->delete($file->file_path);
        }

        $file->delete();

        return $this->apiResponse(null, 'Arquivo excluído com sucesso');
    }

    // ========================================
    // CATEGORIAS E SUBCATEGORIAS
    // ========================================

    /**
     * Listar categorias
     */
    public function categories(Request $request)
    {
        $user = $request->user();
        
        $categories = Category::where('empresa_id', $user->empresa_id)
            ->with('subcategories')
            ->get();

        return $this->apiResponse(['categories' => $categories]);
    }

    /**
     * Subcategorias de uma categoria
     */
    public function subcategories($categoryId, Request $request)
    {
        $user = $request->user();
        
        $category = Category::where('id', $categoryId)
            ->where('empresa_id', $user->empresa_id)
            ->first();

        if (!$category) {
            return $this->apiError('Categoria não encontrada', 404);
        }

        $subcategories = $category->subcategories;

        return $this->apiResponse(['subcategories' => $subcategories]);
    }

    // ========================================
    // DASHBOARD E ESTATÍSTICAS
    // ========================================

    /**
     * Dados do dashboard
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();

        $stats = [
            'total_files' => File::where(function($q) use ($user) {
                if ($user->role === 'admin') {
                    $q->where('empresa_id', $user->empresa_id);
                } else {
                    $q->where('user_id', $user->id);
                }
            })->count(),

            'storage_used' => File::where(function($q) use ($user) {
                if ($user->role === 'admin') {
                    $q->where('empresa_id', $user->empresa_id);
                } else {
                    $q->where('user_id', $user->id);
                }
            })->sum('file_size'),

            'categories_count' => Category::where('empresa_id', $user->empresa_id)->count(),
        ];

        // Arquivos recentes
        $recentFiles = File::where(function($q) use ($user) {
            if ($user->role === 'admin') {
                $q->where('empresa_id', $user->empresa_id);
            } else {
                $q->where('user_id', $user->id);
            }
        })->with(['category', 'subcategory'])
          ->latest()
          ->limit(5)
          ->get();

        return $this->apiResponse([
            'stats' => $stats,
            'recent_files' => $recentFiles,
            'user' => $this->formatUserData($user)
        ]);
    }

    // ========================================
    // PERFIL DO USUÁRIO
    // ========================================

    /**
     * Atualizar perfil
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->apiError('Dados inválidos', 422, $validator->errors());
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->phone) {
            $data['phone'] = $request->phone;
        }

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return $this->apiResponse([
            'user' => $this->formatUserData($user->fresh())
        ], 'Perfil atualizado com sucesso');
    }

    // ========================================
    // MÉTODOS AUXILIARES
    // ========================================

    /**
     * Formatar dados do usuário para API
     */
    private function formatUserData($user)
    {
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'phone' => $user->phone ?? null,
            'approval_status' => $user->approval_status,
            'trial_start_date' => $user->trial_start_date,
            'trial_end_date' => $user->trial_end_date,
            'subscription_end_date' => $user->subscription_end_date ?? null,
            'account_status' => $user->account_status ?? 'active',
            'created_at' => $user->created_at,
        ];

        // Incluir dados da empresa se aplicável
        if ($user->empresa) {
            $data['empresa'] = [
                'id' => $user->empresa->id,
                'nome' => $user->empresa->nome,
                'email' => $user->empresa->email,
            ];
        }

        return $data;
    }
}
