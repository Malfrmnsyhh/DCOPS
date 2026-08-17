<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDevicePortRequest extends FormRequest
{
    public function authorize():bool{
        return true;
    }

    public function rules(): array
    {
        return [
            'name' =>[
                'required', 'string', 'max:50',
                Rule::unique('dc_device_ports', 'name')->where('device_id', $this->route('device')->id),
            ],
            'type'=>['required', 'in:ethernet,fiber,power,other'],
            'speed_mbps'=>['nullable', 'integer'],
            'mac_address'=>['nullable', 'string', 'max:255'],
            'status'=>['required', 'in:active,disabled'],
        ];
    }
}
?>