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
use App\Http\Controllers\BME280Controller;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing Page & Authentication
Route::get('/', [LandingController::class, 'index'])->name('landing.index');
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// API External
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

// ============================================
// ROUTES UNTUK BME280 (SUHU, KELEMBABAN, TEKANAN UDARA)
// ============================================

// Endpoint untuk menerima data dari sensor BME280
Route::post('/monitoring/bme280', [SuhuController::class, 'storeBME280']);

// Web view untuk monitoring BME280
Route::get('/monitoring/bme280', [BME280Controller::class, 'index'])->name('monitoring.bme280')->middleware(['auth']);

// API Routes untuk BME280
Route::prefix('monitoring/bme280')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [BME280Controller::class, 'getDashboardData']);
    Route::get('/data', [BME280Controller::class, 'getMonitoringData']);
    Route::get('/export-stats', [BME280Controller::class, 'getExportStats']);
    Route::post('/export', [BME280Controller::class, 'exportData']);
    Route::get('/devices-filter', [BME280Controller::class, 'getDevicesFilter']);
});

// ============================================
// ROUTES UNTUK DHT22 (SUHU DAN KELEMBABAN SAJA)
// ============================================

Route::post('/monitoringsuhu', [SuhuController::class, 'store']);

// ============================================
// ROUTES UNTUK SERVER MONITORING
// ============================================

Route::get('/server-monitoring/servers', [MonitoringServerController::class, 'getServers']);
Route::get('/server-monitoring/overview', [MonitoringServerController::class, 'getOverview']);
Route::post('/server', [ServerMonitoringController::class, 'store']);

// ============================================
// ROUTES DENGAN MIDDLEWARE AUTH
// ============================================

Route::middleware(['auth'])->group(function () {
    
    // Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // API Dashboard
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
    
    // Monitoring Suhu (DHT22)
    Route::get('/monitoring/suhu', [Monitoring2Controller::class, 'suhuIndex'])->name('monitoring.suhu');
    
    Route::prefix('monitoring/suhu')->group(function () {
        Route::get('/', [Monitoring2Controller::class, 'suhuIndex']);
        Route::get('/data', [Monitoring2Controller::class, 'getMonitoringData']);
        Route::get('/dashboard', [Monitoring2Controller::class, 'getDashboardData']);
        Route::get('/total-data', [Monitoring2Controller::class, 'getTotalData']);
        Route::get('/export-stats', [Monitoring2Controller::class, 'getExportStats']);
        Route::post('/export', [Monitoring2Controller::class, 'exportData']);
        Route::get('/devices-filter', [Monitoring2Controller::class, 'getDevicesFilter']);
        Route::get('/rooms-filter', [Monitoring2Controller::class, 'getRoomsFilter']);
    });
    
    // API Monitoring
    Route::prefix('api/monitoring')->group(function () {
        Route::get('/dashboard', [Monitoring2Controller::class, 'getDashboardData']);
        Route::get('/total-data', [Monitoring2Controller::class, 'getTotalData']);
        Route::get('/logs', [Monitoring2Controller::class, 'getMonitoringData']);
        Route::get('/devices-filter', [Monitoring2Controller::class, 'getDevicesFilter']);
        Route::get('/rooms-filter', [Monitoring2Controller::class, 'getRoomsFilter']);
        Route::get('/export-stats', [Monitoring2Controller::class, 'getExportStats']);
        Route::post('/export', [Monitoring2Controller::class, 'exportData']);
    });
    
    // CRUD Device
    Route::prefix('device')->group(function () {
        Route::get('/', [DeviceController::class, 'index'])->name('device.index');
        Route::post('/', [DeviceController::class, 'store'])->name('device.store');
        Route::get('/{id}', [DeviceController::class, 'show'])->name('device.show');
        Route::put('/{id}', [DeviceController::class, 'update'])->name('device.update');
        Route::delete('/{id}', [DeviceController::class, 'destroy'])->name('device.destroy');
    });
    
    // CRUD Rooms
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
    
    // API Notifications
    Route::prefix('api')->group(function () {
        Route::get('/notifications/count', [NotificationController::class, 'getUnreadCount']);
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    });
    
    // API Device Status
    Route::prefix('api')->group(function () {
        Route::get('/device-status/dashboard', [StatusDeviceController::class, 'getDashboardStats']);
        Route::get('/device-status/current', [StatusDeviceController::class, 'getDeviceStatus']);
    });
    
    // Reset WiFi Device
    Route::post('/api/devices/{deviceKey}/reset-wifi', [RoomsController::class, 'resetWifiForDevice']);
});

// Fallback Route
Route::fallback(function () {
    return redirect()->route('login');
});