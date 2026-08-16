<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['code', 'name', 'u_height', 'max_power_kw', 'status'])]
class Rack extends Model
{
    protected $table = 'dc_racks';

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    public function sensors(): HasMany
    {
        return $this->hasMany(Sensor::class);
    }
}
