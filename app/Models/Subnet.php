<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable(['name', 'cidr', 'gateway', 'vlan_id', 'description'])]
class Subnet extends Model
{
    use HasFactory;
    protected $table = 'dc_subnets';

    public function ipAddresses(): HasMany
    {
        return $this->hasMany(IpAddress::class);
    }
}
