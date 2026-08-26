<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class ActivityLogObserver
{
    private function logAction(Model $model, string $action, ?array $properties = null): void
    {
        if (! auth()->check()) {
            return;
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'subject_type' => get_class($model),
            'subject_id' => $model->id,
            'properties' => $properties,
        ]);
    }

    public function created(Model $model): void
    {
        $this->logAction($model, 'created', $model->toArray());
    }

    public function updated(Model $model): void
    {
        $this->logAction($model, 'updated', Arr::except($model->getChanges(), ['updated_at']));
    }

    public function deleted(Model $model): void
    {
        $this->logAction($model, 'deleted', $model->toArray());
    }
}