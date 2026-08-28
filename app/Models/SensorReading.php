<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
#[Fillable(['sensor_id','value','recorded_at'])]
class SensorReading extends Model
{
    use HasFactory;
    public $timestamps = false;        // tidak ada kolom timestamps
    protected $table = 'dc_sensor_readings';

    protected $dates = ['recorded_at'];   // cast ke Carbon

    public function sensor(): BelongsTo
    {
        return $this->belongsTo(Sensor::class);
    }
}
