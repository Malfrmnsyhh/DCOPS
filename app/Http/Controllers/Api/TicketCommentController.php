<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketCommentRequest;
use App\Http\Requests\UpdateTicketCommentRequest;
use App\Http\Resources\TicketCommentResource;
use App\Models\Ticket;
use App\Models\TicketComment;

class TicketCommentController extends Controller
{
    public function index(Ticket $ticket)
    {
        return TicketCommentResource::collection($ticket->comments()->paginate(20));
    }

    public function store(StoreTicketCommentRequest $request, Ticket $ticket)
    {
        $comment = $ticket->comments()->create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        return new TicketCommentResource($comment);
    }

    public function show(TicketComment $comment)
    {
        return new TicketCommentResource($comment);
    }

    public function update(UpdateTicketCommentRequest $request, TicketComment $comment)
    {
        $comment->update($request->validated());

        return new TicketCommentResource($comment);
    }

    public function destroy(TicketComment $comment)
    {
        $comment->delete();

        return response()->json(null, 204);
    }
}
