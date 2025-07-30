<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PasswordResetCodeController extends Controller
{
    /**
     * Mostrar formulário para solicitar código de reset
     */
    public function create(): View
    {
        return view('auth.forgot-password-code');
    }

    /**
     * Enviar código de reset por email ou SMS
     */
    public function sendCode(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'contact' => ['required', 'string'],
            'contact_type' => ['required', 'in:email,phone'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $contactType = $request->contact_type;
        $contact = $request->contact;

        // Verificar se o usuário existe
        $user = null;
        if ($contactType === 'email') {
            $user = User::where('email', $contact)->first();
        } else {
            $user = User::where('phone', $contact)->first();
        }

        if (!$user) {
            return back()->withErrors([
                'contact' => 'Não foi encontrada nenhuma conta com este ' . ($contactType === 'email' ? 'email' : 'número de telefone') . '. Verifique os dados e tente novamente.'
            ])->withInput();
        }

        // Limpar códigos antigos para este usuário
        if ($contactType === 'email') {
            PasswordResetCode::where('email', $contact)->delete();
        } else {
            PasswordResetCode::where('phone', $contact)->delete();
        }

        // Gerar novo código
        $code = PasswordResetCode::generateCode();
        
        // Salvar código no banco
        $resetCode = PasswordResetCode::create([
            $contactType => $contact,
            'code' => $code,
            'expires_at' => now()->addMinutes(15), // Código válido por 15 minutos
        ]);

        // Enviar código
        if ($contactType === 'email') {
            $this->sendEmailCode($contact, $code, $user->name);
        } else {
            $this->sendSMSCode($contact, $code, $user->name);
        }

        return redirect()->route('password.verify.form')->with([
            'contact' => $contact,
            'contact_type' => $contactType,
            'message' => 'Código de verificação enviado com sucesso! Verifique seu ' . ($contactType === 'email' ? 'email' : 'telefone') . '.'
        ]);
    }

    /**
     * Mostrar formulário para verificar código
     */
    public function showVerifyForm()
    {
        if (!session('contact')) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-reset-code');
    }

    /**
     * Verificar código inserido pelo usuário
     */
    public function verifyCode(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'code' => ['required', 'string', 'size:8'],
            'contact' => ['required', 'string'],
            'contact_type' => ['required', 'in:email,phone'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $contactType = $request->contact_type;
        $contact = $request->contact;
        $code = $request->code;

        // Buscar código válido
        $resetCode = PasswordResetCode::where($contactType, $contact)
            ->where('code', $code)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$resetCode) {
            return back()->withErrors([
                'code' => 'Código inválido ou expirado. Por favor, solicite um novo código.'
            ])->withInput();
        }

        // Marcar código como usado
        $resetCode->markAsUsed();

        // Redirecionar para formulário de nova senha
        return redirect()->route('password.reset.form')->with([
            'contact' => $contact,
            'contact_type' => $contactType,
            'verified' => true
        ]);
    }

    /**
     * Mostrar formulário para definir nova senha
     */
    public function showResetForm()
    {
        if (!session('verified')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password-new');
    }

    /**
     * Salvar nova senha
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        if (!session('verified')) {
            return redirect()->route('password.request');
        }

        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'contact' => ['required', 'string'],
            'contact_type' => ['required', 'in:email,phone'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $contactType = $request->contact_type;
        $contact = $request->contact;

        // Encontrar usuário
        $user = null;
        if ($contactType === 'email') {
            $user = User::where('email', $contact)->first();
        } else {
            $user = User::where('phone', $contact)->first();
        }

        if (!$user) {
            return back()->withErrors([
                'contact' => 'Usuário não encontrado.'
            ]);
        }

        // Atualizar senha
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Limpar sessão
        session()->forget(['contact', 'contact_type', 'verified']);

        return redirect()->route('login')->with('status', 'Senha alterada com sucesso! Faça login com sua nova senha.');
    }

    /**
     * Enviar código por email
     */
    private function sendEmailCode(string $email, string $code, string $name): void
    {
        try {
            Mail::send('emails.password-reset-code', [
                'code' => $code,
                'name' => $name,
                'expires_in' => 15
            ], function ($message) use ($email) {
                $message->to($email)
                    ->subject('Código de Recuperação de Senha');
            });

            Log::info("Email de recuperação enviado para: {$email}");
        } catch (\Exception $e) {
            Log::error('Erro ao enviar email de reset: ' . $e->getMessage());
        }
    }

    /**
     * Enviar código por SMS (implementação básica)
     */
    private function sendSMSCode(string $phone, string $code, string $name): void
    {
        // Aqui você implementaria a integração com um serviço de SMS
        // Por enquanto, vamos apenas logar o código
        Log::info("Código SMS para {$phone}: {$code}");
        
        // Exemplo de implementação com Twilio ou outro serviço:
        /*
        try {
            $twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));
            
            $twilio->messages->create($phone, [
                'from' => config('services.twilio.from'),
                'body' => "Olá {$name}, seu código de recuperação de senha é: {$code}. Válido por 15 minutos."
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar SMS: ' . $e->getMessage());
        }
        */
    }
}
