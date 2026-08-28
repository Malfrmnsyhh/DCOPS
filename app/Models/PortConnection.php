<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['from_port_id', 'to_port_id', 'cable_type', 'cable_label', 'length_m'])]
class PortConnection extends Model
{
    use HasFactory;

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
