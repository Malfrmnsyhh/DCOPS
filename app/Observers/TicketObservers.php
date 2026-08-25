<?php

namespace App\Observers;

use App\Models\Ticket;

class TicketObservers
{
    public function creating(Ticket $ticket): void {
        $year = now()->year;
        $sequence = Ticket::whereYear('created_at', $year)->count() + 1;

        $ticket->ticket_number = sprintf('TKT-%d-%05d', $year. $sequence);
    }
}