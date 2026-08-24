<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlarmResource;
use App\Models\Alarm;
use Illuminate\Http\Request;

class AlarmController extends Controller
{
    public function index(Request $request)
    {
        $query = Alarm::query();

        if ($request->query('status')) {
            $query->where('status', $request->query('status'));
        }

        return AlarmResource::collection($query->orderByDesc('triggered_at')->paginate(15));
    }

    public function acknowledge(Request $request, Alarm $alarm)
    {
        $alarm->update([
            'status' => 'acknowledged',
            'acknowledged_by' => $request->user()->id,
        ]);

        return new AlarmResource($alarm);
    }

    public function resolve(Alarm $alarm)
    {
        $alarm->update([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);

        return new AlarmResource($alarm);
    }
}
