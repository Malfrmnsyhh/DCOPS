<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use App\Models\Site;

class RoomController extends Controller
{
    public function index(Site $site)
    {
        $rooms = $site->rooms()->paginate(15);
        return RoomResource::collection($rooms);
    }

    public function store(StoreRoomRequest $request, Site $site)
    {
        $room = $site->rooms()->create($request->validated());
        return new RoomResource($room);
    }

    public function show(Room $room)
    {
        return new RoomResource($room);
    }

    public function update(UpdateRoomRequest $request, Room $room)
    {
        $room->update($request->validated());
        return new RoomResource($room);
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return response()->json(null, 204);
    }
}
?>
