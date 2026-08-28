<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['code', 'name', 'floor', 'area_sqm', 'status'])]
class Room extends Model
{
    use HasFactory;

    protected $table = 'dc_rooms';

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function racks(): HasMany
    {
        return $this->hasMany(Rack::class);
    }
}
