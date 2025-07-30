<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\ToolsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Landing page - página inicial de apresentação
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('landing');
});

// Nova tela inicial de apresentação
Route::get('/landing', [App\Http\Controllers\PlanController::class, 'landing'])->name('landing');

// Página Sobre - Portfólio do Vambert Capita
Route::get('/sobre', function () {
    return view('sobre');
})->name('sobre');

// ===================================================================
// FUNCIONALIDADES GRATUITAS (SEM LOGIN NECESSÁRIO)
// ===================================================================

// Editor Avançado de PDF
Route::get('/ferramentas/editar-pdf', function() {
    return view('tools.advanced-edit-pdf-final');
})->name('tools.edit-pdf');

Route::post('/tools/save-edited-pdf', [ToolsController::class, 'saveEditedPdf'])->name('tools.save-edited-pdf');

// Converter PDF
Route::get('/ferramentas/converter-pdf', [App\Http\Controllers\ToolsController::class, 'convertPdf'])->name('tools.convert-pdf');
Route::post('/ferramentas/converter-pdf', [App\Http\Controllers\ToolsController::class, 'processConvertPdf'])->name('tools.convert-pdf.process');

// Assinaturas Eletrônicas
Route::get('/ferramentas/assinaturas-eletronicas', [App\Http\Controllers\ToolsController::class, 'electronicSignature'])->name('tools.electronic-signature');
Route::post('/ferramentas/assinaturas-eletronicas', [App\Http\Controllers\ToolsController::class, 'processElectronicSignature'])->name('tools.electronic-signature.process');

// Rotas alternativas para compatibilidade com frontend
Route::get('/tools/electronic-signature', [App\Http\Controllers\ToolsController::class, 'electronicSignature']);
Route::post('/tools/electronic-signature', [App\Http\Controllers\ToolsController::class, 'processElectronicSignature']);

// Comprimir PDF
Route::get('/ferramentas/comprimir-pdf', [App\Http\Controllers\ToolsController::class, 'compressPdf'])->name('tools.compress-pdf');
Route::post('/ferramentas/comprimir-pdf', [App\Http\Controllers\ToolsController::class, 'processCompressPdf'])->name('tools.compress-pdf.process');

// Limpeza de arquivos temporários
Route::get('/temp/cleanup', [App\Http\Controllers\ToolsController::class, 'cleanupTemp'])->name('tools.cleanup');

// Seleção de plano
Route::get('/plan/{planType}', [App\Http\Controllers\PlanController::class, 'selectPlan'])->name('plan.select');

// Formulários de registro por plano
Route::get('/register/{planType}', [App\Http\Controllers\PlanController::class, 'showRegisterForm'])->name('register.plan');

// Processamento dos registros
Route::post('/register/empresa', [App\Http\Controllers\PlanController::class, 'registerEmpresa'])->name('register.store.empresa');
Route::post('/register/pessoal', [App\Http\Controllers\PlanController::class, 'registerPessoal'])->name('register.store.pessoal');

// Página de sucesso do registro
Route::get('/registration/success', [App\Http\Controllers\PlanController::class, 'registrationSuccess'])->name('registration.success');

// Visualização de comprovativo de pagamento (para admin)
Route::get('/payment-proof/{filename}', [App\Http\Controllers\PlanController::class, 'showPaymentProof'])->name('payment.proof.show')->middleware('auth');

// Página de período de teste expirado
Route::get('/trial-expired', [App\Http\Controllers\PlanController::class, 'trialExpired'])->name('trial.expired');



// ===================================================================
// ROTAS PÚBLICAS (SEM AUTENTICAÇÃO)
// ===================================================================

// Cadastro de empresa
Route::get('/empresa/cadastro', [EmpresaController::class, 'create'])->name('empresa.create');
Route::post('/empresa/cadastro', [EmpresaController::class, 'store'])->name('empresa.store');

// Logo da empresa (público para exibição)
Route::get('/empresa/{empresa}/logo', [EmpresaController::class, 'logo'])->name('empresa.logo');

// ===================================================================
// ROTAS AUTENTICADAS
// ===================================================================

// ===================================================================
// PAYMENT ROUTES (FORA DO MIDDLEWARE TRIAL PARA EVITAR CONFLITOS)
// ===================================================================

Route::middleware(['auth', 'verified'])->group(function () {
    // Payment required page
    Route::get('/payment-required', [App\Http\Controllers\PlanController::class, 'paymentRequired'])->name('payment.required');
    
    // Upload payment proof
    Route::post('/upload-payment-proof', [App\Http\Controllers\PlanController::class, 'uploadPaymentProof'])->name('payment.upload');
    
    // Payment pending page
    Route::get('/payment-pending', [App\Http\Controllers\PlanController::class, 'paymentPending'])->name('payment.pending');
    
    // ===================================================================
    // MULTI-MONTH PAYMENT SYSTEM ROUTES
    // ===================================================================
    
    // Account blocked page (for expired/suspended accounts)
    Route::get('/account-blocked', [PaymentController::class, 'accountBlocked'])->name('account.blocked');
    
    // Account reactivation payment
    Route::post('/payment/reactivate', [PaymentController::class, 'processReactivation'])->name('payment.reactivate');
    
    // Multi-month payment form
    Route::get('/payments/multi-month', [PaymentController::class, 'multiMonthForm'])->name('payments.multi_month');
    Route::post('/payments/multi-month', [PaymentController::class, 'processMultiMonth'])->name('payments.multi_month.process');
    
    // Payment status and history
    Route::get('/payments/status', [PaymentController::class, 'status'])->name('payments.status');
    Route::get('/payments/{payment}/status', [PaymentController::class, 'showPaymentStatus'])->name('payment.status');
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    
    // Download payment proof
    Route::get('/payments/{payment}/download', [PaymentController::class, 'downloadProof'])->name('payments.download');
});

Route::middleware(['auth', 'verified', 'account.status'])->group(function () {
    
    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // ===================================================================
    // GERENCIAMENTO DE ARQUIVOS
    // ===================================================================
    
    Route::resource('files', FileController::class);
    Route::get('/files/{file}/download', [FileController::class, 'download'])->name('files.download');
    Route::get('/files/{file}/view', [FileController::class, 'view'])->name('files.view');
    Route::get('/api/categories/{category}/subcategories', [FileController::class, 'getSubcategories'])->name('api.subcategories');
    
    // ===================================================================
    // GERENCIAMENTO DE CATEGORIAS (ADMIN APENAS)
    // ===================================================================
    
    Route::middleware('role:admin')->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('subcategories', SubcategoryController::class);
        
        // Gerenciamento de usuários
        Route::resource('users', UserController::class);
        
        // Rotas de aprovação de usuários (company admin)
        Route::post('/empresa/users/{user}/approve', [EmpresaController::class, 'approveUser'])->name('empresa.users.approve');
        Route::post('/empresa/users/{user}/reject', [EmpresaController::class, 'rejectUser'])->name('empresa.users.reject');
        Route::post('/empresa/users/{user}/assign-category', [EmpresaController::class, 'assignCategory'])->name('empresa.users.assign-category');
        Route::get('/empresa/users/{user}/edit', [EmpresaController::class, 'getUserData'])->name('empresa.users.get-data');
        
        // Gerenciamento da empresa
        Route::get('/empresa/settings', [EmpresaController::class, 'settings'])->name('empresa.settings');
        Route::get('/empresa/reports', [EmpresaController::class, 'reports'])->name('empresa.reports');
        Route::delete('/empresa/remove-logo', [EmpresaController::class, 'removeLogo'])->name('empresa.remove-logo');
        Route::get('/empresa/editar', [EmpresaController::class, 'edit'])->name('empresa.edit');
        Route::put('/empresa/atualizar', [EmpresaController::class, 'update'])->name('empresa.update');
        Route::get('/empresa/detalhes', [EmpresaController::class, 'show'])->name('empresa.show');
    });
    
    // ===================================================================
    // PERFIL DO USUÁRIO
    // ===================================================================
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ===================================================================
// MIDDLEWARE PERSONALIZADO PARA VERIFICAR PAPEL
// ===================================================================

// Criar middleware personalizado se não existir
if (!class_exists('App\Http\Middleware\CheckRole')) {
    Route::middleware(['auth', 'verified'])->group(function () {
        // Rotas de admin sem middleware personalizado por enquanto
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::resource('categories', CategoryController::class);
            Route::resource('subcategories', SubcategoryController::class);
            Route::resource('users', UserController::class);
        });
    });
}

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/super-admin/dashboard', [SuperAdminController::class, 'dashboard'])->name('super_admin.dashboard');
    Route::get('/super-admin/financial-dashboard', [SuperAdminController::class, 'financialDashboard'])->name('super_admin.financial_dashboard');
    Route::get('/super-admin/payments/all', [SuperAdminController::class, 'allPayments'])->name('super_admin.payments.all');
    
    // Página de aprovações pendentes
    Route::get('/super-admin/pending-approvals', [SuperAdminController::class, 'pendingApprovals'])->name('super_admin.pending_approvals');
    
    // ===================================================================
    // MULTI-MONTH PAYMENT APPROVAL ROUTES
    // ===================================================================
    
    // Payment approval dashboard
    Route::get('/super-admin/payments/pending', [PaymentController::class, 'pendingPayments'])->name('super_admin.payments.pending');
    
    // Approve/reject multi-month payments
    Route::post('/super-admin/payments/{payment}/approve', [PaymentController::class, 'approvePayment'])->name('super_admin.payments.approve');
    Route::post('/super-admin/payments/{payment}/reject', [PaymentController::class, 'rejectPayment'])->name('super_admin.payments.reject');
    
    // View payment proof
    Route::get('/super-admin/payments/{payment}/proof', [PaymentController::class, 'viewPaymentProof'])->name('super_admin.payments.proof');
    
    // Visualização de comprovativos de pagamento
    Route::get('/super-admin/payment-proof/{type}/{id}', [SuperAdminController::class, 'viewPaymentProof'])->name('super_admin.payment.proof');
    
    // Rotas para gerenciamento de empresas
    Route::get('/super-admin/companies', [SuperAdminController::class, 'indexCompanies'])->name('super_admin.companies.index');
    Route::get('/super-admin/companies/create', [SuperAdminController::class, 'createCompany'])->name('super_admin.companies.create');
    Route::post('/super-admin/companies', [SuperAdminController::class, 'storeCompany'])->name('super_admin.companies.store');
    Route::get('/super-admin/companies/{empresa}/edit', [SuperAdminController::class, 'editCompany'])->name('super_admin.companies.edit');
    Route::put('/super-admin/companies/{empresa}', [SuperAdminController::class, 'updateCompany'])->name('super_admin.companies.update');
    Route::delete('/super-admin/companies/{empresa}', [SuperAdminController::class, 'destroyCompany'])->name('super_admin.companies.destroy');
    
    // Outras rotas do super admin
    Route::get('/super-admin/users', [SuperAdminController::class, 'indexUsers'])->name('super_admin.users.index');
    Route::get('/super-admin/users/create', [SuperAdminController::class, 'createUser'])->name('super_admin.users.create');
    Route::get('/super-admin/users/assign', [SuperAdminController::class, 'showAssignPage'])->name('super_admin.users.assign.page');
    Route::post('/super-admin/users/assign', [SuperAdminController::class, 'assignUserToCompany'])->name('super_admin.users.assign');
    Route::post('/super-admin/users', [SuperAdminController::class, 'storeUser'])->name('super_admin.users.store');
    Route::get('/super-admin/users/{user}', [SuperAdminController::class, 'showUser'])->name('super_admin.users.show');
    Route::get('/super-admin/users/{user}/edit', [SuperAdminController::class, 'editUser'])->name('super_admin.users.edit');
    Route::put('/super-admin/users/{user}', [SuperAdminController::class, 'updateUser'])->name('super_admin.users.update');
    Route::delete('/super-admin/users/{user}', [SuperAdminController::class, 'destroyUser'])->name('super_admin.users.destroy');
    Route::post('/super-admin/users/{user}/approve', [SuperAdminController::class, 'approveUser'])->name('super_admin.users.approve');
    Route::post('/super-admin/users/{user}/reject', [SuperAdminController::class, 'rejectUser'])->name('super_admin.users.reject');
    
    // Rotas para aprovação de pagamentos após período de teste
    Route::post('/super-admin/users/{user}/approve-payment', [SuperAdminController::class, 'approvePayment'])->name('super_admin.users.approve_payment');
    Route::post('/super-admin/users/{user}/reject-payment', [SuperAdminController::class, 'rejectPayment'])->name('super_admin.users.reject_payment');
    
    // Rotas para novos pagamentos (sistema atual)
    Route::post('/super-admin/payments/{type}/{id}/approve', [SuperAdminController::class, 'approveNewPayment'])->name('super_admin.approve_payment');
    Route::post('/super-admin/payments/{type}/{id}/reject', [SuperAdminController::class, 'rejectNewPayment'])->name('super_admin.reject_payment');
    Route::get('/super-admin/payments/{type}/{id}/proof', [SuperAdminController::class, 'viewNewPaymentProof'])->name('super_admin.view_payment_proof');
    
    // Rotas de aprovação e rejeição de empresas
    Route::post('/super-admin/companies/{empresa}/approve', [SuperAdminController::class, 'approveCompany'])->name('super_admin.companies.approve');
    Route::post('/super-admin/companies/{empresa}/reject', [SuperAdminController::class, 'rejectCompany'])->name('super_admin.companies.reject');
    
    Route::get('/super-admin/categories/by-company/{empresa}', [SuperAdminController::class, 'getCategoriesByCompany'])->name('super_admin.categories.by_company');
    Route::get('/super-admin/settings', [SuperAdminController::class, 'settings'])->name('super_admin.settings');
    Route::post('/super-admin/settings', [SuperAdminController::class, 'updateSettings'])->name('super_admin.settings.update');
    Route::get('/super-admin/categories', [SuperAdminController::class, 'indexCategories'])->name('super_admin.categories.index');
    Route::get('/super-admin/categories/create', [SuperAdminController::class, 'createCategory'])->name('super_admin.categories.create');
    Route::post('/super-admin/categories', [SuperAdminController::class, 'storeCategory'])->name('super_admin.categories.store');
    Route::get('/super-admin/categories/{category}', [SuperAdminController::class, 'showCategory'])->name('super_admin.categories.show');
    Route::get('/super-admin/categories/{category}/edit', [SuperAdminController::class, 'editCategory'])->name('super_admin.categories.edit');
    Route::put('/super-admin/categories/{category}', [SuperAdminController::class, 'updateCategory'])->name('super_admin.categories.update');
    Route::delete('/super-admin/categories/{category}', [SuperAdminController::class, 'destroyCategory'])->name('super_admin.categories.destroy');
    Route::get('/super-admin/reports', [SuperAdminController::class, 'reports'])->name('super_admin.reports');
    Route::get('/super-admin/logs', [SuperAdminController::class, 'logs'])->name('super_admin.logs');
    
    // Audit Log Routes
    Route::get('/super-admin/audit-logs', [App\Http\Controllers\AuditLogController::class, 'index'])->name('super_admin.audit_logs.index');
    Route::get('/super-admin/audit-logs/{auditLog}', [App\Http\Controllers\AuditLogController::class, 'show'])->name('super_admin.audit_logs.show');
    Route::get('/super-admin/audit-logs/export/csv', [App\Http\Controllers\AuditLogController::class, 'export'])->name('super_admin.audit_logs.export');
    Route::post('/super-admin/audit-logs/delete-old', [App\Http\Controllers\AuditLogController::class, 'deleteOld'])->name('super_admin.audit_logs.delete-old');
});

require __DIR__.'/auth.php';
