<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortConnection extends Model
{
    protected $table = 'dc_port_connections';

    public function fromPort(): BelongsTo
    {
        return $this->belongsTo(DevicePort::class, 'from_port_id');
    }

    public function toPort(): BelongsTo
    {
        return $this->belongsTo(DevicePort::class, 'to_port_id');
    }
}
