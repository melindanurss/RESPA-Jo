<?php

use App\Http\Controllers\SuhuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\MonitoringServerController;
use App\Http\Controllers\ServerMonitoringController;
use App\Http\Controllers\StatusDeviceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardSuhuController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Server;

Route::post('/monitoringsuhu', [SuhuController::class, 'store']);

Route::get('/server-monitoring/servers', [MonitoringServerController::class, 'getServers']);
Route::get('/server-monitoring/overview', [MonitoringServerController::class, 'getOverview']);
Route::post('/server', [ServerMonitoringController::class, 'store']);
Route::post('/TambahUser', [UserController::class, 'store']);
Route::post('/devices', [DeviceController::class, 'store']);

Route::prefix('dashboard')->group(function () {
    Route::get('/temperature-realtime', [DashboardSuhuController::class, 'getTemperatureRealtime']);
    Route::get('/humidity-realtime', [DashboardSuhuController::class, 'getHumidityRealtime']);
    Route::get('/device-status', [DashboardSuhuController::class, 'getDeviceStatus']);
    Route::get('/temperature-devices-status', [DashboardSuhuController::class, 'getTemperatureDevicesStatus']);
    Route::get('/stats', [DashboardController::class, 'getStats']);
    Route::get('/server-status', [DashboardController::class, 'getServerStatus']);
});

Route::get('/test-servers', function() {
    return response()->json(Server::all());
});

Route::get('/test-overview', function() {
    return response()->json(['total' => Server::count(), 'message' => 'API bekerja!']);
});

Route::middleware(['auth'])->group(function () {
    Route::prefix('device-status')->group(function () {
        Route::get('/dashboard', [StatusDeviceController::class, 'getDashboardStats']);
        Route::get('/current', [StatusDeviceController::class, 'getDeviceStatus']);
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});