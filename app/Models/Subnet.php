<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subnet extends Model
{
    protected $table = 'dc_subnets';

    public function ipAddresses(): HasMany
    {
        return $this->hasMany(IpAddress::class);
    }
}
