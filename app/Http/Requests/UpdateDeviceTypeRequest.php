<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDeviceTypeRequest extends FormRequest
{
    public function authorize(): bool{
        return true;
    }

    public function rules(): array {
        $deviceType = $this->route('device_type');
        return [
            'name'=>['required', 'string', 'max:255'],
            'slug'=>['required', 'string', 'max:255', Rule::unique('dc_device_types', 'slug')->ignore($deviceType)],
            'category'=>['required', 'in:network,server,storage,other'],
            'default_u_size'=>['required', 'integer', 'min:1']
        ];
    }
}

?>