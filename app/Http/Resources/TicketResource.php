<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ticket_number' => $this->ticket_number,
            'alarm_id' => $this->alarm_id,
            'rack_id' => $this->rack_id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'priority' => $this->priority,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'assigned_to' => $this->assigned_to,
            'resolved_at' => $this->resolved_at,
            'devices' => $this->whenLoaded('devices', fn () => $this->devices->map(fn ($device) => [
                'id' => $device->id,
                'hostname' => $device->hostname,
                'note' => $device->pivot->note,
            ])),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
