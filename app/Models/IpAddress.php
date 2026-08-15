<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IpAddress extends Model
{
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
