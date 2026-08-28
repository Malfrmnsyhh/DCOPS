<?php

namespace App\Http\Requests;

use App\Services\PortConnectionService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePortConnectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'from_port_id' => ['required', 'integer', 'exists:dc_device_ports,id'],
            'to_port_id' => ['required', 'integer', 'exists:dc_device_ports,id'],
            'cable_type' => ['required', 'in:cat5,cat6,fiber,coax'],
            'cable_label' => ['nullable', 'string', 'max:255'],
            'length_m' => ['nullable', 'numeric'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (! $this->from_port_id || ! $this->to_port_id) {
                return;
            }

            $error = app(PortConnectionService::class)->checkPortsAvailable(
                (int) $this->from_port_id,
                (int) $this->to_port_id,
                $this->route('port_connection')->id,
            );

            if ($error) {
                $validator->errors()->add('to_port_id', $error);
            }
        });
    }
}
