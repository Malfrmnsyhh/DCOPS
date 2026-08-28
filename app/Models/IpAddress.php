<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['device_port_id', 'status', 'hostname', 'assigned_at'])]
class IpAddress extends Model
{
    use HasFactory;
    protected $table = 'dc_ip_addresses';

    public function subnet(): BelongsTo
    {
        return $this->belongsTo(Subnet::class);
    }

    public function devicePort(): BelongsTo
    {
        return $this->belongsTo(DevicePort::class);
    }
}
