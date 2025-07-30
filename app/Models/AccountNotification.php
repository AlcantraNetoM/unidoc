<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'notifiable_type',
        'notifiable_id',
        'type',
        'message',
        'is_read',
        'sent_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'is_read' => 'boolean'
    ];

    /**
     * Relacionamento polimórfico com a entidade notificável
     */
    public function notifiable()
    {
        return $this->morphTo();
    }

    /**
     * Scope para notificações não lidas
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Marcar como lida
     */
    public function markAsRead()
    {
        $this->is_read = true;
        $this->save();
    }

    /**
     * Criar notificação de aviso de expiração
     */
    public static function createExpiryWarning($entity, $type, $daysLeft)
    {
        $entityType = $type === 'user' ? 'Conta Pessoal' : 'Conta Empresarial';
        $message = "Sua {$entityType} expira em {$daysLeft} dias. Faça o pagamento para continuar usando o sistema.";
        
        return self::create([
            'notifiable_type' => $type,
            'notifiable_id' => $entity->id,
            'type' => 'expiry_warning',
            'message' => $message,
            'sent_at' => now()
        ]);
    }

    /**
     * Criar notificação de conta suspensa
     */
    public static function createAccountSuspended($entity, $type)
    {
        $entityType = $type === 'user' ? 'Conta Pessoal' : 'Conta Empresarial';
        $message = "Sua {$entityType} foi suspensa por falta de pagamento. Faça o pagamento para reativar sua conta.";
        
        return self::create([
            'notifiable_type' => $type,
            'notifiable_id' => $entity->id,
            'type' => 'account_suspended',
            'message' => $message,
            'sent_at' => now()
        ]);
    }

    /**
     * Criar notificação de pagamento aprovado
     */
    public static function createPaymentApproved($entity, $type, $months)
    {
        $entityType = $type === 'user' ? 'Conta Pessoal' : 'Conta Empresarial';
        $message = "Seu pagamento de {$months} mês(es) foi aprovado. Sua {$entityType} foi reativada com sucesso!";
        
        return self::create([
            'notifiable_type' => $type,
            'notifiable_id' => $entity->id,
            'type' => 'payment_approved',
            'message' => $message,
            'sent_at' => now()
        ]);
    }
}
