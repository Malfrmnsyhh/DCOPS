<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeviceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rack_id' => $this->rack_id,
            'device_type_id' => $this->device_type_id,
            'hostname' => $this->hostname,
            'serial_number' => $this->serial_number,
            'manufacturer' => $this->manufacturer,
            'model' => $this->model,
            'position_u' => $this->position_u,
            'u_size' => $this->u_size,
            'power_watt' => $this->power_watt,
            'status' => $this->status,
            'installed_at' => $this->installed_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
?>