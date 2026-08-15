<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    public $timestamps = false;   // kolom created_at di‑handle manual
    protected $table = 'activity_logs';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Polymorphic relation – apa yang berubah */
    public function subject()
    {
        return $this->morphTo();
    }
}
