<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' =>[
                'required', 'string', 'max:50',
                Rule::unique('dc_rooms', 'code')->where('site_id', $this->route('site')->id),
            ],
            'name'=>['required', 'string', 'max:225'],
            'floor'=>['nullable', 'string', 'max:50'],
            'area_sqm'=>['nullable', 'numeric', 'min:0'],
            'status'=>['required', 'in:active, decommissioned'],
        ];


    }
}


?>