<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSensorRequest;
use App\Http\Requests\UpdateSensorRequest;
use App\Http\Resources\SensorResource;
use App\Models\Rack;
use App\Models\Sensor;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

    public function readings(Request $request, Sensor $sensor)
    {
        $from = $request->query('from') ? Carbon::parse($request->query('from')) : now()->subDay();
        $to = $request->query('to') ? Carbon::parse($request->query('to')) : now();

        $query = $sensor->readings()->whereBetween('recorded_at', [$from, $to]);

        if ($request->query('aggregate') === 'hourly') {
            $readings = $query
                ->selectRaw('DATE_FORMAT(recorded_at, "%Y-%m-%d %H:00:00") as bucket, AVG(value) as avg_value, COUNT(*) as total')
                ->groupBy('bucket')
                ->orderBy('bucket')
                ->get();

            return response()->json([
                'data' => $readings->map(fn($row) => [
                    'bucket' => $row->bucket,
                    'avg_value' => round((float) $row->avg_value, 2),
                    'total' => (int) $row->total,
                ]),
            ]);
        }

        return response()->json([
            'data' => $query->orderBy('recorded_at')->get(['id', 'value', 'recorded_at']),
        ]);
    }
}
