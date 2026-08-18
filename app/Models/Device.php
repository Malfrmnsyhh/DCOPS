<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable('rack_id', 'device_type_id', 'hostname', 'serial_number', 'manufacturer','model', 'position_u', 'u_size', 'power_watt', 'status', 'installed_at')]
class Device extends Model
{
    use HasFactory;
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
