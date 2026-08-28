<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttachTicketDeviceRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Device;
use App\Models\Ticket;

class TicketController extends Controller
{
    public function index() {
        return TicketResource::collection(Ticket::paginate(15));
    }

    public function store(StoreTicketRequest $request) {
        $ticket = Ticket::create([
            ...$request->safe()->except('device_ids'),
            'created_by' => $request->user()->id,
        ]);

        if ($request->filled('device_ids')) {
            $ticket->devices()->attach($request->device_ids);
        }

        return new TicketResource($ticket->load('devices'));
    }

    public function show(Ticket $ticket) {
        return new TicketResource($ticket->load('devices'));
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket) {
        $ticket->update($request->validated());

        return new TicketResource($ticket->load('devices'));
    }

    public function destroy(Ticket $ticket) {
        $ticket->delete();

        return response()->json(null, 204);
    }

    public function attachDevice(AttachTicketDeviceRequest $request, Ticket $ticket) {
        $ticket->devices()->syncWithoutDetaching([
            $request->device_id => ['note' => $request->note],
        ]);

        return new TicketResource($ticket->load('devices'));
    }

    public function detachDevice(Ticket $ticket, Device $device) {
        $ticket->devices()->detach($device->id);

        return response()->json(null, 204);
    }
}
