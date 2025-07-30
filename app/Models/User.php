<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $role
 * @property int|null $empresa_id
 * @property int|null $category_id
 * @property string $account_type
 * @property string $approval_status
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property int|null $approved_by
 * @property-read User|null $approvedBy
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\Empresa|null $empresa
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\File> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAccountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereApprovalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmpresaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Auditable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'empresa_id',
        'category_id',
        'account_type',
        'approval_status',
        'approved_at',
        'approved_by',
        'rejection_reason',
        'payment_proof_path',
        'trial_start_date',
        'trial_end_date',
        'payment_processed_at',
        'has_paid',
        'subscription_end_date',
        'account_status',
        'total_months_paid',
        'last_notification_sent',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'approved_at' => 'datetime',
            'trial_start_date' => 'datetime',
            'trial_end_date' => 'datetime',
            'payment_processed_at' => 'datetime',
            'subscription_end_date' => 'datetime',
            'last_notification_sent' => 'datetime',
        ];
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGeneralTechnician(): bool
    {
        return $this->role === 'general_technician';
    }

    public function isNormalTechnician(): bool
    {
        return $this->role === 'normal_technician';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isPersonalUser(): bool
    {
        return $this->role === 'personal_user';
    }

    public function isCompanyAdmin(): bool
    {
        return $this->role === 'company_admin';
    }

    public function isPersonalAccount(): bool
    {
        return $this->account_type === 'personal';
    }

    public function isCompanyAccount(): bool
    {
        return $this->account_type === 'company';
    }

    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->approval_status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->approval_status === 'rejected';
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function canUploadToCategory(Category $category): bool
    {
        if ($this->isAdmin() || $this->isGeneralTechnician()) {
            return $this->empresa_id === $category->empresa_id;
        }

        return $this->isNormalTechnician() && $this->category_id === $category->id;
    }

    public function isInTrialPeriod(): bool
    {
        return $this->trial_start_date && $this->trial_end_date && 
               now()->between($this->trial_start_date, $this->trial_end_date);
    }

    /**
     * Verificar se o período de teste expirou
     */
    public function isTrialExpired(): bool
    {
        // Se a conta está ativa (pagamento aprovado), não considera trial expirado
        if ($this->account_status === 'active' && $this->subscription_end_date && $this->subscription_end_date->isFuture()) {
            return false;
        }
        
        return $this->trial_end_date && now()->isAfter($this->trial_end_date);
    }

    public function hasValidAccess(): bool
    {
        return $this->isApproved() && !$this->isTrialExpired();
    }

    public function getTrialDaysRemaining(): int
    {
        if (!$this->trial_end_date) {
            return 0;
        }

        return max(0, now()->diffInDays($this->trial_end_date, false));
    }

    public function hasPendingPayment(): bool
    {
        return $this->approval_status === 'pending' && $this->payment_proof_path !== null;
    }

    public function needsPayment(): bool
    {
        return $this->isTrialExpired() && !$this->hasPendingPayment();
    }

    public function getTrialStatusMessage(): ?string
    {
        if (!$this->isInTrialPeriod() && !$this->isTrialExpired()) {
            return null;
        }

        if ($this->isTrialExpired()) {
            return 'Período de teste expirado. Pagamento necessário.';
        }

        $daysRemaining = $this->getTrialDaysRemaining();
        
        if ($daysRemaining <= 3) {
            return "Período de teste expira em {$daysRemaining} " . ($daysRemaining === 1 ? 'dia' : 'dias') . '. Prepare-se para realizar o pagamento.';
        }
        
        return "Você está no período de teste. Restam {$daysRemaining} " . ($daysRemaining === 1 ? 'dia' : 'dias') . '.';
    }

    public function canMakePayment(): bool
    {
        // Pode fazer pagamento se:
        // 1. Está no período de teste (independente de quantos dias restam)
        // 2. Período de teste expirou
        // 3. Não tem pagamento pendente já (exceto se foi rejeitado)
        // 4. Se foi rejeitado, pode tentar novamente
        
        $hasPendingPayment = $this->hasPendingPayment();
        $wasRejected = $this->isPaymentRejected();
        
        // Se foi rejeitado, pode tentar novamente
        if ($wasRejected) {
            return true;
        }
        
        // Se está no trial ou expirou E não tem pagamento pendente, pode fazer
        return ($this->isInTrialPeriod() || $this->isTrialExpired()) && !$hasPendingPayment;
    }

    /**
     * Obter mensagem de status do pagamento
     */
    public function getPaymentStatusMessage(): ?string
    {
        if ($this->isPaymentApproved()) {
            return '✅ Pagamento aprovado! Sua conta foi reativada com sucesso.';
        }
        
        if ($this->isPaymentRejected()) {
            $reason = $this->rejection_reason ?: 'Motivo não informado';
            return "❌ Pagamento rejeitado. Motivo: {$reason}. Você pode tentar novamente.";
        }
        
        if ($this->hasPendingPayment()) {
            return '⏳ Pagamento em análise. Aguarde a validação do administrador.';
        }
        
        return null;
    }

    /**
     * Verificar se o pagamento foi aprovado
     */
    public function isPaymentApproved(): bool
    {
        return $this->approval_status === 'approved' && 
               $this->payment_processed_at !== null && 
               $this->has_paid === true;
    }

    /**
     * Verificar se o pagamento foi rejeitado
     */
    public function isPaymentRejected(): bool
    {
        return $this->approval_status === 'rejected' && 
               $this->payment_processed_at !== null && 
               $this->rejection_reason !== null;
    }

    public function isPaymentPending(): bool
    {
        return $this->approval_status === 'pending' && $this->payment_proof_path !== null;
    }

    public function canRetryPayment(): bool
    {
        return $this->isPaymentRejected() && $this->trial_end_date && now()->isBefore($this->trial_end_date);
    }

    /**
     * Verificar se o usuário pode realizar pagamentos
     */
    public function canMakePayments(): bool
    {
        // Apenas admins de empresa e usuários de conta pessoal podem fazer pagamentos
        return $this->role === 'admin' || 
               $this->role === 'company_admin' || 
               $this->isPersonalUser();
    }

    /**
     * Relacionamento com pagamentos
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'entity_id')->where('payment_type', 'user');
    }

    /**
     * Relacionamento com notificações
     */
    public function accountNotifications()
    {
        return $this->morphMany(AccountNotification::class, 'notifiable');
    }

    /**
     * Verificar se a conta está ativa
     */
    public function isAccountActive(): bool
    {
        return $this->account_status === 'active' && 
               $this->subscription_end_date && 
               now()->isBefore($this->subscription_end_date);
    }

    /**
     * Verificar se a conta está expirada
     */
    public function isAccountExpired(): bool
    {
        return $this->account_status === 'expired' || 
               ($this->subscription_end_date && now()->isAfter($this->subscription_end_date));
    }

    /**
     * Verificar se a conta está suspensa
     */
    public function isAccountSuspended(): bool
    {
        return $this->account_status === 'suspended';
    }

    /**
     * Obter dias restantes da subscrição
     */
    public function getDaysUntilExpiry(): int
    {
        if (!$this->subscription_end_date) {
            return 0;
        }
        return max(0, now()->diffInDays($this->subscription_end_date, false));
    }

    /**
     * Verificar se precisa de notificação de expiração
     */
    public function needsExpiryNotification(): bool
    {
        $daysLeft = $this->getDaysUntilExpiry();
        $notificationDays = [30, 15, 7, 3, 1]; // Avisar nestes dias
        
        return in_array($daysLeft, $notificationDays) && 
               (!$this->last_notification_sent || 
                $this->last_notification_sent->diffInDays(now()) >= 1);
    }

    /**
     * Suspender conta por falta de pagamento
     */
    public function suspendAccount()
    {
        $this->account_status = 'suspended';
        $this->save();
        
        // Criar notificação
        AccountNotification::createAccountSuspended($this, 'user');
    }

    /**
     * Reativar conta
     */
    public function reactivateAccount($months = 1)
    {
        $this->account_status = 'active';
        if ($this->subscription_end_date && now()->isBefore($this->subscription_end_date)) {
            $this->subscription_end_date = $this->subscription_end_date->addMonths($months);
        } else {
            $this->subscription_end_date = now()->addMonths($months);
        }
        $this->total_months_paid += $months;
        $this->save();
    }

    /**
     * Verificar se pode fazer login
     */
    public function canLogin(): bool
    {
        // Super admin sempre pode
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        // Deve estar aprovado
        if (!$this->isApproved()) {
            return false;
        }
        
        // Não deve estar suspenso
        if ($this->isAccountSuspended()) {
            return false;
        }
        
        // Se tem pagamento pendente e ainda está no período de teste, pode logar
        if ($this->hasPendingPayment() && $this->isInTrialPeriod()) {
            return true;
        }
        
        // Se está no período de teste, pode logar
        if ($this->isInTrialPeriod()) {
            return true;
        }
        
        // Se tem assinatura ativa, pode logar
        if ($this->isAccountActive()) {
            return true;
        }
        
        return false;
    }

    /**
     * Obter status da conta para exibição
     */
    public function getAccountStatusText(): string
    {
        if ($this->isInTrialPeriod()) {
            $days = $this->getTrialDaysRemaining();
            return "Período de teste - {$days} dia(s) restante(s)";
        }
        
        if ($this->isAccountActive()) {
            $days = $this->getDaysUntilExpiry();
            return "Conta ativa - {$days} dia(s) restante(s)";
        }
        
        if ($this->isAccountExpired()) {
            return "Conta expirada - Pagamento necessário";
        }
        
        if ($this->isAccountSuspended()) {
            return "Conta suspensa - Entre em contato";
        }
        
        return "Status indefinido";
    }

    /**
     * Obter notificações não lidas
     */
    public function getUnreadNotifications()
    {
        return $this->accountNotifications()->unread()->latest()->get();
    }

    /**
     * Corrigir status da conta após aprovação de pagamento
     */
    public function fixAccountStatusAfterPaymentApproval()
    {
        // Se foi aprovado mas não tem account_status ou subscription_end_date corretos
        if ($this->isApproved() && $this->has_paid && $this->payment_processed_at) {
            $updates = [];
            
            // Se não tem account_status definido ou está incorreto
            if (!$this->account_status || $this->account_status === 'trial') {
                $updates['account_status'] = 'active';
            }
            
            // Se não tem subscription_end_date
            if (!$this->subscription_end_date) {
                $updates['subscription_end_date'] = $this->payment_processed_at->addMonth();
            }
            
            // Limpar dados de trial se ainda existem
            if ($this->trial_start_date || $this->trial_end_date) {
                $updates['trial_start_date'] = null;
                $updates['trial_end_date'] = null;
            }
            
            if (!empty($updates)) {
                $this->update($updates);
                return true;
            }
        }
        
        return false;
    }

    /**
     * Método para limpar trial após pagamento aprovado
     * Garante que todos os campos de trial sejam removidos
     */
    public function clearTrialAfterPayment()
    {
        $this->update([
            'trial_start_date' => null,
            'trial_end_date' => null,
            'account_status' => 'active',
            'has_paid' => true,
            'approval_status' => 'approved',
        ]);

        Log::info('Trial limpo após pagamento aprovado', [
            'user_id' => $this->id,
            'email' => $this->email,
            'empresa_id' => $this->empresa_id
        ]);
    }

    /**
     * Verificar se trial está limpo corretamente
     */
    public function isTrialCompletelyCleared(): bool
    {
        return is_null($this->trial_start_date) && 
               is_null($this->trial_end_date) &&
               $this->has_paid &&
               $this->approval_status === 'approved';
    }
}
