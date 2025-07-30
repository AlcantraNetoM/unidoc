<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckTrialAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Super admin sempre tem acesso
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Verificar se a conta está aprovada
        // Permitir acesso se está aprovado OU se tem pagamento pendente durante o período de teste
        if (!$user->isApproved()) {
            // Se tem pagamento pendente e ainda está no período de teste, permitir acesso
            if ($user->isPending() && $user->payment_proof_path && $user->isInTrialPeriod()) {
                // Continuar para as próximas verificações
            } else {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Sua conta ainda não foi aprovada.');
            }
        }

        // Verificar se o período de teste expirou
        if ($user->isTrialExpired()) {
            // Permitir acesso às rotas de pagamento mesmo após expiração do trial
            $allowedRoutes = [
                'payment.required', 
                'payment.upload', 
                'payment.pending', 
                'trial.expired',
                'payments.multi_month',
                'payments.multi_month.process',
                'account.blocked',
                'payment.reactivate',
                'payments.status'
            ];
            
            if (in_array($request->route()->getName(), $allowedRoutes)) {
                return $next($request);
            }
            
            // Se não é uma rota permitida, redirecionar para página de pagamento
            return redirect()->route('payment.required')->with('error', 'Seu período de teste de 15 dias expirou. Realize o pagamento para continuar usando o sistema.');
        }

        // Para usuários de empresa, verificar também a empresa
        if ($user->isCompanyAccount() && $user->empresa) {
            if (!$user->empresa->isApproved()) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Sua empresa ainda não foi aprovada.');
            }

            if ($user->empresa->isTrialExpired()) {
                // Permitir acesso às rotas de pagamento mesmo após expiração do trial da empresa
                $allowedRoutes = [
                    'payment.required', 
                    'payment.upload', 
                    'payment.pending', 
                    'trial.expired',
                    'payments.multi_month',
                    'payments.multi_month.process',
                    'account.blocked',
                    'payment.reactivate',
                    'payments.status'
                ];
                
                if (in_array($request->route()->getName(), $allowedRoutes)) {
                    return $next($request);
                }
                
                // Se não é uma rota permitida, redirecionar para página de pagamento
                return redirect()->route('payment.required')->with('error', 'O período de teste da sua empresa expirou. Realize o pagamento para continuar usando o sistema.');
            }
        }

        return $next($request);
    }
}
