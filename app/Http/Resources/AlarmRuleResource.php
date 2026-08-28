<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlarmRuleResource extends JsonResource
{
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'sensor_id' => $this->sensor_id,
            'sensor_type' => $this->sensor_type,
            'name' => $this->name,
            'operator' => $this->operator,
            'threshold_min' => $this->threshold_min,
            'threshold_max' => $this->threshold_max,
            'severity' => $this->severity,
            'auto_create_ticket' => (bool) $this->auto_create_ticket,
            'is_active' => (bool) $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
