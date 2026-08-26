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

    /**
     * Tambah device baru, opsional langsung dipasang ke slot rack.
     *
     * Jika `rack_id` dan `position_u` diisi, posisi divalidasi: rentang U yang
     * dipakai (`position_u` sampai `position_u + u_size - 1`) tidak boleh
     * melebihi tinggi rack (`u_height`) dan tidak boleh tabrakan dengan
     * device lain yang sudah terpasang di rack yang sama. Error dikembalikan
     * pada field `position_u`.
     */
    public function store(StoreDeviceRequest $request)
    {
        return new DeviceResource(Device::create($request->validated()));
    }

    public function show(Device $device)
    {
        return new DeviceResource($device->load('deviceType'));
    }

    /**
     * Perbarui data device, termasuk posisi rack-nya.
     *
     * Aturan slot U sama seperti store(), device ini sendiri dikecualikan
     * dari pengecekan tabrakan supaya bisa update tanpa harus dilepas dari
     * rack dulu.
     */
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