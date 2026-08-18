<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDevicePortRequest;
use App\Http\Requests\UpdateDevicePortRequest;
use App\Http\Resources\DevicePortResource;
use App\Models\Device;
use App\Models\DevicePort;

class DevicePortController extends Controller
{
    public function index(Device $device){
        $devicePort = $device->ports()->paginate(15);
        return DevicePortResource::collection($devicePort);
    }

    public function store(StoreDevicePortRequest $request, Device $device) {
        $devicePort = $device->ports()->create($request->validated());
        return new DevicePortResource($devicePort);
    }

    public function show(DevicePort $port) {
        return new DevicePortResource($port);
    }

    public function update(UpdateDevicePortRequest $request, DevicePort $port) {
        $port->update($request->validated());
        return new DevicePortResource($port);
    }

    public function destroy(DevicePort $port) {
        $port->delete();
        return response()->json(null, 204);
    }
}