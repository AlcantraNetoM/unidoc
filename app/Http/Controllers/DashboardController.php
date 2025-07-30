<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Dashboard principal - redireciona baseado no papel do usuário
     */
    public function index()
    {
        $user = Auth::user();

        // Log para debug
        Log::info('Dashboard acessado', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'empresa_id' => $user->empresa_id
        ]);

        // Se é super admin, redireciona para dashboard específico
        if ($user->role === 'super_admin') {
            return redirect()->route('super_admin.dashboard');
        }

        // Se o usuário não tem empresa E não é usuário pessoal, mostra página de aguardando atribuição
        if (!$user->empresa_id && !$user->isPersonalUser()) {
            Log::info('Usuário sem empresa - mostrando página de aguardando atribuição', ['user_id' => $user->id]);
            return view('dashboard.awaiting-assignment', ['user' => $user]);
        }

        try {
            return match ($user->role) {
                'admin', 'company_admin' => $this->adminDashboard(),
                'general_technician' => $this->generalTechnicianDashboard(),
                'normal_technician' => $this->normalTechnicianDashboard(),
                'personal_user' => $this->personalUserDashboard(),
                default => abort(403, 'Role não reconhecido: ' . $user->role)
            };
        } catch (\Exception $e) {
            Log::error('Erro no dashboard', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return view('dashboard.error', [
                'error' => 'Erro ao carregar dashboard: ' . $e->getMessage(),
                'user' => $user
            ]);
        }
    }

    /**
     * Dashboard do Administrador
     */
    private function adminDashboard()
    {
        $user = Auth::user();
        $empresa = $user->empresa;

        // Se o admin não tem empresa, redireciona para cadastro de empresa
        if (!$empresa) {
            return redirect()->route('empresa.create')
                ->with('warning', 'Como administrador, você precisa primeiro registrar sua empresa para acessar o dashboard.');
        }

        // Verificar período de teste e adicionar notificação
        $trialNotification = $this->getTrialNotification($user);

        // Usuários pendentes de aprovação
        $usuariosPendentes = $empresa->users()
            ->where('approval_status', 'pending')
            ->latest()
            ->get();

        $stats = [
            'total_usuarios' => $empresa->users()->where('approval_status', 'approved')->count(),
            'usuarios_pendentes' => $usuariosPendentes->count(),
            'total_categorias' => $empresa->categories()->count(),
            'total_subcategorias' => $empresa->categories()
                ->withCount('subcategories')
                ->get()
                ->sum('subcategories_count'),
            'total_arquivos' => File::where('empresa_id', $empresa->id)->count(),
            'arquivos_hoje' => File::where('empresa_id', $empresa->id)
                ->whereDate('created_at', today())
                ->count(),
        ];

        $arquivos_recentes = File::where('empresa_id', $empresa->id)
            ->with(['user', 'category'])
            ->latest()
            ->limit(10)
            ->get();

        $categorias = $empresa->categories()
            ->withCount(['files', 'subcategories'])
            ->get();

        // Obter notificação sobre período de teste
        $trialNotification = $this->getTrialNotification($user);

        return view('dashboard.admin', compact('stats', 'arquivos_recentes', 'categorias', 'empresa', 'usuariosPendentes', 'trialNotification'));
    }

    /**
     * Dashboard do Técnico Geral
     */
    private function generalTechnicianDashboard()
    {
        $user = Auth::user();
        $empresa = $user->empresa;

        $stats = [
            'meus_arquivos' => File::where('user_id', $user->id)->count(),
            'arquivos_hoje' => File::where('user_id', $user->id)
                ->whereDate('created_at', today())
                ->count(),
            'categorias_disponiveis' => $empresa->categories()->count(),
        ];

        $meus_arquivos = File::where('user_id', $user->id)
            ->with(['category', 'subcategory'])
            ->latest()
            ->limit(15)
            ->get();

        $categorias = $empresa->categories()
            ->with('subcategories')
            ->get();

        // Obter notificação sobre período de teste
        $trialNotification = $this->getTrialNotification($user);

        return view('dashboard.general-technician', compact('stats', 'meus_arquivos', 'categorias', 'empresa', 'trialNotification'));
    }

    /**
     * Dashboard do Técnico Normal
     */
    private function normalTechnicianDashboard()
    {
        $user = Auth::user();
        $categoria = $user->category;

        if (!$categoria) {
            return view('dashboard.no-category', ['user' => $user]);
        }

        $stats = [
            'meus_arquivos' => File::where('user_id', $user->id)->count(),
            'arquivos_hoje' => File::where('user_id', $user->id)
                ->whereDate('created_at', today())
                ->count(),
            'categoria_nome' => $categoria->nome,
            'subcategorias_disponiveis' => $categoria->subcategories()->count(),
        ];

        $meus_arquivos = File::where('user_id', $user->id)
            ->with(['subcategory'])
            ->latest()
            ->limit(15)
            ->get();

        $subcategorias = $categoria->subcategories;

        // Obter notificação sobre período de teste
        $trialNotification = $this->getTrialNotification($user);

        return view('dashboard.normal-technician', compact('stats', 'meus_arquivos', 'subcategorias', 'categoria', 'user', 'trialNotification'));
    }

    /**
     * Dashboard do Usuário Pessoal
     */
    private function personalUserDashboard()
    {
        $user = Auth::user();

        // Get user's personal files
        $userFiles = File::where('user_id', $user->id)
            ->whereNull('empresa_id');

        // Calculate file statistics
        $totalFiles = $userFiles->count();
        $filesThisMonth = $userFiles->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Calculate storage used
        $totalSize = $userFiles->sum('size') ?? 0;
        $storageUsed = $this->formatFileSize($totalSize);

        // Get recent files
        $recentFiles = $userFiles->latest()
            ->limit(5)
            ->get();

        $stats = [
            'account_type' => 'Conta Pessoal',
            'status' => 'Ativo',
            'created_at' => $user->created_at->format('d/m/Y'),
            'total_files' => $totalFiles,
            'files_this_month' => $filesThisMonth,
            'storage_used' => $storageUsed,
        ];

        // Obter notificação sobre período de teste
        $trialNotification = $this->getTrialNotification($user);

        return view('dashboard.personal-user', compact('stats', 'user', 'recentFiles', 'trialNotification'));
    }

    /**
     * Format file size in human readable format
     */
    private function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return $bytes . ' byte';
        } else {
            return '0 bytes';
        }
    }

    /**
     * Obter notificação sobre período de teste e status de pagamento
     */
    private function getTrialNotification($user)
    {
        // Para usuários de empresa, verificar pagamentos da empresa
        if ($user->empresa_id && $user->empresa) {
            $empresa = $user->empresa;
            
            // Verificar pagamentos aprovados da empresa
            $approvedPayment = \App\Models\Payment::where('payment_type', 'empresa')
                ->where('entity_id', $empresa->id)
                ->where('status', 'approved')
                ->latest()
                ->first();
                
            if ($approvedPayment) {
                // Calcular data de expiração baseada no pagamento
                $endDate = $approvedPayment->created_at->addMonths($approvedPayment->months_paid);
                
                if ($endDate->isFuture()) {
                    $daysRemaining = now()->diffInDays($endDate);
                    
                    if ($daysRemaining <= 7) {
                        return [
                            'type' => 'warning',
                            'message' => "A assinatura da empresa expira em {$daysRemaining} dias. Renove agora para evitar interrupções.",
                            'days_remaining' => $daysRemaining,
                            'show_payment_button' => true,
                            'show_payment_history' => true
                        ];
                    } else {
                        return [
                            'type' => 'success',
                            'message' => "Conta da empresa ativa! Assinatura válida até " . $endDate->format('d/m/Y'),
                            'days_remaining' => $daysRemaining,
                            'show_payment_button' => false,
                            'show_payment_history' => true
                        ];
                    }
                }
            }
            
            // Verificar pagamentos pendentes da empresa
            $pendingPayment = \App\Models\Payment::where('payment_type', 'empresa')
                ->where('entity_id', $empresa->id)
                ->where('status', 'pending')
                ->latest()
                ->first();
                
            if ($pendingPayment) {
                return [
                    'type' => 'info',
                    'message' => "Pagamento da empresa de {$pendingPayment->months_paid} mês(es) (Kz " . number_format((float)$pendingPayment->amount, 0, ',', '.') . ") aguardando aprovação.",
                    'days_remaining' => 0,
                    'show_payment_button' => false,
                    'show_payment_history' => true
                ];
            }
            
            // Verificar pagamentos rejeitados da empresa
            $rejectedPayment = \App\Models\Payment::where('payment_type', 'empresa')
                ->where('entity_id', $empresa->id)
                ->where('status', 'rejected')
                ->latest()
                ->first();
                
            if ($rejectedPayment && $rejectedPayment->created_at->isAfter(now()->subDays(7))) {
                return [
                    'type' => 'danger',
                    'message' => 'O último pagamento da empresa foi rejeitado. Realize um novo pagamento para reativar a conta.',
                    'days_remaining' => 0,
                    'show_payment_button' => $user->role === 'company_admin' || $user->role === 'admin',
                    'show_payment_history' => true
                ];
            }
        }

        // Para usuários pessoais ou se não há empresa, usar a lógica anterior
        // Primeiro verificar se a conta está ativa através da nova tabela de pagamentos
        $approvedPayment = $user->payments()->where('status', 'approved')->latest()->first();
        if ($approvedPayment && $user->account_status === 'active') {
            // Se há um pagamento aprovado recente e a conta está ativa
            if ($user->subscription_end_date && $user->subscription_end_date->isFuture()) {
                $daysRemaining = now()->diffInDays($user->subscription_end_date);
                
                if ($daysRemaining <= 7) {
                    return [
                        'type' => 'warning',
                        'message' => "Sua assinatura expira em {$daysRemaining} dias. Renove agora para evitar interrupções.",
                        'days_remaining' => $daysRemaining,
                        'show_payment_button' => true,
                        'show_payment_history' => true
                    ];
                } else {
                    return [
                        'type' => 'success',
                        'message' => "Conta ativa! Assinatura válida até " . $user->subscription_end_date->format('d/m/Y'),
                        'days_remaining' => $daysRemaining,
                        'show_payment_button' => false,
                        'show_payment_history' => true
                    ];
                }
            }
        }

        // Verificar se a conta está suspensa (apenas para usuários pessoais)
        if ($user->account_status === 'suspended' && !$user->empresa_id) {
            return [
                'type' => 'danger',
                'message' => 'Sua conta foi suspensa. Realize um novo pagamento para reativar.',
                'days_remaining' => 0,
                'show_payment_button' => true,
                'show_payment_history' => true
            ];
        }

        // Verificar se há pagamentos pendentes (nova tabela)
        $pendingPayment = $user->payments()->where('status', 'pending')->latest()->first();
        if ($pendingPayment) {
            return [
                'type' => 'info',
                'message' => "Pagamento de {$pendingPayment->months_paid} mês(es) (Kz " . number_format((float)$pendingPayment->amount, 0, ',', '.') . ") aguardando aprovação.",
                'days_remaining' => 0,
                'show_payment_button' => false,
                'show_payment_history' => true
            ];
        }

        // Verificar se há pagamentos rejeitados recentes (nova tabela)
        $rejectedPayment = $user->payments()->where('status', 'rejected')->latest()->first();
        if ($rejectedPayment && $rejectedPayment->created_at->isAfter(now()->subDays(7))) {
            return [
                'type' => 'danger',
                'message' => 'Seu último pagamento foi rejeitado. Realize um novo pagamento para ativar sua conta.',
                'days_remaining' => 0,
                'show_payment_button' => true,
                'show_payment_history' => true
            ];
        }

        // Verificar se há pagamentos multi-mês ativos (sistema antigo)
        if ($user->months_paid > 0 && $user->subscription_expires_at && $user->subscription_expires_at->isFuture()) {
            $daysRemaining = now()->diffInDays($user->subscription_expires_at);
            
            if ($daysRemaining <= 7) {
                return [
                    'type' => 'warning',
                    'message' => "Sua assinatura expira em {$daysRemaining} dias. Renove agora para evitar interrupções.",
                    'days_remaining' => $daysRemaining,
                    'show_payment_button' => true,
                    'show_payment_history' => true
                ];
            } elseif ($daysRemaining <= 30) {
                return [
                    'type' => 'info',
                    'message' => "Sua assinatura expira em {$daysRemaining} dias. Considere renovar em breve.",
                    'days_remaining' => $daysRemaining,
                    'show_payment_button' => true,
                    'show_payment_history' => true
                ];
            } else {
                return [
                    'type' => 'success',
                    'message' => "Assinatura ativa. {$user->months_paid} meses pagos, expira em " . $user->subscription_expires_at->format('d/m/Y'),
                    'days_remaining' => $daysRemaining,
                    'show_payment_button' => false,
                    'show_payment_history' => true
                ];
            }
        }

        // Primeiro verificar se há notificação de pagamento (sistema antigo)
        // Só verificar se realmente houve tentativa de pagamento (payment_proof_path ou payment_processed_at)
        if ($user->payment_proof_path || $user->payment_processed_at) {
            $paymentMessage = $user->getPaymentStatusMessage();
            if ($paymentMessage) {
                if ($user->isPaymentApproved()) {
                    return [
                        'type' => 'success',
                        'message' => $paymentMessage,
                        'days_remaining' => 0,
                        'show_payment_button' => false
                    ];
                } elseif ($user->isPaymentRejected()) {
                    return [
                        'type' => 'danger',
                        'message' => $paymentMessage,
                        'days_remaining' => 0,
                        'show_retry_payment' => true
                    ];
                } elseif ($user->hasPendingPayment()) {
                    return [
                        'type' => 'info',
                        'message' => $paymentMessage,
                        'days_remaining' => 0,
                        'show_payment_button' => false
                    ];
                }
            }
        }

        // Se não há notificação de pagamento, verificar período de teste
        if (!$user->isInTrialPeriod()) {
            // Se o período de teste expirou e não há pagamento pendente
            if ($user->isTrialExpired() && !$user->hasPendingPayment()) {
                return [
                    'type' => 'warning',
                    'message' => 'Seu período de teste expirou. Realize o pagamento para continuar usando o sistema.',
                    'days_remaining' => 0,
                    'show_payment_button' => $user->canMakePayments()
                ];
            }
            return null;
        }

        $daysRemaining = $user->getTrialDaysRemaining();
        
        if ($daysRemaining <= 3) {
            return [
                'type' => 'warning',
                'message' => "Seu período de teste expira em {$daysRemaining} dias. Realize o pagamento para continuar usando o sistema.",
                'days_remaining' => $daysRemaining,
                'show_payment_button' => $user->canMakePayments()
            ];
        } elseif ($daysRemaining <= 7) {
            return [
                'type' => 'info',
                'message' => "Você está no período de teste. Restam {$daysRemaining} dias para expirar. Você pode realizar o pagamento antecipadamente se desejar.",
                'days_remaining' => $daysRemaining,
                'show_payment_button' => $user->canMakePayments()
            ];
        }

        return [
            'type' => 'success',
            'message' => "Você está no período de teste de 15 dias. Restam {$daysRemaining} dias. Você pode realizar o pagamento antecipadamente se desejar.",
            'days_remaining' => $daysRemaining,
            'show_payment_button' => $user->canMakePayments(),
            'show_payment_history' => true
        ];
    }
}
