<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDeviceTypeRequest;
use App\Http\Requests\UpdateDeviceTypeRequest;
use App\Http\Resources\DeviceTypeResource;
use App\Models\DeviceType;

class DeviceTypeController extends Controller
{
    public function index(){
        return DeviceTypeResource::collection(DeviceType::paginate(15));
    }

    public function store(StoreDeviceTypeRequest $request) {
        return new DeviceTypeResource(DeviceType::create($request->validated()));
    }

    public function show(DeviceType $deviceType) {
        return new DeviceTypeResource($deviceType);
    }

    public function update(UpdateDeviceTypeRequest $request, DeviceType $deviceType) {
        $deviceType->update($request->validated());

        return new DeviceTypeResource($deviceType);
    }

    public function destroy(DeviceType $deviceType) {
        $deviceType->delete();

        return response()->json(null, 204);
    }
}



?>