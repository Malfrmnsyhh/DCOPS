<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDeviceRequest;
use App\Http\Requests\UpdateDeviceRequest;
use App\Http\Resources\DeviceResource;
use App\Models\Device;

class DeviceController extends Controller
{
    public function index()
    {
        return DeviceResource::collection(
            Device::with('deviceType')->paginate(15)
        );
    }

    public function store(StoreDeviceRequest $request)
    {
        return new DeviceResource(Device::create($request->validated()));
    }

    public function show(Device $device)
    {
        return new DeviceResource($device->load('deviceType'));
    }

    public function update(UpdateDeviceRequest $request, Device $device)
    {
        $device->update($request->validated());

        return new DeviceResource($device);
    }

    public function destroy(Device $device)
    {
        $device->delete();

        return response()->json(null, 204);
    }
}
?>