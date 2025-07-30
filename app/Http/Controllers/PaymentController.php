<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use App\Models\Empresa;
use App\Models\AccountNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Mostrar página de pagamento multi-mês
     */
    public function showPaymentForm()
    {
        $user = Auth::user();
        
        // Verificar se o usuário pode fazer pagamentos
        if (!$user->canMakePayments()) {
            return redirect()->route('dashboard')->with('error', 'Você não tem permissão para fazer pagamentos.');
        }
        
        // Obter informações da conta
        $accountInfo = $this->getAccountInfo($user);
        
        return view('payments.multi-month', compact('user', 'accountInfo'));
    }

    /**
     * Processar pagamento multi-mês
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'months' => 'required|integer|min:1|max:12',
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $user = Auth::user();
        
        if (!$user->canMakePayments()) {
            return back()->with('error', 'Você não tem permissão para fazer pagamentos.');
        }

        // Verificar se não há pagamento pendente
        $pendingPayment = Payment::where('payment_type', $user->isPersonalUser() ? 'user' : 'empresa')
            ->where('entity_id', $user->isPersonalUser() ? $user->id : $user->empresa_id)
            ->where('status', 'pending')
            ->exists();

        if ($pendingPayment) {
            return back()->with('error', 'Já existe um pagamento pendente de aprovação.');
        }

        // Upload do comprovativo
        $proofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
        
        // Calcular valor
        $months = $request->months;
        $amount = Payment::calculateAmount($months, $user->isPersonalUser() ? 'personal' : 'company');
        
        // Criar registro de pagamento
        $payment = Payment::create([
            'payment_type' => $user->isPersonalUser() ? 'user' : 'empresa',
            'entity_id' => $user->isPersonalUser() ? $user->id : $user->empresa_id,
            'months_paid' => $months,
            'amount' => $amount,
            'payment_proof_path' => $proofPath,
            'status' => 'pending',
            'payment_date' => now(),
        ]);

        return redirect()->route('payment.status', $payment->id)
            ->with('success', "Pagamento de {$months} mês(es) submetido com sucesso! Aguarde a aprovação.");
    }

    /**
     * Processar pagamento multi-mês
     */
    public function processMultiMonth(Request $request)
    {
        $request->validate([
            'months' => 'required|integer|min:1|max:12',
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240'
        ]);

        $user = Auth::user();
        
        // Upload do comprovativo
        $proofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
        
        // Calcular valor
        $months = $request->months;
        $amount = Payment::calculateAmount($months, $user->isPersonalUser() ? 'personal' : 'company');
        
        // Criar registro de pagamento
        $payment = Payment::create([
            'payment_type' => $user->isPersonalUser() ? 'user' : 'empresa',
            'entity_id' => $user->isPersonalUser() ? $user->id : $user->empresa_id,
            'months_paid' => $months,
            'amount' => $amount,
            'payment_proof_path' => $proofPath,
            'status' => 'pending',
            'payment_date' => now(),
        ]);

        return redirect()->route('payments.status')
            ->with('success', "Pagamento de {$months} mês(es) submetido com sucesso! Aguarde a aprovação.");
    }

    /**
     * Mostrar status do pagamento
     */
    public function showPaymentStatus($paymentId)
    {
        $user = Auth::user();
        $payment = Payment::findOrFail($paymentId);
        
        // Verificar se o usuário pode ver este pagamento
        if (!$this->canViewPayment($user, $payment)) {
            abort(403);
        }
        
        return view('payments.status', compact('payment', 'user'));
    }

    /**
     * Mostrar status dos pagamentos do usuário
     */
    public function status()
    {
        $user = Auth::user();
        
        // Criar coleção para todos os pagamentos
        $allPayments = collect();
        
        // Obter pagamentos da nova tabela baseado no tipo de usuário
        if ($user->isPersonalUser()) {
            // Usuário pessoal - buscar pagamentos onde payment_type = 'user' e entity_id = user.id
            $newPayments = Payment::where('payment_type', 'user')
                ->where('entity_id', $user->id)
                ->latest()
                ->get();
        } else {
            // Usuário de empresa - buscar pagamentos onde payment_type = 'empresa' e entity_id = empresa.id
            $newPayments = Payment::where('payment_type', 'empresa')
                ->where('entity_id', $user->empresa_id)
                ->latest()
                ->get();
        }
        
        // Adicionar pagamentos da nova tabela
        foreach ($newPayments as $payment) {
            $allPayments->push((object)[
                'id' => $payment->id,
                'months_paid' => $payment->months_paid,
                'amount' => $payment->amount,
                'status' => $payment->status,
                'payment_date' => $payment->payment_date,
                'approved_at' => $payment->approved_at,
                'notes' => $payment->notes,
                'type' => 'new_system',
                'payment_proof_path' => $payment->payment_proof_path ?? null
            ]);
        }
        
        // Se há evidência de pagamento no sistema antigo, adicionar
        if ($user->payment_proof_path || $user->payment_processed_at) {
            $oldSystemStatus = 'pending';
            if ($user->approval_status === 'approved' && $user->has_paid) {
                $oldSystemStatus = 'approved';
            } elseif ($user->approval_status === 'rejected') {
                $oldSystemStatus = 'rejected';
            }
            
            $allPayments->push((object)[
                'id' => 'legacy-' . $user->id,
                'months_paid' => $user->months_paid ?? 1,
                'amount' => ($user->months_paid ?? 1) * ($user->isPersonalUser() ? 5000 : 15000),
                'status' => $oldSystemStatus,
                'payment_date' => $user->payment_processed_at ?? $user->created_at,
                'approved_at' => $user->approved_at,
                'notes' => 'Pagamento do sistema anterior',
                'type' => 'legacy_system',
                'payment_proof_path' => $user->payment_proof_path
            ]);
        }
        
        // Ordenar por data mais recente
        $payments = $allPayments->sortByDesc('payment_date');
        
        // Obter estatísticas
        $totalNewPayments = $newPayments->count();
        $stats = [
            'total_paid' => $user->total_months_paid ?? 0,
            'pending_payments' => $allPayments->where('status', 'pending')->count(),
            'approved_payments' => $allPayments->where('status', 'approved')->count(),
            'subscription_expires' => $user->subscription_end_date,
            'total_payments' => $allPayments->count()
        ];
        
        return view('payments.list', compact('user', 'payments', 'stats'));
    }

    /**
     * Listar pagamentos do usuário/empresa
     */
    public function listPayments()
    {
        $user = Auth::user();
        
        if ($user->isPersonalUser()) {
            $payments = Payment::where('payment_type', 'user')
                ->where('entity_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $payments = Payment::where('payment_type', 'empresa')
                ->where('entity_id', $user->empresa_id)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        return view('payments.list', compact('payments', 'user'));
    }

    /**
     * Listar histórico de pagamentos
     */
    public function index()
    {
        $user = Auth::user();
        
        // Obter todos os pagamentos do usuário
        $payments = $user->payments()->latest()->paginate(15);
        
        return view('payments.list', compact('user', 'payments'));
    }

    /**
     * Ver configurações de pagamento/conta
     */
    public function showAccountSettings()
    {
        $user = Auth::user();
        $accountInfo = $this->getAccountInfo($user);
        
        // Obter histórico de pagamentos
        if ($user->isPersonalUser()) {
            $payments = $user->payments()->approved()->orderBy('created_at', 'desc')->get();
        } else {
            $empresa = $user->empresa;
            $payments = $empresa->payments()->approved()->orderBy('created_at', 'desc')->get();
        }
        
        return view('payments.account-settings', compact('user', 'accountInfo', 'payments'));
    }

    /**
     * Super Admin - Listar pagamentos pendentes
     */
    public function pendingPayments()
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }
        
        $payments = Payment::with(['entity', 'approver'])
            ->pending()
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('super_admin.pending-payments', compact('payments'));
    }

    /**
     * Super Admin - Aprovar pagamento
     */
    public function approvePayment(Request $request, $paymentId)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }
        
        $payment = Payment::findOrFail($paymentId);
        
        if ($payment->status !== 'pending') {
            return back()->with('error', 'Este pagamento já foi processado.');
        }
        
        $payment->approve(Auth::id());
        
        // Criar notificação para a entidade
        if ($payment->payment_type === 'user') {
            $entity = User::find($payment->entity_id);
            AccountNotification::createPaymentApproved($entity, 'user', $payment->months_paid);
        } else {
            $entity = Empresa::find($payment->entity_id);
            AccountNotification::createPaymentApproved($entity, 'empresa', $payment->months_paid);
        }
        
        return back()->with('success', 'Pagamento aprovado com sucesso!');
    }

    /**
     * Super Admin - Rejeitar pagamento
     */
    public function rejectPayment(Request $request, $paymentId)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }
        
        $request->validate([
            'notes' => 'required|string|max:500'
        ]);
        
        $payment = Payment::findOrFail($paymentId);
        
        if ($payment->status !== 'pending') {
            return back()->with('error', 'Este pagamento já foi processado.');
        }
        
        $payment->status = 'rejected';
        $payment->notes = $request->notes;
        $payment->approved_by = Auth::id();
        $payment->approved_at = now();
        $payment->save();
        
        return back()->with('success', 'Pagamento rejeitado.');
    }

    /**
     * Download do comprovativo de pagamento
     */
    public function downloadProof($paymentId)
    {
        $user = Auth::user();
        $payment = Payment::findOrFail($paymentId);
        
        // Verificar permissões
        if (!$user->isSuperAdmin() && !$this->canViewPayment($user, $payment)) {
            abort(403);
        }
        
        if (!$payment->payment_proof_path || !Storage::disk('public')->exists($payment->payment_proof_path)) {
            abort(404, 'Comprovativo não encontrado.');
        }
        
        return response()->download(storage_path('app/public/' . $payment->payment_proof_path));
    }

    /**
     * Mostrar página de conta bloqueada
     */
    public function accountBlocked()
    {
        $user = Auth::user();
        
        // Verificar se realmente precisa estar nesta página
        if ($user->account_status === 'active' && $user->subscription_end_date && $user->subscription_end_date->isFuture()) {
            return redirect()->route('dashboard');
        }
        
        // Determinar tipo de conta e preço
        $isPersonalUser = $user->isPersonalUser();
        $monthlyPrice = $isPersonalUser ? 5000 : 15000;
        $accountType = $isPersonalUser ? 'personal' : 'company';
        
        // Obter último pagamento rejeitado (se houver)
        $lastRejectedPayment = $user->payments()
            ->where('status', 'rejected')
            ->latest()
            ->first();
        
        // Obter pagamento pendente (se houver)
        $pendingPayment = $user->payments()
            ->where('status', 'pending')
            ->latest()
            ->first();
        
        // Informações da entidade
        $entityInfo = [
            'type' => $accountType,
            'id' => $isPersonalUser ? $user->id : $user->empresa_id,
            'monthly_price' => $monthlyPrice,
            'account_type_label' => $isPersonalUser ? 'Conta Pessoal' : 'Conta Empresarial'
        ];
        
        return view('auth.account-blocked', compact(
            'user', 
            'lastRejectedPayment', 
            'pendingPayment', 
            'entityInfo',
            'monthlyPrice',
            'accountType'
        ));
    }

    /**
     * Processar pagamento de reativação
     */
    public function processReactivation(Request $request)
    {
        $request->validate([
            'entity_type' => 'required|in:personal,company',
            'entity_id' => 'required|integer',
            'months' => 'required|integer|min:1|max:12',
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120'
        ]);

        $user = Auth::user();
        
        // Upload do comprovativo
        $proofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
        
        // Calcular valor
        $months = $request->months;
        $amount = Payment::calculateAmount($months, $request->entity_type);
        
        // Criar registro de pagamento para reativação
        $payment = Payment::create([
            'payment_type' => $request->entity_type === 'personal' ? 'user' : 'empresa',
            'entity_id' => $request->entity_id,
            'months_paid' => $months,
            'amount' => $amount,
            'payment_proof_path' => $proofPath,
            'status' => 'pending',
            'payment_date' => now(),
            'notes' => 'Pagamento para reativação de conta suspensa'
        ]);

        return redirect()->route('account.blocked')
            ->with('success', "Comprovativo de reativação enviado com sucesso! Pagamento de {$months} mês(es) aguardando aprovação.");
    }

    /**
     * Verificar se o usuário pode ver o pagamento
     */
    private function canViewPayment($user, $payment)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        if ($payment->payment_type === 'user') {
            return $payment->entity_id === $user->id;
        } else {
            return $payment->entity_id === $user->empresa_id && $user->canMakePayments();
        }
    }

    /**
     * Obter informações da conta
     */
    private function getAccountInfo($user)
    {
        if ($user->isPersonalUser()) {
            return [
                'type' => 'personal',
                'entity' => $user,
                'name' => $user->name,
                'email' => $user->email,
                'status' => $user->getAccountStatusText(),
                'days_left' => $user->isAccountActive() ? $user->getDaysUntilExpiry() : $user->getTrialDaysRemaining(),
                'total_months_paid' => $user->total_months_paid ?? 0,
                'subscription_end' => $user->subscription_end_date,
            ];
        } else {
            $empresa = $user->empresa;
            return [
                'type' => 'company',
                'entity' => $empresa,
                'name' => $empresa->nome,
                'email' => $empresa->email,
                'status' => $empresa->getAccountStatusText(),
                'days_left' => $empresa->isAccountActive() ? $empresa->getDaysUntilExpiry() : $empresa->getTrialDaysRemaining(),
                'total_months_paid' => $empresa->total_months_paid ?? 0,
                'subscription_end' => $empresa->subscription_end_date,
            ];
        }
    }

    /**
     * Mostrar formulário de pagamento multi-mês
     */
    public function multiMonthForm()
    {
        $user = Auth::user();
        
        // Carregar relacionamento empresa se necessário
        if ($user->empresa_id && !$user->relationLoaded('empresa')) {
            $user->load('empresa');
        }
        
        // Verificar se o usuário pode fazer pagamentos
        if (!$user->canMakePayments()) {
            return redirect()->route('dashboard')
                ->with('error', 'Você não pode fazer pagamentos no momento.');
        }
        
        // Calcular preços por tipo de conta
        $personalPrice = 5000; // Kz por mês para conta pessoal
        $companyPrice = 15000; // Kz por mês para conta empresarial
        
        $isPersonalUser = $user->isPersonalUser();
        $monthlyPrice = $isPersonalUser ? $personalPrice : $companyPrice;
        
        // Informações da conta
        $accountInfo = [
            'type' => $isPersonalUser ? 'personal' : 'company',
            'name' => $user->name,
            'email' => $user->email,
            'company' => $isPersonalUser ? null : ($user->empresa ? $user->empresa->nome : 'N/A'),
            'monthly_price' => $monthlyPrice,
            'account_type_label' => $isPersonalUser ? 'Conta Pessoal' : 'Conta Empresarial',
            'status' => ucfirst($user->account_status ?? 'trial'),
            'total_months_paid' => $user->total_months_paid ?? 0,
            'subscription_expires' => $user->subscription_end_date ? $user->subscription_end_date->format('d/m/Y') : 'N/A'
        ];
        
        // Verificar se há pagamento pendente
        $pendingPayment = $user->payments()
            ->where('status', 'pending')
            ->latest()
            ->first();
        
        return view('payments.multi-month', compact(
            'user', 
            'monthlyPrice', 
            'isPersonalUser', 
            'pendingPayment',
            'personalPrice',
            'companyPrice',
            'accountInfo'
        ));
    }
}
