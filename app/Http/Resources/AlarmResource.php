<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlarmResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'alarm_rule_id' => $this->alarm_rule_id,
            'sensor_id' => $this->sensor_id,
            'triggered_value' => $this->triggered_value,
            'severity' => $this->severity,
            'status' => $this->status,
            'triggered_at' => $this->triggered_at,
            'acknowledged_by' => $this->acknowledged_by,
            'resolved_at' => $this->resolved_at,
        ];
    }
}
