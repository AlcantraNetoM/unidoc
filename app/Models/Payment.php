<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Exception;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_type',
        'entity_id',
        'months_paid',
        'amount',
        'payment_proof_path',
        'status',
        'payment_date',
        'approved_at',
        'approved_by',
        'notes',
        'plan_type'
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'approved_at' => 'datetime',
        'amount' => 'decimal:2'
    ];

    /**
     * Relacionamento com quem aprovou o pagamento
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Obter a entidade que fez o pagamento (User ou Empresa)
     */
    public function entity()
    {
        if ($this->payment_type === 'user') {
            return $this->belongsTo(User::class, 'entity_id');
        } else {
            return $this->belongsTo(Empresa::class, 'entity_id');
        }
    }

    /**
     * Relacionamento com usuário (para pagamentos de usuário)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'entity_id');
    }

    /**
     * Relacionamento com empresa (para pagamentos de empresa)
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'entity_id');
    }

    /**
     * Scope para pagamentos pendentes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope para pagamentos aprovados
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Calcular o valor total baseado no número de meses
     */
    public static function calculateAmount($months, $accountType = 'personal')
    {
        $monthlyRate = $accountType === 'personal' ? 5000 : 15000; // Pessoal: 5000 Kz, Empresarial: 15000 Kz
        return $months * $monthlyRate;
    }

    /**
     * Aprovar pagamento
     */
    public function approve($approvedBy)
    {
        $this->status = 'approved';
        $this->approved_at = now();
        $this->approved_by = $approvedBy;
        $this->save();

        // Atualizar a entidade correspondente
        return $this->updateEntitySubscription();
    }

    /**
     * Atualizar subscrição da entidade
     */
    protected function updateEntitySubscription()
    {
        try {
            if ($this->payment_type === 'user') {
                $user = User::find($this->entity_id);
                if ($user) {
                    $currentEnd = $user->subscription_end_date ?? now();
                    $newEnd = Carbon::parse($currentEnd)->addMonths($this->months_paid);
                    
                    $user->update([
                        'subscription_end_date' => $newEnd,
                        'total_months_paid' => ($user->total_months_paid ?? 0) + $this->months_paid,
                        'payment_processed_at' => now(),
                    ]);
                    
                    // Usar método dedicado para limpar trial
                    $user->clearTrialAfterPayment();
                    
                    return true;
                }
            } else {
                $empresa = Empresa::find($this->entity_id);
                if ($empresa) {
                    $currentEnd = $empresa->subscription_end_date ?? now();
                    $newEnd = Carbon::parse($currentEnd)->addMonths($this->months_paid);
                    
                    $empresa->update([
                        'subscription_end_date' => $newEnd,
                        'total_months_paid' => ($empresa->total_months_paid ?? 0) + $this->months_paid,
                    ]);
                    
                    // Usar método dedicado para limpar trial
                    $empresa->clearTrialAfterPayment();
                    
                    return true;
                }
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
}
