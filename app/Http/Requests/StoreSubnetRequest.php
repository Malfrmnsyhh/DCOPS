<?php

namespace App\Http\Requests;

use App\Services\IpamService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubnetRequest extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules():array {
        return [
            'name'=>['nullable','string','max:255'],
            'cidr'=>['required','string', 'max:50','unique:dc_subnets,cidr'],
            'gateway'=>['nullable', 'ip'],
            'vlan_id'=>['nullable', 'integer', 'min:1', 'max:4094'],
            'description'=>['nullable', 'string'],
        ];
    }

    public function withValidator(Validator $validator): void {
        $validator->after(function (Validator $validator) {
            if (! $this->cidr)
                {return;
            }
            
            $error = app(IpamService::class)->validateCidr($this->cidr);

            if ($error) {
                $validator->errors()->add('cidr', $error);
            }
        });
    }
}