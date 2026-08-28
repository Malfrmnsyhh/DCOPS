<?php

namespace App\Http\Requests;

use App\Services\IpamService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AssignIpAddressRequest extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'device_port_id' => ['required', 'integer', 'exists:dc_device_ports,id'],
            'hostname' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function withValidator(Validator $validator): void {
        $validator->after(function (Validator $validator) {
            if (! $this->device_port_id) {
                return;
            }

            $ipAddress = $this->route('ip_address');
            $error = app(IpamService::class)->checkAssignable($ipAddress, (int) $this->device_port_id);

            if ($error) {
                $validator->errors()->add('device_port_id', $error);
            }
        });
    }
}
