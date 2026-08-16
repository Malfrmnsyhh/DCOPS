<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRackRequest extends FormRequest
{
    public function authorize(): bool 
    {
        return true;
    }

    public function rules(): array
    {
        $rackId = $this->route('rack');

        return [
            'code' => ['required', 'string', 'max:50', Rule::unique('dc_racks', 'code')->ignore($rackId)],
            'name' => ['required', 'string', 'max:255'],
            'u_height' => ['required', 'integer'],
            'max_power_kw' => ['nullable', 'numeric'],
            'status' => ['required', 'in:active,decommissioned'],
        ];
    }
}


?>