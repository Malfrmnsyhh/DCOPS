<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

class RackResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'room_id'=>$this->room_id,
            'code'=>$this->code,
            'name'=>$this->name,
            'u_height'=>$this->u_height,
            'max_power_kw'=>$this->max_power_kw,
            'status'=>$this->status,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
        ];
    }
}


?>