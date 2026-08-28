<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeviceTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:dc_device_types,slug'],
            'category' => ['required', 'in:network,server,storage,other'],
            'default_u_size' => ['required', 'integer', 'min:1'],
        ];
    }
}
?>