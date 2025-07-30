<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class PlanController extends Controller
{
    /**
     * Show the landing page
     */
    public function landing()
    {
        return view('landing');
    }

    /**
     * Show plan selection page
     */
    public function selectPlan($planType)
    {
        if (!in_array($planType, ['empresa', 'pessoal'])) {
            abort(404);
        }

        return view('plan-selection', compact('planType'));
    }

    /**
     * Show registration form for selected plan
     */
    public function showRegisterForm($planType)
    {
        if (!in_array($planType, ['empresa', 'pessoal'])) {
            abort(404);
        }

        // Criar um ViewErrorBag vazio para evitar erros
        $errors = session()->get('errors', new \Illuminate\Support\ViewErrorBag());

        if ($planType === 'empresa') {
            return view('auth.register-empresa', compact('errors'));
        } else {
            return view('auth.register-pessoal', compact('errors'));
        }
    }

    /**
     * Handle empresa registration
     */
    public function registerEmpresa(Request $request)
    {
        $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'admin_name' => ['required', 'string', 'max:255'],
            'endereco' => ['required', 'string'],
            'password' => [
                'required', 
                'confirmed', 
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
                function ($attribute, $value, $fail) {
                    // Lista de senhas comuns para rejeitar
                    $commonPasswords = [
                        'password', '123456', '123456789', 'qwerty', 'abc123', 'monkey',
                        '1234567890', 'letmein', 'trustno1', 'dragon', 'baseball', 'iloveyou',
                        'senha', 'admin', 'administrador', '123123', 'password123', 'senha123'
                    ];
                    
                    if (in_array(strtolower($value), $commonPasswords)) {
                        $fail('A senha escolhida é muito comum. Por favor, escolha uma senha mais segura.');
                    }
                },
            ],
            'terms' => ['required', 'accepted'],
        ], [
            'company_name.required' => 'O nome da empresa é obrigatório.',
            'email.required' => 'O email é obrigatório.',
            'email.unique' => 'Este email já está em uso.',
            'admin_name.required' => 'O nome do administrador é obrigatório.',
            'endereco.required' => 'O endereço da empresa é obrigatório.',
            'password.required' => 'A palavra-passe é obrigatória.',
            'password.confirmed' => 'A confirmação da palavra-passe não confere.',
            'password.min' => 'A palavra-passe deve ter pelo menos 8 caracteres.',
            'password.regex' => 'A palavra-passe deve conter pelo menos: 1 letra minúscula, 1 maiúscula, 1 número e 1 caractere especial (@$!%*?&).',
            'terms.accepted' => 'Deve aceitar os termos e condições.',
        ]);

        DB::beginTransaction();

        try {
            // Create empresa
            $empresa = Empresa::create([
                'nome' => $request->company_name,
                'email' => $request->email,
                'endereco' => $request->endereco,
                'approval_status' => 'pending',
                'registration_date' => now(),
            ]);

            // Create admin user
            $user = User::create([
                'name' => $request->admin_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'company_admin',
                'empresa_id' => $empresa->id,
                'account_type' => 'company',
                'approval_status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('registration.success')->with([
                'success' => 'Registro empresarial realizado com sucesso!',
                'message' => 'Sua conta será ativada pelo administrador. Você receberá 15 dias gratuitos para testar o sistema após a aprovação.',
                'account_type' => 'empresa'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Erro ao processar registro. Tente novamente.'])->withInput();
        }
    }

    /**
     * Handle personal registration
     */
    public function registerPessoal(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => [
                'required', 
                'confirmed', 
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
                function ($attribute, $value, $fail) {
                    // Lista de senhas comuns para rejeitar
                    $commonPasswords = [
                        'password', '123456', '123456789', 'qwerty', 'abc123', 'monkey',
                        '1234567890', 'letmein', 'trustno1', 'dragon', 'baseball', 'iloveyou',
                        'senha', 'admin', 'administrador', '123123', 'password123', 'senha123'
                    ];
                    
                    if (in_array(strtolower($value), $commonPasswords)) {
                        $fail('A senha escolhida é muito comum. Por favor, escolha uma senha mais segura.');
                    }
                },
            ],
            'terms' => ['required', 'accepted'],
        ], [
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'O email é obrigatório.',
            'email.unique' => 'Este email já está em uso.',
            'password.required' => 'A palavra-passe é obrigatória.',
            'password.confirmed' => 'A confirmação da palavra-passe não confere.',
            'password.min' => 'A palavra-passe deve ter pelo menos 8 caracteres.',
            'password.regex' => 'A palavra-passe deve conter pelo menos: 1 letra minúscula, 1 maiúscula, 1 número e 1 caractere especial (@$!%*?&).',
            'terms.accepted' => 'Deve aceitar os termos e condições.',
        ]);

        try {
            // Create personal user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'personal_user',
                'account_type' => 'personal',
                'approval_status' => 'pending',
            ]);

            return redirect()->route('registration.success')->with([
                'success' => 'Registro pessoal realizado com sucesso!',
                'message' => 'Sua conta será ativada pelo administrador. Você receberá 15 dias gratuitos para testar o sistema após a aprovação.',
                'account_type' => 'pessoal'
            ]);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao processar registro. Tente novamente.'])->withInput();
        }
    }

    /**
     * Show registration success page
     */
    public function registrationSuccess()
    {
        if (!session('success')) {
            return redirect()->route('landing');
        }

        return view('auth.registration-success');
    }

    /**
     * Show payment proof file (for admin validation)
     */
    public function showPaymentProof($filename)
    {
        $path = 'payment-proofs/' . $filename;
        
        if (!Storage::disk('private')->exists($path)) {
            abort(404);
        }

        return response()->file(Storage::disk('private')->path($path));
    }

    /**
     * Show trial expired page
     */
    public function trialExpired()
    {
        return view('auth.trial-expired');
    }

    /**
     * Show payment required page
     */
    public function paymentRequired()
    {
        $user = Auth::user();
        
        // Se o usuário não pode fazer pagamentos, mostrar página com informação
        if (!$user->canMakePayments()) {
            return view('auth.payment-required', compact('user'))
                ->with('error', 'Você não tem permissão para realizar pagamentos. Entre em contato com o administrador da sua empresa.');
        }
        
        return view('auth.payment-required', compact('user'));
    }

    /**
     * Upload payment proof after trial period
     */
    public function uploadPaymentProof(Request $request)
    {
        $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120' // 5MB
        ]);

        $user = Auth::user();
        
        // Verificar se o usuário pode fazer pagamentos
        if (!$user->canMakePayments()) {
            return back()->with('error', 'Você não tem permissão para realizar pagamentos.');
        }

        try {
            // Upload do arquivo
            $file = $request->file('payment_proof');
            $filename = 'payment_proof_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('payment_proofs', $filename, 'private');

            // Atualizar usuário - sempre colocar como pendente quando há comprovativo de pagamento
            $updateData = [
                'payment_proof_path' => $path,
                'approval_status' => 'pending', // Sempre pendente para validação do super admin
                'updated_at' => now()
            ];
            
            $user->update($updateData);

            return redirect()->route('payment.pending')
                ->with('success', 'Comprovativo de pagamento enviado com sucesso! Aguarde a validação do administrador.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao enviar comprovativo: ' . $e->getMessage());
        }
    }

    /**
     * Show payment pending approval page
     */
    public function paymentPending()
    {
        $user = Auth::user();
        
        // Verificar se o usuário pode fazer pagamentos
        if (!$user->canMakePayments()) {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para acessar esta página.');
        }
        
        return view('auth.payment-pending', compact('user'));
    }
}
