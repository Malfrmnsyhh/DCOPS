<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAlarmRuleRequest;
use App\Http\Requests\UpdateAlarmRuleRequest;
use App\Http\Resources\AlarmRuleResource;
use App\Models\AlarmRule;

class AlarmRuleController extends Controller
{
    public function index()
    {
        return AlarmRuleResource::collection(AlarmRule::paginate(15));
    }

    public function store(StoreAlarmRuleRequest $request)
    {
        return new AlarmRuleResource(AlarmRule::create($request->validated()));
    }

    public function show(AlarmRule $alarmRule)
    {
        return new AlarmRuleResource($alarmRule);
    }

    public function update(UpdateAlarmRuleRequest $request, AlarmRule $alarmRule)
    {
        $alarmRule->update($request->validated());

        return new AlarmRuleResource($alarmRule);
    }

    public function destroy(AlarmRule $alarmRule)
    {
        $alarmRule->delete();

        return response()->json(null, 204);
    }
}
