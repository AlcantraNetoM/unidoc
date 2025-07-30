<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'action',
        'model',
        'model_id',
        'user_id',
        'user_name',
        'user_email',
        'user_role',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'description',
        'severity'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relacionamento com usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Métodos para facilitar consultas
    public static function logLogin($user, $request)
    {
        return self::create([
            'action' => 'login',
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_role' => $user->role,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'description' => "Usuário {$user->name} fez login no sistema",
            'severity' => 'low'
        ]);
    }

    public static function logLogout($user, $request)
    {
        return self::create([
            'action' => 'logout',
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_role' => $user->role,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'description' => "Usuário {$user->name} fez logout do sistema",
            'severity' => 'low'
        ]);
    }

    public static function logAction($action, $model, $user, $request, $oldValues = null, $newValues = null, $description = null, $severity = 'medium')
    {
        $modelName = class_basename($model);
        $modelId = $model ? $model->id : null;

        return self::create([
            'action' => $action,
            'model' => $modelName,
            'model_id' => $modelId,
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_role' => $user->role,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'description' => $description,
            'severity' => $severity
        ]);
    }

    public static function logFailedLogin($email, $request)
    {
        return self::create([
            'action' => 'failed_login',
            'user_email' => $email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'description' => "Tentativa de login falhada para email: {$email}",
            'severity' => 'high'
        ]);
    }

    // Scopes para filtrar logs
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
