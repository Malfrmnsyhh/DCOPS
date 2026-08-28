<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSensorRequest extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'code'=>['required', 'string', 'max:50', Rule::unique('dc_sensors', 'code')->ignore($this->route('sensor'))],
            'name'=>['required', 'string', 'max:255'],
            'type'=>['required', 'in:temperature,humidity,power,airflow,other'],
            'unit'=>['required', 'string', 'max:50'],
            'status'=>['required', 'in:active,inactive'],
        ];
    }
}