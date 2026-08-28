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

    /**
     * Sambungkan dua port device dengan satu kabel.
     *
     * `from_port_id` dan `to_port_id` tidak boleh sama, dan masing-masing
     * port tidak boleh sedang dipakai oleh koneksi lain (baik sebagai from
     * maupun to). Error dikembalikan pada field `to_port_id`.
     */
    public function store(StorePortConnectionRequest $request) {
        return new PortConnectionResource(PortConnection::create($request->validated()));
    }

    public function show(PortConnection $portConnection) {
        return new PortConnectionResource($portConnection);
    }

    /**
     * Perbarui koneksi kabel antar dua port.
     *
     * Aturan sama seperti store(), koneksi ini sendiri dikecualikan dari
     * pengecekan port yang sudah terpakai.
     */
    public function update(UpdatePortConnectionRequest $request, PortConnection $portConnection) {
        $portConnection->update($request->validated());
        return new PortConnectionResource($portConnection);
    }

    public function destroy(PortConnection $portConnection) {
        $portConnection->delete();
        return response()->json(null, 204);
    }
}
