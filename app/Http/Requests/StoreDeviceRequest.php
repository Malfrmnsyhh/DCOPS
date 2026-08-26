<?php

namespace App\Http\Requests;

use App\Models\Rack;
use App\Services\DeviceSlotService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreDeviceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // --- Validasi Data Utama Device ---
            'rack_id' => ['nullable', 'exists:dc_racks,id', 'required_with:position_u'],
            'device_type_id' => ['required', 'exists:dc_device_types,id'],
            'hostname' => ['required', 'string', 'max:255', 'unique:dc_devices,hostname'],
            'serial_number' => ['required', 'string', 'max:255', 'unique:dc_devices,serial_number'],
            'manufacturer' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'position_u' => ['nullable', 'integer', 'min:1', 'required_with:rack_id'],
            'u_size' => ['required', 'integer', 'min:1'],
            'power_watt' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:active,standby,decommissioned'],
            'installed_at' => ['nullable', 'date'],

            // --- Validasi Nested Array untuk Ports ---
            'ports' => ['nullable', 'array'],
            'ports.*.name' => [
                'required', 
                'string', 
                'max:50', 
                'distinct'
            ],
            'ports.*.type' => ['required', 'in:ethernet,fiber,power,other'],
            'ports.*.speed_mbps' => ['nullable', 'integer', 'min:0'],
            'ports.*.mac_address' => ['nullable', 'string', 'max:255'],
            'ports.*.status' => ['required', 'in:active,disabled'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (! $this->rack_id || ! $this->position_u) {
                return;
            }

            $rack = Rack::find($this->rack_id);
            if (! $rack) {
                return;
            }

            $error = app(DeviceSlotService::class)->checkSlotAvailability(
                $rack,
                (int) $this->position_u,
                (int) $this->u_size,
            );

            if ($error) {
                $validator->errors()->add('position_u', $error);
            }
        });
    }
}