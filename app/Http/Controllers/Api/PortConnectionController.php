<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePortConnectionRequest;
use App\Http\Requests\UpdatePortConnectionRequest;
use App\Http\Resources\PortConnectionResource;
use App\Models\PortConnection;

class PortConnectionController extends Controller
{
    public function index() {
        return PortConnectionResource::collection(PortConnection::paginate(15));
    }

    public function store(StorePortConnectionRequest $request) {
        return new PortConnectionResource(PortConnection::create($request->validated()));
    }

    public function show(PortConnection $portConnection) {
        return new PortConnectionResource($portConnection);
    }

    public function update(UpdatePortConnectionRequest $request, PortConnection $portConnection) {
        $portConnection->update($request->validated());
        return new PortConnectionResource($portConnection);
    }

    public function destroy(PortConnection $portConnection) {
        $portConnection->delete();
        return response()->json(null, 204);
    }
}
