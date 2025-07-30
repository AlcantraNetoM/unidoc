<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    protected static function bootAuditable()
    {
        // Log quando criar
        static::created(function ($model) {
            if (Auth::check()) {
                AuditLog::logAction(
                    'create',
                    $model,
                    Auth::user(),
                    Request::instance(),
                    null,
                    $model->getAttributes(),
                    "Criado " . class_basename($model) . " ID: {$model->id}",
                    'medium'
                );
            }
        });

        // Log quando atualizar
        static::updated(function ($model) {
            if (Auth::check()) {
                $changes = $model->getChanges();
                $original = $model->getOriginal();
                
                // Filtrar apenas os campos que mudaram
                $oldValues = array_intersect_key($original, $changes);
                
                AuditLog::logAction(
                    'update',
                    $model,
                    Auth::user(),
                    Request::instance(),
                    $oldValues,
                    $changes,
                    "Atualizado " . class_basename($model) . " ID: {$model->id}",
                    'medium'
                );
            }
        });

        // Log quando deletar
        static::deleted(function ($model) {
            if (Auth::check()) {
                AuditLog::logAction(
                    'delete',
                    $model,
                    Auth::user(),
                    Request::instance(),
                    $model->getAttributes(),
                    null,
                    "Deletado " . class_basename($model) . " ID: {$model->id}",
                    'high'
                );
            }
        });
    }
}
