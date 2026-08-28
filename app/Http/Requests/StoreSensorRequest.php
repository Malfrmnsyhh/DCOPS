<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSensorRequest extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'code'=>['required', 'string', 'max:50', 'unique:dc_sensors,code'],
            'name'=>['required', 'string', 'max:255'],
            'type'=>['required', 'in:temperature,humidity,power,airflow,other'],
            'unit'=>['required', 'string', 'max:50'],
            'status'=>['required', 'in:active,inactive'],
        ];
    }
}