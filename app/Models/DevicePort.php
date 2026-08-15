<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DevicePort extends Model
{
    protected $table = 'dc_device_ports';

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function fromConnections(): HasMany
    {
        return $this->hasMany(PortConnection::class, 'from_port_id');
    }

    public function toConnections(): HasMany
    {
        return $this->hasMany(PortConnection::class, 'to_port_id');
    }
}
