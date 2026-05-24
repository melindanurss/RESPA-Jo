<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Server;
use App\Models\Rooms;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DashboardSuhuController extends Controller
{
    public function index()
    {
        $stats = $this->getDashboardStats();
        $serverStatusData = $this->getServerStatusData();
        
        return view('dashboard', [
            'serverStatusData' => $serverStatusData,
            'totalDevices' => $stats['totalDevices'],
            'activeDevices' => $stats['activeDevices'],
            'roomMonitorings' => $stats['roomMonitorings'],
            'patientMonitorings' => 0,
            'lastUpdated' => $stats['lastUpdated']
        ]);
    }
    
    public function getTemperatureRealtime(Request $request)
    {
        try {
            $range = $request->get('range', '1h');
            $endDate = Carbon::now();
            
            switch ($range) {
                case '1h': $startDate = $endDate->copy()->subHour(); break;
                case '6h': $startDate = $endDate->copy()->subHours(6); break;
                case '24h': $startDate = $endDate->copy()->subDay(); break;
                default: $startDate = $endDate->copy()->subHour();
            }
            
            $data = Server::whereBetween('created_at', [$startDate, $endDate])->orderBy('created_at')->get(['suhu', 'created_at']);
            $labels = [];
            $values = [];
            $currentTemp = 0;
            
            foreach ($data as $item) {
                $labels[] = $item->created_at->format('H:i');
                $values[] = floatval($item->suhu);
                $currentTemp = floatval($item->suhu);
            }
            
            if (empty($values)) {
                $labels = $this->generateSampleLabels($range);
                $values = $this->generateSampleValues(25, 30, count($labels));
                $currentTemp = end($values);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'labels' => $labels, 'values' => $values, 'current' => $currentTemp,
                    'min' => min($values), 'max' => max($values),
                    'avg' => array_sum($values) / count($values),
                    'range' => $range, 'last_updated' => now()->toISOString()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Temperature realtime error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data suhu'], 500);
        }
    }
    
    public function getHumidityRealtime(Request $request)
    {
        try {
            $range = $request->get('range', '1h');
            $endDate = Carbon::now();
            
            switch ($range) {
                case '1h': $startDate = $endDate->copy()->subHour(); break;
                case '6h': $startDate = $endDate->copy()->subHours(6); break;
                case '24h': $startDate = $endDate->copy()->subDay(); break;
                default: $startDate = $endDate->copy()->subHour();
            }
            
            $data = Server::whereBetween('created_at', [$startDate, $endDate])->orderBy('created_at')->get(['kelembapan', 'created_at']);
            $labels = [];
            $values = [];
            $currentHumidity = 0;
            
            foreach ($data as $item) {
                $labels[] = $item->created_at->format('H:i');
                $values[] = floatval($item->kelembapan);
                $currentHumidity = floatval($item->kelembapan);
            }
            
            if (empty($values)) {
                $labels = $this->generateSampleLabels($range);
                $values = $this->generateSampleValues(50, 70, count($labels));
                $currentHumidity = end($values);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'labels' => $labels, 'values' => $values, 'current' => $currentHumidity,
                    'min' => min($values), 'max' => max($values),
                    'avg' => array_sum($values) / count($values),
                    'range' => $range, 'last_updated' => now()->toISOString()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Humidity realtime error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data kelembaban'], 500);
        }
    }
    
    public function getDeviceStatus()
    {
        try {
            $devices = Device::where('is_active', true)->where('device_type', 'suhu')->get();
            $statusData = ['total' => $devices->count(), 'online' => 0, 'offline' => 0, 'by_type' => []];
            
            foreach ($devices as $device) {
                $lastData = Server::where('device_key', $device->device_key)->where('created_at', '>=', Carbon::now()->subMinutes(5))->first();
                if ($lastData) $statusData['online']++;
                else $statusData['offline']++;
                
                if (!isset($statusData['by_type'][$device->device_type])) $statusData['by_type'][$device->device_type] = 0;
                $statusData['by_type'][$device->device_type]++;
            }
            
            return response()->json(['success' => true, 'data' => $statusData]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil status device'], 500);
        }
    }
    
    public function getDashboardStatsApi()
    {
        try {
            $stats = $this->getDashboardStats();
            return response()->json(['success' => true, 'data' => $stats]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil statistik'], 500);
        }
    }
    
    public function getServerStatus()
    {
        try {
            $data = $this->getServerStatusData();
            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil status server'], 500);
        }
    }
    
    public function getTemperatureDevicesStatus()
    {
        try {
            $devices = Device::where('device_type', 'suhu')->where('is_active', true)->get();
            $deviceData = [];
            
            foreach ($devices as $device) {
                $latestData = Server::where('device_key', $device->device_key)->orderBy('created_at', 'desc')->first();
                $room = Rooms::where('device_id', $device->device_key)->first();
                
                if ($latestData) {
                    $temperatureStatus = ($latestData->suhu <= 27) ? 'Normal' : 'Warning';
                    $humidityStatus = ($latestData->kelembapan <= 60) ? 'Normal' : 'Warning';
                    $overallStatus = ($temperatureStatus === 'Normal' && $humidityStatus === 'Normal') ? 'Normal' : 'Warning';
                    
                    $deviceData[] = [
                        'device_key' => $device->device_key,
                        'device_name' => $device->name ?? $device->device_key,
                        'room_id' => $room ? $room->room_id : '-',
                        'room_name' => $room ? $room->room_name : 'Belum ditetapkan',
                        'temperature' => number_format($latestData->suhu, 1),
                        'humidity' => number_format($latestData->kelembapan, 1),
                        'status' => $overallStatus,
                        'status_display' => $overallStatus,
                        'status_class' => ($overallStatus === 'Normal') ? 'status-normal' : 'status-warning',
                        'temperature_status' => $temperatureStatus,
                        'humidity_status' => $humidityStatus,
                        'last_update' => $latestData->created_at->format('d/m/Y H:i:s'),
                        'time_ago' => $this->getTimeAgo($latestData->created_at),
                        'is_online' => $this->isDeviceOnline($latestData->created_at)
                    ];
                } else {
                    $deviceData[] = [
                        'device_key' => $device->device_key,
                        'device_name' => $device->name ?? $device->device_key,
                        'room_id' => $room ? $room->room_id : '-',
                        'room_name' => $room ? $room->room_name : 'Belum ditetapkan',
                        'temperature' => '--',
                        'humidity' => '--',
                        'status' => 'offline',
                        'status_display' => 'Offline',
                        'status_class' => 'status-offline',
                        'temperature_status' => 'Unknown',
                        'humidity_status' => 'Unknown',
                        'last_update' => 'Belum ada data',
                        'time_ago' => 'Never',
                        'is_online' => false
                    ];
                }
            }
            
            return response()->json([
                'success' => true,
                'data' => $deviceData,
                'total' => count($deviceData),
                'online_count' => collect($deviceData)->where('is_online', true)->count(),
                'offline_count' => collect($deviceData)->where('is_online', false)->count(),
                'last_updated' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            Log::error('Temperature devices status error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengambil status device suhu'], 500);
        }
    }
    
    private function getDashboardStats()
    {
        try {
            return [
                'totalDevices' => Device::where('device_type', 'suhu')->where('is_active', true)->count(),
                'activeDevices' => Device::where('is_active', true)->where('device_type', 'suhu')->count(),
                'roomMonitorings' => Rooms::whereNotNull('device_id')->count(),
                'patientMonitorings' => 0,
                'lastUpdated' => now()->format('d/m/Y H:i:s')
            ];
        } catch (\Exception $e) {
            return ['totalDevices' => 0, 'activeDevices' => 0, 'roomMonitorings' => 0, 'patientMonitorings' => 0, 'lastUpdated' => now()->format('d/m/Y H:i:s')];
        }
    }
    
    private function getServerStatusData()
    {
        try {
            $devices = Device::where('device_type', 'suhu')->where('is_active', true)->get();
            $servers = [];
            $normalCount = 0;
            $warningCount = 0;
            
            foreach ($devices as $device) {
                $latestData = Server::where('device_key', $device->device_key)->orderBy('created_at', 'desc')->first();
                if ($latestData) {
                    $room = Rooms::where('device_id', $device->device_key)->first();
                    $status = ($latestData->suhu <= 27) ? 'normal' : 'warning';
                    if ($status === 'normal') $normalCount++;
                    else $warningCount++;
                    
                    $servers[] = [
                        'name' => $device->name ?? $device->device_key,
                        'location' => $room ? $room->room_name : 'Unknown',
                        'temperature' => number_format($latestData->suhu, 1),
                        'status' => $status,
                        'last_update' => $latestData->created_at->format('H:i')
                    ];
                }
            }
            return ['servers' => $servers, 'normalCount' => $normalCount, 'warningCount' => $warningCount, 'total' => count($servers)];
        } catch (\Exception $e) {
            return ['servers' => [], 'normalCount' => 0, 'warningCount' => 0, 'total' => 0];
        }
    }
    
    private function getTimeAgo($timestamp)
    {
        $now = Carbon::now();
        $time = Carbon::parse($timestamp);
        $diff = $now->diffInSeconds($time);
        if ($diff < 60) return 'Baru saja';
        if ($diff < 3600) return floor($diff / 60) . ' menit lalu';
        if ($diff < 86400) return floor($diff / 3600) . ' jam lalu';
        return floor($diff / 86400) . ' hari lalu';
    }
    
    private function isDeviceOnline($lastUpdate)
    {
        return Carbon::parse($lastUpdate)->greaterThan(Carbon::now()->subMinutes(5));
    }
    
    private function generateSampleLabels($range)
    {
        $now = Carbon::now();
        $labels = [];
        switch ($range) {
            case '1h':
                for ($i = 59; $i >= 0; $i -= 5) $labels[] = $now->copy()->subMinutes($i)->format('H:i');
                break;
            case '6h':
                for ($i = 6; $i >= 0; $i--) $labels[] = $now->copy()->subHours($i)->format('H:i');
                break;
            case '24h':
                for ($i = 24; $i >= 0; $i -= 3) $labels[] = $now->copy()->subHours($i)->format('H:i');
                break;
            default:
                for ($i = 11; $i >= 0; $i--) $labels[] = $now->copy()->subMinutes($i * 5)->format('H:i');
        }
        return $labels;
    }
    
    private function generateSampleValues($min, $max, $count)
    {
        $values = [];
        for ($i = 0; $i < $count; $i++) $values[] = round(rand($min * 10, $max * 10) / 10, 1);
        return $values;
    }
}