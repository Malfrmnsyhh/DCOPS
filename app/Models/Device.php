<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use SoftDeletes;                // soft‑delete hanya di tabel devices

    protected $table = 'dc_devices';

    public function rack(): BelongsTo
    {
        return $this->belongsTo(Rack::class);
    }

    public function deviceType(): BelongsTo
    {
        return $this->belongsTo(DeviceType::class);
    }

    public function ports(): HasMany
    {
        return $this->hasMany(DevicePort::class);
    }
}
