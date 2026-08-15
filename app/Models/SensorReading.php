<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SensorReading extends Model
{
    public $timestamps = false;        // tidak ada kolom timestamps
    protected $table = 'dc_sensor_readings';

    protected $dates = ['recorded_at'];   // cast ke Carbon

    public function sensor(): BelongsTo
    {
        return $this->belongsTo(Sensor::class);
    }
}
