<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\File;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // Super admin should not have access to files - redirect to their dashboard
        if ($user->isSuperAdmin()) {
            return redirect()->route('super_admin.dashboard')->with('info', 'Super admins não têm arquivos. Use o dashboard para gerenciar o sistema.');
        }

        // Base query differs for personal vs company users
        if ($user->isPersonalUser()) {
            // Personal users see only their own files
            $query = File::with(['user'])
                ->where('user_id', $user->id)
                ->whereNull('empresa_id');
        } else {
            // Company users see files from their company
            $empresa = $user->empresa;
            $query = File::with(['user', 'category', 'subcategory'])
                ->where('empresa_id', $empresa->id);

            // Filter based on user role
            if ($user->role === 'normal_technician') {
                $query->where('category_id', $user->category_id);
            }
        }

        // Search filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('descricao', 'like', "%{$search}%");
            });
        }

        // Category filters only apply to company users
        if (!$user->isPersonalUser()) {
            if ($request->filled('category_id')) {
                $query->where('category_id', $request->input('category_id'));
            }

            if ($request->filled('subcategory_id')) {
                $query->where('subcategory_id', $request->input('subcategory_id'));
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $files = $query->latest()->paginate(20);

        // Categories for filters - only for company users
        $categories = collect();
        $subcategories = collect();
        $empresa = null;
        
        if (!$user->isPersonalUser()) {
            $empresa = $user->empresa;
            $categories = $empresa->categories;
            
            if ($request->filled('category_id')) {
                $subcategories = Subcategory::where('category_id', $request->input('category_id'))->get();
            }
        }

        return view('files.index', compact('files', 'categories', 'subcategories', 'empresa'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        /** @var User $user */
        $user = Auth::user();

        // Super admin should not have access to files - redirect to their dashboard
        if ($user->isSuperAdmin()) {
            return redirect()->route('super_admin.dashboard')->with('info', 'Super admins não têm arquivos. Use o dashboard para gerenciar o sistema.');
        }

        // Personal users don't need categories
        if ($user->isPersonalUser()) {
            return view('files.create', [
                'categories' => collect(),
                'subcategories' => collect(),
                'empresa' => null,
                'isPersonalUser' => true
            ]);
        }

        // Company users get categories based on their role
        $empresa = $user->empresa;

        if ($user->role === 'normal_technician') {
            // Normal technician can only upload to their category
            $categories = collect([$user->category]);
            $subcategories = $user->category->subcategories ?? collect();
        } else {
            // Admin and general technician can upload to any category
            $categories = $empresa->categories;
            $subcategories = collect();
        }

        return view('files.create', [
            'categories' => $categories,
            'subcategories' => $subcategories,
            'empresa' => $empresa,
            'isPersonalUser' => false
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // Super admin should not have access to files - redirect to their dashboard
        if ($user->isSuperAdmin()) {
            return redirect()->route('super_admin.dashboard')->with('info', 'Super admins não têm arquivos. Use o dashboard para gerenciar o sistema.');
        }

        $empresa = $user->empresa;

        // Validation rules vary by user type
        $validationRules = [
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000',
            'files' => 'required|array|min:1',
        ];

        // Personal users have different rules
        if ($user->isPersonalUser()) {
            $validationRules['files.*'] = 'required|file|max:10240'; // Allow any file type for personal users
        } else {
            // Company users have restricted file types
            $validationRules['category_id'] = 'required|exists:categories,id';
            $validationRules['subcategory_id'] = 'nullable|exists:subcategories,id';
            $validationRules['files.*'] = 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,zip,rar|max:10240';
        }

        $request->validate($validationRules);

        // Personal users don't need category validation
        if (!$user->isPersonalUser()) {
            // Verificar permissões para usuários de empresa
            $category = Category::findOrFail($request->category_id);
            
            // Verificar se pode fazer upload nesta categoria
            if ($user->role === 'normal_technician' && $user->category_id != $category->id) {
                abort(403, 'Você não tem permissão para enviar arquivos nesta categoria.');
            }
            
            if ($category->empresa_id !== $empresa->id) {
                abort(403, 'Categoria não pertence à sua empresa.');
            }

            // Verificar se a subcategoria pertence à categoria
            if ($request->filled('subcategory_id')) {
                $subcategory = Subcategory::findOrFail($request->subcategory_id);
                if ($subcategory->category_id != $request->category_id) {
                    abort(400, 'Subcategoria inválida para a categoria selecionada.');
                }
            }
        }

        // Upload dos arquivos
        $uploadedFiles = $request->file('files');
        $createdFiles = [];
        $errors = [];
        
        foreach ($uploadedFiles as $index => $uploadedFile) {
            try {
                $filePath = $uploadedFile->store('files', 'local');

                // Criar registro no banco para cada arquivo
                $fileData = [
                    'user_id' => $user->id,
                    'titulo' => count($uploadedFiles) === 1 ? $request->titulo : $request->titulo . ' (' . ($index + 1) . ')',
                    'descricao' => $request->descricao,
                    'file_path' => $filePath,
                    'original_name' => $uploadedFile->getClientOriginalName(),
                    'mime_type' => $uploadedFile->getMimeType(),
                    'size' => $uploadedFile->getSize(),
                ];

                // Add company and category fields only for company users
                if ($user->isPersonalUser()) {
                    $fileData['empresa_id'] = null;
                    $fileData['category_id'] = null;
                    $fileData['subcategory_id'] = null;
                } else {
                    $fileData['empresa_id'] = $empresa->id;
                    $fileData['category_id'] = $request->category_id;
                    $fileData['subcategory_id'] = $request->subcategory_id;
                }

                $file = File::create($fileData);
                
                $createdFiles[] = $file;
            } catch (\Exception $e) {
                $errors[] = "Erro ao processar arquivo '{$uploadedFile->getClientOriginalName()}': " . $e->getMessage();
            }
        }

        // Verificar se houve algum erro
        if (!empty($errors)) {
            // Se houve erros, deletar arquivos já criados
            foreach ($createdFiles as $file) {
                if (Storage::disk('local')->exists($file->file_path)) {
                    Storage::disk('local')->delete($file->file_path);
                }
                $file->delete();
            }
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['files' => 'Ocorreram erros durante o upload: ' . implode(', ', $errors)]);
        }

        $fileCount = count($createdFiles);
        $message = $fileCount === 1 ? 'Arquivo enviado com sucesso!' : "{$fileCount} arquivos enviados com sucesso!";

        return redirect()->route('files.index')
            ->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(File $file)
    {
        /** @var User $user */
        $user = Auth::user();

        // Super admin should not have access to files - redirect to their dashboard
        if ($user->isSuperAdmin()) {
            return redirect()->route('super_admin.dashboard')->with('info', 'Super admins não têm arquivos. Use o dashboard para gerenciar o sistema.');
        }

        // Check permissions based on user type
        if ($user->isPersonalUser()) {
            // Personal users can only see their own files
            if ($file->user_id !== $user->id) {
                abort(403);
            }
        } else {
            // Company users can see files from their company
            if ($file->empresa_id !== $user->empresa_id) {
                abort(403);
            }

            if ($user->role === 'normal_technician' && $file->category_id !== $user->category_id) {
                abort(403);
            }
        }

        return view('files.show', compact('file'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(File $file)
    {
        /** @var User $user */
        $user = Auth::user();

        // Super admin should not have access to files - redirect to their dashboard
        if ($user->isSuperAdmin()) {
            return redirect()->route('super_admin.dashboard')->with('info', 'Super admins não têm arquivos. Use o dashboard para gerenciar o sistema.');
        }

        // Only file owner or admin can edit
        if ($file->user_id !== $user->id && $user->role !== 'admin') {
            abort(403);
        }

        // Personal users don't need categories
        if ($user->isPersonalUser()) {
            return view('files.edit', [
                'file' => $file,
                'categories' => collect(),
                'subcategories' => collect(),
                'empresa' => null,
                'isPersonalUser' => true
            ]);
        }

        // Company users get categories based on their role
        $empresa = $user->empresa;
        
        if ($user->role === 'normal_technician') {
            $categories = collect([$user->category]);
            $subcategories = $user->category->subcategories ?? collect();
        } else {
            $categories = $empresa->categories;
            $subcategories = $file->category ? $file->category->subcategories : collect();
        }

        return view('files.edit', [
            'file' => $file,
            'categories' => $categories,
            'subcategories' => $subcategories,
            'empresa' => $empresa,
            'isPersonalUser' => false
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, File $file)
    {
        /** @var User $user */
        $user = Auth::user();

        // Super admin should not have access to files - redirect to their dashboard
        if ($user->isSuperAdmin()) {
            return redirect()->route('super_admin.dashboard')->with('info', 'Super admins não têm arquivos. Use o dashboard para gerenciar o sistema.');
        }

        // Only file owner or admin can edit
        if ($file->user_id !== $user->id && $user->role !== 'admin') {
            abort(403);
        }

        // Validation rules vary by user type
        $validationRules = [
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000',
        ];

        // Personal users don't need categories
        if (!$user->isPersonalUser()) {
            $validationRules['category_id'] = 'required|exists:categories,id';
            $validationRules['subcategory_id'] = 'nullable|exists:subcategories,id';
        }

        $request->validate($validationRules);

        // Personal users don't need category validation
        if (!$user->isPersonalUser()) {
            // Check category permissions for company users
            $category = Category::findOrFail($request->category_id);
            
            if ($user->role === 'normal_technician' && $user->category_id != $category->id) {
                abort(403, 'Você não tem permissão para mover arquivos para esta categoria.');
            }
        }

        // Update file data
        $updateData = ['titulo' => $request->titulo, 'descricao' => $request->descricao];
        
        if (!$user->isPersonalUser()) {
            $updateData['category_id'] = $request->category_id;
            $updateData['subcategory_id'] = $request->subcategory_id;
        }

        $file->update($updateData);

        return redirect()->route('files.show', $file)
            ->with('success', 'Arquivo atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        /** @var User $user */
        $user = Auth::user();

        // Super admin should not have access to files - redirect to their dashboard
        if ($user->isSuperAdmin()) {
            return redirect()->route('super_admin.dashboard')->with('info', 'Super admins não têm arquivos. Use o dashboard para gerenciar o sistema.');
        }

        // Apenas o dono do arquivo ou admin pode deletar
        if ($file->user_id !== $user->id && $user->role !== 'admin') {
            abort(403);
        }

        // Deletar arquivo físico
        if (Storage::disk('local')->exists($file->file_path)) {
            Storage::disk('local')->delete($file->file_path);
        }

        // Deletar registro
        $file->delete();

        return redirect()->route('files.index')
            ->with('success', 'Arquivo removido com sucesso!');
    }

    /**
     * Download do arquivo (apenas para admins e donos do arquivo)
     */
    public function download(File $file)
    {
        /** @var User $user */
        $user = Auth::user();

        // Super admin should not have access to files - redirect to their dashboard
        if ($user->isSuperAdmin()) {
            return redirect()->route('super_admin.dashboard')->with('info', 'Super admins não têm arquivos. Use o dashboard para gerenciar o sistema.');
        }

        // Check permissions based on user type
        if ($user->isPersonalUser()) {
            // Personal users can only download their own files
            if ($file->user_id !== $user->id) {
                abort(403);
            }
        } else {
            // Company users permission checks
            if ($file->empresa_id !== $user->empresa_id) {
                abort(403);
            }

            if ($user->role === 'normal_technician' && $file->category_id !== $user->category_id) {
                abort(403);
            }

            // Only admins and file owners can download
            if ($user->role !== 'admin' && $file->user_id !== $user->id) {
                abort(403, 'Apenas administradores e o dono do arquivo podem fazer download.');
            }
        }

        if (!Storage::disk('local')->exists($file->file_path)) {
            abort(404, 'Arquivo não encontrado.');
        }

        $fileContent = Storage::disk('local')->get($file->file_path);
        
        return response($fileContent, 200, [
            'Content-Type' => $file->mime_type,
            'Content-Disposition' => 'attachment; filename="' . $file->original_name . '"',
        ]);
    }

    /**
     * Visualizar arquivo no navegador (para todos os usuários autorizados)
     */
    public function view(File $file)
    {
        /** @var User $user */
        $user = Auth::user();

        // Super admin should not have access to files - redirect to their dashboard
        if ($user->isSuperAdmin()) {
            return redirect()->route('super_admin.dashboard')->with('info', 'Super admins não têm arquivos. Use o dashboard para gerenciar o sistema.');
        }

        // Check permissions based on user type
        if ($user->isPersonalUser()) {
            // Personal users can only view their own files
            if ($file->user_id !== $user->id) {
                abort(403);
            }
        } else {
            // Company users permission checks
            if ($file->empresa_id !== $user->empresa_id) {
                abort(403);
            }

            if ($user->role === 'normal_technician' && $file->category_id !== $user->category_id) {
                abort(403);
            }
        }

        if (!Storage::disk('local')->exists($file->file_path)) {
            abort(404, 'Arquivo não encontrado.');
        }

        $fileContent = Storage::disk('local')->get($file->file_path);
        
        return response($fileContent, 200, [
            'Content-Type' => $file->mime_type,
            'Content-Disposition' => 'inline; filename="' . $file->original_name . '"',
        ]);
    }

    /**
     * Get subcategories via AJAX
     */
    public function getSubcategories(Category $category)
    {
        $user = Auth::user();

        // Super admin should not have access to files - return error
        if ($user->isSuperAdmin()) {
            return response()->json(['error' => 'Super admins não têm acesso a arquivos'], 403);
        }

        // Verificar se o usuário pode acessar esta categoria
        if ($category->empresa_id !== $user->empresa_id) {
            abort(403);
        }

        if ($user->role === 'normal_technician' && $category->id !== $user->category_id) {
            abort(403);
        }

        return response()->json($category->subcategories);
    }
}
