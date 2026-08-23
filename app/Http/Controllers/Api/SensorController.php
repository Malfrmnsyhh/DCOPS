<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSensorRequest;
use App\Http\Requests\UpdateSensorRequest;
use App\Http\Resources\SensorResource;
use App\Models\Rack;
use App\Models\Sensor;

class SensorController extends Controller
{
    public function index(Rack $rack)
    {
        return SensorResource::collection($rack->sensors()->paginate(15));
    }

    public function store(StoreSensorRequest $request, Rack $rack)
    {
        return new SensorResource($rack->sensors()->create($request->validated()));
    }

    public function show(Sensor $sensor)
    {
        return new SensorResource($sensor);
    }

    public function update(UpdateSensorRequest $request, Sensor $sensor)
    {
        $sensor->update($request->validated());

        return new SensorResource($sensor);
    }

    public function destroy(Sensor $sensor)
    {
        $sensor->delete();

        return response()->json(null, 204);
    }
}
