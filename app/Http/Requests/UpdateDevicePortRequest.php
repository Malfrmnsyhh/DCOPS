<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDevicePortRequest extends FormRequest
{
    public function authorize(): bool 
    {
        return true;
    }

    public function rules(): array
    {
        $port = $this->route('port');

        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('dc_device_ports', 'name')
                    ->where('device_id', $port->device_id)
                    ->ignore($port),
            ],
            'type'=>['required', 'in:ethernet,fiber,power,other'],
            'speed_mbps'=>['nullable', 'integer'],
            'mac_address'=>['nullable', 'string', 'max:255'],
            'status'=>['required', 'in:active,disabled'],
        ];
    }
}
?>