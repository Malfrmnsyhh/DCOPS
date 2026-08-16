<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSiteRequest extends FormRequest 
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules():array
    {
        return [
            'code'=>[
                'required', 'string', 'max:50',
                Rule::unique('dc_sites', 'code')->ignore($this->route('site')),
            ],
            'name'=>['required', 'string', 'max:255'],
            'address'=>['required', 'string'],
            'city'=>['required', 'string', 'max:100'],
            'status'=>['required', 'in:active,decommissioned'],
        ];
    }
}

?>