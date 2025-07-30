<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Empresa;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $empresas = Empresa::approved()->get();
        return view('auth.register', compact('empresas'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validationRules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'account_type' => ['required', 'in:personal,company,new_company'],
            'empresa_id' => ['nullable', 'exists:empresas,id', 'required_if:account_type,company'],
        ];

        // Add validation for new company fields
        if ($request->account_type === 'new_company') {
            $validationRules['company_nome'] = ['required', 'string', 'max:255'];
            $validationRules['company_email'] = ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:empresas,email'];
            $validationRules['company_endereco'] = ['required', 'string'];
        }

        $request->validate($validationRules);

        return DB::transaction(function () use ($request) {
            $empresaId = null;

            // Handle new company creation
            if ($request->account_type === 'new_company') {
                $empresa = Empresa::create([
                    'nome' => $request->company_nome,
                    'email' => $request->company_email,
                    'endereco' => $request->company_endereco,
                    'approval_status' => 'pending',
                ]);
                $empresaId = $empresa->id;
            } elseif ($request->account_type === 'company') {
                $empresaId = $request->empresa_id;
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->account_type === 'personal' ? 'personal_user' : 
                         ($request->account_type === 'new_company' ? 'company_admin' : 'normal_technician'),
                'account_type' => $request->account_type,
                'empresa_id' => $empresaId,
                'approval_status' => 'pending',
                // Período de teste será iniciado automaticamente após aprovação pelo admin
            ]);

            event(new Registered($user));

            // Different messages based on account type
            $message = $request->account_type === 'new_company' 
                ? 'Conta e empresa criadas com sucesso! Aguarde aprovação do super administrador para ativar sua empresa e sua conta.'
                : 'Conta criada com sucesso! Aguarde aprovação do administrador.';

            return redirect()->route('account.pending')->with('success', $message);
        });
    }
}
