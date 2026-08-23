<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubnetRequest extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name'=>['nullable','string', 'max:255'],
            'gateway'=>['nullable', 'ip'],
            'vlan_id'=>['nullable','integer','min:1','max:4094'],
            'description'=>['nullable', 'string'],
        ];
    }
}