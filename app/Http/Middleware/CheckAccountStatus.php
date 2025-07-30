<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if (!$user) {
            return $next($request);
        }
        
        // Super admin sempre pode acessar
        if ($user->isSuperAdmin()) {
            return $next($request);
        }
        
        // Verificar se é usuário pessoal
        if ($user->isPersonalUser()) {
            return $this->handlePersonalUser($user, $request, $next);
        }
        
        // Verificar se é usuário de empresa
        if ($user->empresa_id) {
            return $this->handleCompanyUser($user, $request, $next);
        }
        
        return $next($request);
    }
    
    /**
     * Verificar usuário pessoal
     */
    private function handlePersonalUser($user, $request, $next)
    {
        // Rotas permitidas para qualquer estado da conta
        $allowedRoutes = [
            'payment.required', 
            'payment.upload', 
            'payment.pending', 
            'trial.expired',
            'payments.multi_month',
            'payments.multi_month.process',
            'account.blocked',
            'payment.reactivate',
            'payments.status',
            'logout'
        ];
        
        if (in_array($request->route()->getName(), $allowedRoutes)) {
            return $next($request);
        }
        
        // Se está no período de trial, sempre permitir acesso (independente de aprovação)
        if ($user->isInTrialPeriod()) {
            return $next($request);
        }
        
        // Se a conta está ativa (pagamento aprovado), permitir acesso
        if ($user->isAccountActive()) {
            return $next($request);
        }
        
        // Se tem pagamento pendente e ainda pode estar esperando aprovação, permitir acesso limitado
        if ($user->hasPendingPayment() && $user->isPending()) {
            return $next($request);
        }
        
        // Se o período de teste expirou, redirecionar para pagamento (ANTES de verificar aprovação)
        if ($user->isTrialExpired()) {
            // Se pode fazer pagamento, permitir acesso às rotas de pagamento
            if ($user->canMakePayment()) {
                return redirect()->route('payment.required')->with('error', 'Seu período de teste de 15 dias expirou. Realize o pagamento para continuar usando o sistema.');
            }
            // Se não pode fazer pagamento, bloquear acesso
            return $this->redirectToBlockedAccount($user, 'Período de teste expirado. Entre em contato com o suporte.');
        }
        
        // Se não está aprovado e não está em nenhuma condição especial acima
        if (!$user->isApproved()) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Sua conta ainda não foi aprovada.');
        }
        
        // Se a conta está suspensa
        if ($user->isAccountSuspended()) {
            return $this->redirectToBlockedAccount($user, 'Sua conta pessoal foi suspensa por falta de pagamento.');
        }
        
        // Se a conta expirou
        if ($user->isAccountExpired()) {
            return $this->redirectToBlockedAccount($user, 'Sua conta pessoal expirou. Faça um novo pagamento para reativar.');
        }
        
        // Se não pode fazer login por qualquer motivo, permitir mesmo assim se puder fazer pagamento
        if (!$user->canLogin()) {
            if ($user->canMakePayment()) {
                return redirect()->route('payment.required')->with('error', 'Realize o pagamento para acessar o sistema.');
            }
            return $this->redirectToBlockedAccount($user, 'Acesso negado. Entre em contato com o suporte.');
        }
        
        return $next($request);
    }
    
    /**
     * Verificar usuário de empresa
     */
    private function handleCompanyUser($user, $request, $next)
    {
        $empresa = $user->empresa;
        
        if (!$empresa) {
            return redirect()->route('login')->with('error', 'Empresa não encontrada.');
        }
        
        // Rotas permitidas para qualquer estado da conta de empresa
        $allowedRoutes = [
            'payment.required', 
            'payment.upload', 
            'payment.pending', 
            'trial.expired',
            'payments.multi_month',
            'payments.multi_month.process',
            'account.blocked',
            'payment.reactivate',
            'payments.status',
            'logout'
        ];
        
        if (in_array($request->route()->getName(), $allowedRoutes)) {
            return $next($request);
        }
        
        // Se a empresa está no período de trial, sempre permitir acesso
        if ($empresa->isInTrialPeriod()) {
            return $next($request);
        }
        
        // Se tem pagamento pendente para a empresa, permitir acesso limitado
        if ($empresa->hasPendingPayment && method_exists($empresa, 'hasPendingPayment') && $empresa->hasPendingPayment()) {
            return $next($request);
        }
        
        // Se o período de teste da empresa expirou, redirecionar para pagamento (ANTES de verificar aprovação)
        if ($empresa->isTrialExpired()) {
            // Se o usuário pode fazer pagamento para a empresa, permitir acesso às rotas de pagamento
            if ($user->canMakePayments()) {
                return redirect()->route('payment.required')->with('error', 'O período de teste da sua empresa expirou. Realize o pagamento para continuar usando o sistema.');
            }
            // Se não pode fazer pagamento, bloquear acesso
            return $this->redirectToBlockedAccount($empresa, 'Período de teste da empresa expirado. Entre em contato com o administrador.');
        }
        
        // Verificar se o usuário está aprovado
        if (!$user->isApproved()) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Sua conta ainda não foi aprovada.');
        }
        
        // Verificar se a empresa está aprovada
        if (!$empresa->isApproved()) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Sua empresa ainda não foi aprovada.');
        }
        
        // Se a empresa está suspensa (mas não em período de trial)
        if ($empresa->isAccountSuspended()) {
            return $this->redirectToBlockedAccount($empresa, 'A conta da sua empresa foi suspensa por falta de pagamento.');
        }
        
        // Se a conta da empresa expirou (mas não em período de trial)
        if ($empresa->isAccountExpired()) {
            return $this->redirectToBlockedAccount($empresa, 'A conta da sua empresa expirou. O administrador deve fazer um novo pagamento para reativar.');
        }
        
        // Se a empresa não pode ter usuários logados
        if (!$empresa->canUsersLogin()) {
            return $this->redirectToBlockedAccount($empresa, 'Acesso negado. Entre em contato com o administrador da empresa.');
        }
        
        return $next($request);
    }
    
    /**
     * Redirecionar para página de conta bloqueada
     */
    private function redirectToBlockedAccount($entity, $message)
    {
        // Se for uma requisição AJAX, retornar JSON
        if (request()->expectsJson()) {
            return response()->json([
                'error' => 'Account blocked',
                'message' => $message,
                'redirect' => route('account.blocked')
            ], 403);
        }
        
        // Logout do usuário
        Auth::logout();
        
        // Redirecionar para página de conta bloqueada com informações
        return redirect()->route('account.blocked')
            ->with('blocked_message', $message)
            ->with('entity_type', $entity instanceof \App\Models\User ? 'user' : 'empresa')
            ->with('entity_id', $entity->id);
    }
}
