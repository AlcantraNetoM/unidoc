<?php

use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rotas públicas
Route::prefix('v1')->group(function () {
    // Autenticação
    Route::post('/login', [ApiController::class, 'login']);
    
    // Informações da aplicação
    Route::get('/app-info', function () {
        return response()->json([
            'app_name' => config('app.name'),
            'version' => '1.0.0',
            'description' => 'API do Sistema UNIDOC para aplicativos móveis',
            'status' => 'online',
            'timestamp' => now()->toISOString(),
            'endpoints' => [
                'auth' => [
                    'POST /api/v1/login' => 'Fazer login',
                    'POST /api/v1/logout' => 'Fazer logout',
                    'GET /api/v1/me' => 'Dados do usuário'
                ],
                'files' => [
                    'GET /api/v1/files' => 'Listar arquivos',
                    'POST /api/v1/files' => 'Upload de arquivos',
                    'GET /api/v1/files/{id}/download' => 'Download de arquivo',
                    'DELETE /api/v1/files/{id}' => 'Excluir arquivo'
                ],
                'categories' => [
                    'GET /api/v1/categories' => 'Listar categorias',
                    'GET /api/v1/categories/{id}/subcategories' => 'Subcategorias'
                ]
            ]
        ]);
    });
});

// Rotas protegidas (requer autenticação)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    
    // Autenticação
    Route::post('/logout', [ApiController::class, 'logout']);
    Route::get('/me', [ApiController::class, 'me']);
    
    // Dashboard
    Route::get('/dashboard', [ApiController::class, 'dashboard']);
    
    // Gestão de arquivos
    Route::get('/files', [ApiController::class, 'files']);
    Route::post('/files', [ApiController::class, 'uploadFile']);
    Route::get('/files/{id}/download', [ApiController::class, 'downloadFile']);
    Route::delete('/files/{id}', [ApiController::class, 'deleteFile']);
    
    // Categorias e subcategorias
    Route::get('/categories', [ApiController::class, 'categories']);
    Route::get('/categories/{id}/subcategories', [ApiController::class, 'subcategories']);
    
    // Perfil do usuário
    Route::put('/profile', [ApiController::class, 'updateProfile']);
    
});
