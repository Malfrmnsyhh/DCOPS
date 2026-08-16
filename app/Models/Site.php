<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

#[Fillable(['code', 'name', 'address', 'city', 'status'])]
class Site extends Model
{
    protected $table = 'dc_sites';

    /** One Site has many Rooms */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    /** Through Rooms we can get all Racks */
    public function racks(): HasManyThrough
    {
        return $this->hasManyThrough(Rack::class, Room::class);
    }
}
