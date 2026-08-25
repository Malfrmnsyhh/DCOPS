<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['alarm_id','rack_id','title','description','category','priority','status','created_at','assigned_to','resolved_at'])]
class Ticket extends Model
{
    use HasFactory;
    protected $table = 'dc_tickets';

    public function alarm(): BelongsTo
    {
        return $this->belongsTo(Alarm::class);
    }

    public function rack(): BelongsTo
    {
        return $this->belongsTo(Rack::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class);
    }

    /** Many‑to‑many relationship via pivot table */
    public function devices(): BelongsToMany
    {
        return $this->belongsToMany(Device::class, 'dc_ticket_device')
                    ->withPivot('note')
                    ->withTimestamps();
    }
}
