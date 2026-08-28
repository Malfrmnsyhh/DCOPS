<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DevicePortResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'device_id'=>$this->device_id,
            'name'=>$this->name,
            'type'=>$this->type,
            'speed_mbps'=>$this->speed_mbps,
            'mac_address'=>$this->mac_address,
            'status'=>$this->status,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
        ];
    }
}
