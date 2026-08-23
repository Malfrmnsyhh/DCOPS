<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubnetRequest;
use App\Http\Requests\UpdateSubnetRequest;
use App\Http\Resources\SubnetResource;
use App\Models\Subnet;
use App\Services\IpamService;

class SubnetController extends Controller
{
    public function index() {
        return SubnetResource::collection(Subnet::paginate(15));
    }

    public function store(StoreSubnetRequest $request, IpamService $ipamService) {
        $subnet = Subnet::create($request->validated());

        $addresses = $ipamService->generateAddresses($subnet->cidr);
        $ipamService->bulkCreateAddresses($subnet->id, $addresses);

        return new SubnetResource($subnet);
    }

    public function show(Subnet $subnet) {
        return new SubnetResource($subnet);
    }

    public function update(UpdateSubnetRequest $request, Subnet $subnet) {
        $subnet->update($request->validated());

        return new SubnetResource($subnet);
    }

    public function destroy(Subnet $subnet) {
        $subnet->delete();

        return response()->json(null, 204);
    }

    public function utilization(Subnet $subnet) {
        $total = $subnet->ipAddresses()->count();
        $assigned = $subnet->ipAddresses()->where('status', 'assigned')->count();
        $available = $subnet->ipAddresses()->where('status', 'available')->count();
        $reserved = $subnet->ipAddresses()->where('status', 'reserved')->count();

        return response()->json([
            'data' => [
                'subnet_id' => $subnet->id,
                'cidr' => $subnet->cidr,
                'total' => $total,
                'assigned' => $assigned,
                'available' => $available,
                'reserved' => $reserved,
                'utilization_percent' => $total > 0 ? round($assigned / $total * 100, 2) : 0,
            ],
        ]);
    }
}
