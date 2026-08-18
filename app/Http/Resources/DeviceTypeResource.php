<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

class DeviceTypeResource extends JsonResource
{
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'slug'=>$this->slug,
            'category'=>$this->category,
            'default_u_size'=>$this->default_u_size,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at
        ];
    }
}

?>