<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\API\SiteController;
use App\Http\Controllers\API\RoomController;
use App\Http\Controllers\Api\RackController;
use App\Http\Controllers\Api\DeviceTypeController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\DevicePortController;
use App\Http\Controllers\Api\PortConnectionController;

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

    });
});

?>