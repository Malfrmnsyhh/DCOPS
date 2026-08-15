<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sensor extends Model
{
    protected $table = 'dc_sensors';

    public function rack(): BelongsTo
    {
        return $this->belongsTo(Rack::class);
    }

    public function readings(): HasMany
    {
        return $this->hasMany(SensorReading::class);
    }

    public function alarmRules(): HasMany
    {
        return $this->hasMany(AlarmRule::class);
    }

    public function alarms(): HasMany
    {
        return $this->hasMany(Alarm::class);
    }
}
