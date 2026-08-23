<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\API\SiteController;
use App\Http\Controllers\API\RoomController;
use App\Http\Controllers\Api\RackController;
use App\Http\Controllers\Api\DeviceTypeController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\DevicePortController;
use App\Http\Controllers\Api\PortConnectionController;
use App\Http\Controllers\Api\SubnetController;
use App\Http\Controllers\Api\IpAddressController;
use App\Http\Controllers\Api\SensorController;
use App\Http\Controllers\Api\AlarmRuleController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me', [AuthController::class, 'me']);

        Route::apiResource('sites', SiteController::class);
        Route::apiResource('sites.rooms', RoomController::class)->shallow();
        Route::apiResource('rooms.racks', RackController::class)->shallow();
        Route::get('racks/{rack}/elevation', [RackController::class, 'elevation']);

        Route::apiResource('device-types', DeviceTypeController::class);
        Route::apiResource('devices', DeviceController::class);
        Route::apiResource('devices.ports', DevicePortController::class)->shallow();
        Route::apiResource('port-connections', PortConnectionController::class);
        Route::apiResource('subnets', SubnetController::class);
        Route::get('subnets/{subnet}/utilization', [SubnetController::class, 'utilization']);
        Route::get('subnets/{subnet}/ip-addresses', [IpAddressController::class, 'index']);
        Route::get('ip-addresses/{ip_address}', [IpAddressController::class, 'show']);
        Route::post('ip-addresses/{ip_address}/assign', [IpAddressController::class, 'assign']);
        Route::post('ip-addresses/{ip_address}/release', [IpAddressController::class, 'release']);
        Route::apiResource('racks.sensors', SensorController::class)->shallow();
        Route::apiResource('alarm-rules', AlarmRuleController::class);
    });
});
