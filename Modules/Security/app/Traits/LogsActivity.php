<?php

namespace Modules\Security\Traits;

use Illuminate\Support\Facades\Auth;
use Modules\Security\Models\ActivityLog;

trait LogsActivity
{
    protected static array $activityLogExcept = ['updated_at', 'created_at', 'remember_token'];

    protected static array $activityLogMasked = ['password'];

    protected static function bootLogsActivity(): void
    {
        static::created(function ($model) {
            $model->recordActivity('created', [
                'new' => $model->cleanAttributesForLog($model->getAttributes()),
            ]);
        });

        static::updated(function ($model) {
            $changes = $model->cleanAttributesForLog($model->getChanges());

            if (empty($changes)) {
                return;
            }

            $original = collect($model->getOriginal())
                ->only(array_keys($changes))
                ->toArray();

            $model->recordActivity('updated', [
                'old' => $model->cleanAttributesForLog($original),
                'new' => $changes,
            ]);
        });

        static::deleted(function ($model) {
            $model->recordActivity('deleted', [
                'old' => $model->cleanAttributesForLog($model->getOriginal()),
            ]);
        });
    }

    protected function cleanAttributesForLog(array $attributes): array
    {
        $attributes = collect($attributes)->except(static::$activityLogExcept);

        foreach (static::$activityLogMasked as $maskedKey) {
            if ($attributes->has($maskedKey)) {
                $attributes[$maskedKey] = '••••••••';
            }
        }

        return $attributes->toArray();
    }

    protected function recordActivity(string $event, array $properties): void
    {
        ActivityLog::create([
            'log_name'      => strtolower(class_basename($this)),
            'description'   => sprintf('%s %s', class_basename($this), $event),
            'event'         => $event,
            'subject_type'  => static::class,
            'subject_id'    => $this->getKey(),
            'causer_type'   => Auth::check() ? get_class(Auth::user()) : null,
            'causer_id'     => Auth::id(),
            'properties'    => $properties,
        ]);
    }
}
