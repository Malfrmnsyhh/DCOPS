<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest 
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'alarm_id' => ['nullable', 'exists:dc_alarms,id'],
            'rack_id' => ['nullable', 'exists:dc_racks,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'in:hardware,software,network,other'],
            'priority' => ['required', 'in:low,medium,high,critical'],
            'status' => ['required', 'in:open,in_progress,resolved,closed'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'device_ids' => ['nullable', 'array'],
            'device_ids.*' => ['exists:dc_devices,id'],
        ];
    }
}