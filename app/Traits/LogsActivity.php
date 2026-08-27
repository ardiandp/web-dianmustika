<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected function logActivity(
        string $action,
        ?Model $model,
        string $description,
        ?array $changes = null,
        string $module = ''
    ): void {
        try {
            $user = Auth::user();
            $request = request();

            // Auto-detect module from model if not given
            if (! $module && $model) {
                $module = strtolower(class_basename($model));
                // Normalize: servicecategory -> service-categories etc, keep simple
                $module = match ($module) {
                    'ServiceCategory' => 'service-categories',
                    'ArticleCategory' => 'article-categories',
                    'ServiceGallery' => 'services',
                    default => str($module)->kebab()->toString(),
                };
            }

            ActivityLog::create([
                'user_id' => $user?->id,
                'user_name' => $user?->name ?? 'System',
                'action' => $action,
                'loggable_type' => $model ? get_class($model) : null,
                'loggable_id' => $model?->getKey(),
                'module' => $module ?: 'system',
                'description' => $description,
                'changes' => $changes,
                'ip_address' => $request?->ip(),
                'user_agent' => $request ? mb_substr($request->userAgent() ?? '', 0, 500) : null,
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    protected function diffChanges(Model $model, array $exclude = ['updated_at', 'created_at']): ?array
    {
        $dirty = $model->getDirty();
        if (empty($dirty)) {
            return null;
        }

        $changes = [];
        foreach ($dirty as $key => $new) {
            if (in_array($key, $exclude, true)) {
                continue;
            }
            // Hide sensitive
            if (in_array($key, ['password', 'remember_token'], true)) {
                continue;
            }
            $old = $model->getOriginal($key);
            // Truncate long values
            $oldStr = is_string($old) ? mb_substr((string) $old, 0, 500) : $old;
            $newStr = is_string($new) ? mb_substr((string) $new, 0, 500) : $new;
            if ($oldStr !== $newStr) {
                $changes[$key] = ['old' => $oldStr, 'new' => $newStr];
            }
        }

        return empty($changes) ? null : $changes;
    }
}
