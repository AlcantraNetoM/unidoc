<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            
            // Super admin sempre pode acessar
            if ($user->isSuperAdmin()) {
                return $next($request);
            }
            
            // Verificar se a conta está aprovada
            if ($user->approval_status === 'pending') {
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'email' => 'Sua conta ainda está aguardando aprovação do administrador.'
                ]);
            }
            
            if ($user->approval_status === 'rejected') {
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'email' => 'Sua conta foi rejeitada pelo administrador.'
                ]);
            }

            // Verificar se a empresa do usuário está aprovada (para usuários de empresa)
            if ($user->empresa && $user->empresa->approval_status === 'pending') {
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'email' => 'A empresa associada à sua conta ainda está aguardando aprovação.'
                ]);
            }

            if ($user->empresa && $user->empresa->approval_status === 'rejected') {
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'email' => 'A empresa associada à sua conta foi rejeitada.'
                ]);
            }
        }
        
        return $next($request);
    }
}
