<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable('alarm_rulr_id', 'sensor_id', 'triggered_value', 'severity', 'status', 'triggered_at', 'acknowledged_by', 'resolved_at')]
class Alarm extends Model
{
    use HasFactory;
    protected $table = 'dc_alarms';

    public function alarmRule(): BelongsTo
    {
        return $this->belongsTo(AlarmRule::class);
    }

    public function sensor(): BelongsTo
    {
        return $this->belongsTo(Sensor::class);
    }

    public function acknowledgedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acknowledged_by');
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
