<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IpAddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subnet_id' => $this->subnet_id,
            'device_port_id' => $this->device_port_id,
            'address' => $this->address,
            'status' => $this->status,
            'hostname' => $this->hostname,
            'assigned_at' => $this->assigned_at,
        ];
    }
}
