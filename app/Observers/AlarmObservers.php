<?php

namespace App\Observers;

use App\Models\Alarm;
use App\Models\Ticket;
use App\Models\User;

class AlarmObservers
{
    public function created(Alarm $alarm): void 
    {
        if (! $alarm->alarmRule?->auto_create_ticket) {
            return;
        }

        $priority = $alarm->severity === 'critical' ? 'critical' : 'medium';
        $category = 'hardware';
        $systemUser = User::first();
        $rackId = $alarm->sensor?->rack_id;

        Ticket::create([
            'alarm_id' => $alarm->id,
            'rack_id' => $rackId,
            'title' => "Auto Ticket: Pelanggaran Rule " . $alarm->alarmRule?->name,
            'description' => "Tiket dibuat otomatis oleh sistem. Sensor mendeteksi nilai: {$alarm->triggered_value} (Severity: {$alarm->severity}).",
            'category' => $category,
            'priority' => $priority,
            'status' => 'open',
            'created_by' => $systemUser ? $systemUser->id : null,
        ]);
    }
}