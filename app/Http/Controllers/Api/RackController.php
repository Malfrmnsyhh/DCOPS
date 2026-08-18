<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRackRequest;
use App\Http\Requests\UpdateRackRequest;
use App\Http\Resources\RackResource;
use App\Models\Room;
use App\Models\Rack;
use App\Services\DeviceSlotService;

class RackController extends Controller
{
    public function index(Room $room)
    {
        $racks = $room->racks()->paginate(15);
        return RackResource::collection($racks);
    }

    public function store(StoreRackRequest $request, Room $room)
    {
        $rack = $room->racks()->create($request->validated());
        return new RackResource($rack);
    }

    public function show(Rack $rack)
    {
        return new RackResource($rack);
    }

    public function update(UpdateRackRequest $request, Rack $rack)
    {
        $rack->update($request->validated());
        return new RackResource($rack);
    }

    public function destroy(Rack $rack)
    {
        $rack->delete();
        return response()->json(null, 204);
    }

    public function elevation(Rack $rack, DeviceSlotService $slotService)
    {
        return response()->json([
            'data' => [
                'rack_id' => $rack->id,
                'code' => $rack->code,
                'u_height' => $rack->u_height,
                'slots' => $slotService->buildElevation($rack),
            ],
        ]);
    }
}
?>
