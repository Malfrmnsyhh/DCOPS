<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['code', 'name', 'type', 'unit', 'status', 'last_reading_at'])]
class Sensor extends Model
{
    use HasFactory;
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
