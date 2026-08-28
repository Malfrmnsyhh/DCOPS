<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttachTicketDeviceRequest extends FormRequest 
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'device_id'=>['required','exists:dc_devices,id'],
            'note'=>['nullable', 'string', 'max:255'],
        ];
    }
}