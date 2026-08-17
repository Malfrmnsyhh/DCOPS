<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'slug', 'category', 'default_u_size'])]
class DeviceType extends Model
{
    protected $table = 'dc_device_types';

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }
}
