<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    /**
     * Boot the trait
     */
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            static::logModelActivity('create', $model);
        });

        static::updated(function ($model) {
            static::logModelActivity('update', $model);
        });

        static::deleted(function ($model) {
            static::logModelActivity('delete', $model);
        });
    }

    /**
     * Log model activity
     */
    protected static function logModelActivity(string $action, $model)
    {
        $modelName = class_basename($model);

        // Get a display name for the model
        $displayName = $model->name ?? ($model->title ?? $model->id);

        // Build description based on action
        if ($action === 'create') {
            $description = "{$modelName} '{$displayName}' was created";
        } elseif ($action === 'update') {
            $description = "{$modelName} '{$displayName}' was updated";
        } elseif ($action === 'delete') {
            $description = "{$modelName} '{$displayName}' was deleted";
        } else {
            $description = "{$modelName} was {$action}";
        }

        $oldValues = null;
        $newValues = null;

        if ($action === 'update') {
            $oldValues = $model->getOriginal();
            $newValues = $model->getAttributes();

            // Filter out timestamps and irrelevant fields
            $excludeFields = ['updated_at', 'created_at', 'password', 'remember_token'];
            $oldValues = array_diff_key($oldValues, array_flip($excludeFields));
            $newValues = array_diff_key($newValues, array_flip($excludeFields));
        } elseif ($action === 'create') {
            $newValues = $model->getAttributes();
        } elseif ($action === 'delete') {
            $oldValues = $model->getAttributes();
        }

        ActivityLog::logActivity(
            $action,
            $description,
            get_class($model),
            $model->id,
            $oldValues,
            $newValues
        );
    }
}
