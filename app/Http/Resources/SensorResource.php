<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

class SensorResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id'=>$this->id,
            'rack_id'=>$this->rack_id,
            'code'=>$this->code,
            'name'=>$this->name,
            'type'=>$this->type,
            'unit'=>$this->unit,
            'status'=>$this->status,
            'last_reading_at'=>$this->last_reading_at,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
        ];
    }
}