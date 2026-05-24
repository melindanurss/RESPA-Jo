<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuhuController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RoomsController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardSuhuController;
use App\Http\Controllers\Monitoring2Controller;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StatusDeviceController;
use App\Http\Controllers\ServerMonitoringController;

Route::get('/', [LandingController::class, 'index'])->name('landing.index');

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/api/ruangan', function () {
    try {
        $response = Http::timeout(30)->get('http://magang.rsparumanguharjo.com/api/ruangan');
        if ($response->successful()) {
            return $response->json();
        }
        return response()->json(['ok' => false, 'error' => 'Gagal mengambil data ruangan'], 500);
    } catch (\Exception $e) {
        return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
    }
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::prefix('api/dashboard')->group(function () {
        Route::get('/temperature-realtime', [DashboardSuhuController::class, 'getTemperatureRealtime']);
        Route::get('/humidity-realtime', [DashboardSuhuController::class, 'getHumidityRealtime']);
        Route::get('/device-status', [DashboardSuhuController::class, 'getDeviceStatus']);
        Route::get('/stats', [DashboardSuhuController::class, 'getDashboardStatsApi']);
        Route::get('/server-status', [DashboardSuhuController::class, 'getServerStatus']);
        Route::get('/temperature-devices-status', [DashboardSuhuController::class, 'getTemperatureDevicesStatus']);
        Route::get('/summary', [Monitoring2Controller::class, 'getDashboardSummary']);
        Route::get('/devices', [Monitoring2Controller::class, 'getDevicesForDashboard']);
        Route::get('/alerts', [Monitoring2Controller::class, 'getRecentAlertsForDashboard']);
        Route::get('/charts/temperature/{period}', [Monitoring2Controller::class, 'getTemperatureChartForDashboard']);
        Route::get('/charts/status-distribution', [Monitoring2Controller::class, 'getStatusDistributionForDashboard']);
    });

    Route::get('/monitoring/suhu', [Monitoring2Controller::class, 'suhuIndex'])->name('monitoring.suhu');
    
    Route::prefix('monitoring/suhu')->group(function () {
        Route::get('/', [Monitoring2Controller::class, 'suhuIndex'])->name('monitoring.suhu.index');
        Route::get('/data', [Monitoring2Controller::class, 'getMonitoringData'])->name('monitoring.suhu.data');
        Route::get('/dashboard', [Monitoring2Controller::class, 'getDashboardData'])->name('monitoring.suhu.dashboard');
        Route::get('/total-data', [Monitoring2Controller::class, 'getTotalData'])->name('monitoring.suhu.total-data');
        Route::get('/export-stats', [Monitoring2Controller::class, 'getExportStats'])->name('monitoring.suhu.export-stats');
        Route::post('/export', [Monitoring2Controller::class, 'exportData'])->name('monitoring.suhu.export');
        Route::get('/devices-filter', [Monitoring2Controller::class, 'getDevicesFilter'])->name('monitoring.suhu.devices-filter');
        Route::get('/rooms-filter', [Monitoring2Controller::class, 'getRoomsFilter'])->name('monitoring.suhu.rooms-filter');
    });

    Route::prefix('api/monitoring')->group(function () {
        Route::get('/dashboard', [Monitoring2Controller::class, 'getDashboardData']);
        Route::get('/total-data', [Monitoring2Controller::class, 'getTotalData']);
        Route::get('/logs', [Monitoring2Controller::class, 'getMonitoringData']);
        Route::get('/devices-filter', [Monitoring2Controller::class, 'getDevicesFilter']);
        Route::get('/rooms-filter', [Monitoring2Controller::class, 'getRoomsFilter']);
        Route::get('/export-stats', [Monitoring2Controller::class, 'getExportStats']);
        Route::post('/export', [Monitoring2Controller::class, 'exportData']);
    });

    Route::prefix('device')->group(function () {
        Route::get('/', [DeviceController::class, 'index'])->name('device.index');
        Route::post('/', [DeviceController::class, 'store'])->name('device.store');
        Route::get('/{id}', [DeviceController::class, 'show'])->name('device.show');
        Route::put('/{id}', [DeviceController::class, 'update'])->name('device.update');
        Route::delete('/{id}', [DeviceController::class, 'destroy'])->name('device.destroy');
    });

    Route::prefix('rooms')->group(function () {
        Route::get('/', [RoomsController::class, 'index'])->name('rooms.index');
        Route::get('/list', [RoomsController::class, 'list'])->name('rooms.list');
        Route::post('/', [RoomsController::class, 'store'])->name('rooms.store');
        Route::get('/{id}', [RoomsController::class, 'show'])->name('rooms.show');
        Route::put('/{id}', [RoomsController::class, 'update'])->name('rooms.update');
        Route::delete('/{id}', [RoomsController::class, 'destroy'])->name('rooms.destroy');
        Route::post('/refresh', [RoomsController::class, 'refresh'])->name('rooms.refresh');
        Route::get('/statistics', [RoomsController::class, 'statistics'])->name('rooms.statistics');
    });

    Route::prefix('api')->group(function () {
        Route::get('/notifications/count', [NotificationController::class, 'getUnreadCount']);
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    });

    Route::prefix('api')->group(function () {
        Route::get('/device-status/dashboard', [StatusDeviceController::class, 'getDashboardStats']);
        Route::get('/device-status/current', [StatusDeviceController::class, 'getDeviceStatus']);
    });
});

Route::fallback(function () {
    return redirect()->route('login');
});