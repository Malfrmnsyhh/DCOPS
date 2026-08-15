<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlarmRule extends Model
{
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
