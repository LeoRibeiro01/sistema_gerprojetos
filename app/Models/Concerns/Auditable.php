<?php

namespace App\Models\Concerns;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    protected static function bootAuditable(): void
    {
        static::creating(function ($model): void {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });

        static::updating(function ($model): void {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });

        static::updated(function ($model): void {
            $changes = $model->getChanges();
            unset($changes['updated_at'], $changes['updated_by']);

            if ($changes === []) {
                return;
            }

            $oldValues = array_intersect_key($model->getOriginal(), $changes);

            AuditLog::create([
                'user_id' => Auth::id(),
                'event' => 'updated',
                'auditable_type' => $model->getMorphClass(),
                'auditable_id' => $model->getKey(),
                'old_values' => $oldValues,
                'new_values' => $changes,
            ]);
        });
    }
}
