<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDeviceRequest;
use App\Http\Requests\UpdateDeviceRequest;
use App\Http\Resources\DeviceResource;
use App\Models\Device;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

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
        // Bungkus dengan DB::transaction agar proses all-or-nothing
        $device = DB::transaction(function () use ($request) {
            $validated = $request->validated();
            
            // Pisahkan key 'ports' agar tidak memicu error Mass Assignment di model Device
            $deviceData = Arr::except($validated, ['ports']);
            
            // 1. Buat device terlebih dahulu
            $device = Device::create($deviceData);

            // 2. Jika ada payload ports, loop dan buat port-nya
            if (isset($validated['ports']) && is_array($validated['ports'])) {
                foreach ($validated['ports'] as $port) {
                    $device->ports()->create($port);
                }
            }

            return $device;
        });

        // Opsional: load relasi ports agar data yang baru dibuat langsung muncul di response API
        return new DeviceResource($device->load('ports'));
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