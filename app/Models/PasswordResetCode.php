<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PasswordResetCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'phone',
        'code',
        'expires_at',
        'is_used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    /**
     * Gerar código de 8 dígitos
     */
    public static function generateCode(): string
    {
        return str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
    }

    /**
     * Verificar se o código está válido
     */
    public function isValid(): bool
    {
        return !$this->is_used && $this->expires_at > now();
    }

    /**
     * Marcar código como usado
     */
    public function markAsUsed(): void
    {
        $this->update(['is_used' => true]);
    }

    /**
     * Limpar códigos expirados
     */
    public static function clearExpired(): void
    {
        static::where('expires_at', '<', now())
            ->orWhere('is_used', true)
            ->delete();
    }
}
