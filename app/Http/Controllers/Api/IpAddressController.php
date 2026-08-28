<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignIpAddressRequest;
use App\Http\Resources\IpAddressResource;
use App\Models\IpAddress;
use App\Models\Subnet;

class IpAddressController extends Controller
{
    public function index(Subnet $subnet) {
        return IpAddressResource::collection($subnet->ipAddresses()->paginate(20));
    }

    public function show(IpAddress $ipAddress) {
        return new IpAddressResource($ipAddress);
    }

    /**
     * Assign sebuah IP address ke port device.
     *
     * IP hanya bisa di-assign kalau statusnya masih "available", dan port
     * tujuan tidak boleh sudah punya IP lain yang berstatus "assigned" —
     * harus di-release dulu sebelum assign yang baru.
     */
    public function assign(AssignIpAddressRequest $request, IpAddress $ipAddress) {
        $ipAddress->update([
            'device_port_id' => $request->device_port_id,
            'hostname' => $request->hostname,
            'status' => 'assigned',
            'assigned_at' => now(),
        ]);

        return new IpAddressResource($ipAddress);
    }

    public function release(IpAddress $ipAddress) {
        $ipAddress->update([
            'device_port_id' => null,
            'hostname' => null,
            'status' => 'available',
            'assigned_at' => null,
        ]);

        return new IpAddressResource($ipAddress);
    }
}
