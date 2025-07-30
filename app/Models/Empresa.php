<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/**
 * 
 *
 * @property int $id
 * @property string $nome
 * @property string|null $logo_path
 * @property string|null $primary_color
 * @property string|null $secondary_color
 * @property string $email
 * @property string $endereco
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Category> $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\File> $files
 * @property-read int|null $files_count
 * @property-read string|null $logo_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\EmpresaFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empresa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empresa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empresa query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empresa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empresa whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empresa whereEndereco($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empresa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empresa whereLogoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empresa whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empresa wherePrimaryColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empresa whereSecondaryColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empresa whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Empresa extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'nome',
        'logo_path',
        'email',
        'endereco',
        'primary_color',
        'secondary_color',
        'approval_status',
        'approved_at',
        'approved_by',
        'payment_proof_path',
        'registration_date',
        'trial_start_date',
        'trial_end_date',
        'subscription_end_date',
        'account_status',
        'total_months_paid',
        'last_notification_sent',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'registration_date' => 'datetime',
        'trial_start_date' => 'datetime',
        'trial_end_date' => 'datetime',
        'subscription_end_date' => 'datetime',
        'last_notification_sent' => 'datetime',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo_path) {
            // Tentar usar URL direta do storage público primeiro
            if (Storage::disk('public')->exists($this->logo_path)) {
                return asset('storage/' . $this->logo_path);
            }
            // Fallback para rota customizada
            return route('empresa.logo', $this->id);
        }
        return null;
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isPending()
    {
        return $this->approval_status === 'pending';
    }

    public function isApproved()
    {
        return $this->approval_status === 'approved';
    }

    public function isRejected()
    {
        return $this->approval_status === 'rejected';
    }

    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function isInTrialPeriod(): bool
    {
        return $this->trial_start_date && $this->trial_end_date && 
               now()->between($this->trial_start_date, $this->trial_end_date);
    }

    public function isTrialExpired(): bool
    {
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

    /**
     * Relacionamento com pagamentos
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'entity_id')->where('payment_type', 'empresa');
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
        
        // Suspender também todos os usuários da empresa
        $this->users()->update(['account_status' => 'suspended']);
        
        // Criar notificação
        AccountNotification::createAccountSuspended($this, 'empresa');
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
        
        // Reativar também todos os usuários da empresa
        $this->users()->update(['account_status' => 'active']);
    }

    /**
     * Verificar se os usuários da empresa podem fazer login
     */
    public function canUsersLogin(): bool
    {
        return $this->isApproved() && 
               ($this->isAccountActive() || $this->isInTrialPeriod()) && 
               !$this->isAccountSuspended();
    }

    /**
     * Obter admin da empresa
     */
    public function getAdmin()
    {
        return $this->users()->where('role', 'admin')->first() ?: 
               $this->users()->where('role', 'company_admin')->first();
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
     * Método para limpar trial após pagamento aprovado
     * Garante que todos os campos de trial sejam removidos
     */
    public function clearTrialAfterPayment()
    {
        $this->update([
            'trial_start_date' => null,
            'trial_end_date' => null,
            'trial_start' => null,
            'trial_end' => null,
            'account_status' => 'active',
            'has_paid' => true,
            'approval_status' => 'approved',
        ]);

        Log::info('Trial da empresa limpo após pagamento aprovado', [
            'empresa_id' => $this->id,
            'nome' => $this->nome
        ]);

        // Também limpar trial dos usuários da empresa (sem chamar método recursivo)
        $this->users()->update([
            'trial_start_date' => null,
            'trial_end_date' => null,
            'account_status' => 'active',
            'has_paid' => true,
        ]);
    }

    /**
     * Verificar se trial está limpo corretamente
     */
    public function isTrialCompletelyCleared(): bool
    {
        return is_null($this->trial_start_date) && 
               is_null($this->trial_end_date) && 
               is_null($this->trial_start) && 
               is_null($this->trial_end);
    }
}
