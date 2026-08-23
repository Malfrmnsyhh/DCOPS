<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlarmRuleRequest extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'sensor_id' => ['nullable', 'required_without:sensor_type', 'exists:dc_sensors,id'],
            'sensor_type' => ['nullable', 'required_without:sensor_id', 'in:temperature,humidity,power,airflow,other'],
            'name' => ['required', 'string', 'max:255'],
            'operator' => ['required', 'in:>,<,>=,<='],
            'threshold_min' => ['nullable', 'required_without:threshold_max', 'numeric'],
            'threshold_max' => ['nullable', 'required_without:threshold_min', 'numeric'],
            'severity' => ['required', 'in:warning,critical'],
            'auto_create_ticket' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
