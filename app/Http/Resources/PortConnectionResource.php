<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortConnectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'from_port_id' => $this->from_port_id,
            'to_port_id' => $this->to_port_id,
            'cable_type' => $this->cable_type,
            'cable_label' => $this->cable_label,
            'length_m' => $this->length_m,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
