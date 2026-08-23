<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['sensor_id', 'sensor_type', 'name', 'operator', 'threshold_min', 'threshold_max', 'severity', 'auto_create_ticket', 'is_active'])]
class AlarmRule extends Model
{
    use HasFactory;

    protected $table = 'dc_alarm_rules';

    public function sensor(): BelongsTo
    {
        return $this->belongsTo(Sensor::class);
    }

    public function alarms(): HasMany
    {
        return $this->hasMany(Alarm::class);
    }
}
