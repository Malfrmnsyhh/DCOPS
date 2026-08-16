<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $room = $this->route('room');

        return [
            'code' => [
                'required', 'string', 'max:50',
                Rule::unique('dc_rooms', 'code')
                    ->where('site_id', $room->site_id)
                    ->ignore($room),
            ],
            'name' => ['required', 'string', 'max:255'],
            'floor' => ['nullable', 'string', 'max:50'],
            'area_sqm' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:active,decommissioned'],
        ];
    }
}
