<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Rooms;
use App\Models\Device;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $totalDevices = Device::where('device_type', 'suhu')->count();
            $activeDevices = Device::where('is_active', true)->where('device_type', 'suhu')->count();
            $serverStatusData = $this->getServerStatusData();
            $temperatureDevices = $this->getTemperatureDevices();
            $iotDeviceData = $this->getIotDeviceStatus();
            $roomMonitorings = Rooms::whereNotNull('device_id')->count();
            $patientMonitorings = 0;
            
            return view('dashboard', compact(
                'totalDevices',
                'activeDevices',
                'serverStatusData',
                'temperatureDevices',
                'iotDeviceData',
                'roomMonitorings',
                'patientMonitorings'
            ));
        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            return view('dashboard')->with([
                'totalDevices' => 0,
                'activeDevices' => 0,
                'serverStatusData' => ['servers' => [], 'criticalCount' => 0, 'warningCount' => 0, 'normalCount' => 0],
                'temperatureDevices' => [],
                'iotDeviceData' => ['active' => 0, 'warning' => 0, 'inactive' => 0, 'total' => 0],
                'roomMonitorings' => 0,
                'patientMonitorings' => 0
            ]);
        }
    }
    
    public function getStats(Request $request)
    {
        try {
            $totalDevices = Device::where('device_type', 'suhu')->count();
            $activeDevices = Device::where('is_active', true)->where('device_type', 'suhu')->count();
            $roomMonitorings = Rooms::whereNotNull('device_id')->count();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'total_devices' => $totalDevices,
                    'active_devices' => $activeDevices,
                    'room_monitorings' => $roomMonitorings,
                    'patient_monitorings' => 0
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function getServerStatus(Request $request)
    {
        try {
            $data = $this->getServerStatusData();
            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    private function getServerStatusData()
    {
        $latestData = Server::select('device_key', DB::raw('MAX(created_at) as latest_time'))
            ->groupBy('device_key')
            ->get()
            ->pluck('latest_time', 'device_key');
        
        $servers = [];
        $normalCount = 0;
        $warningCount = 0;
        
        foreach ($latestData as $deviceKey => $latestTime) {
            $latestRecord = Server::where('device_key', $deviceKey)->where('created_at', $latestTime)->first();
            if ($latestRecord) {
                $status = ($latestRecord->suhu > 28) ? 'warning' : 'normal';
                if ($status === 'normal') $normalCount++;
                else $warningCount++;
                
                $device = Device::where('device_key', $deviceKey)->first();
                $room = Rooms::where('device_id', $deviceKey)->first();
                
                $servers[] = [
                    'name' => $deviceKey,
                    'location' => $room ? $room->room_name : ($latestRecord->ruang ?? 'Unknown'),
                    'temperature' => round($latestRecord->suhu, 1),
                    'humidity' => round($latestRecord->kelembapan, 1),
                    'status' => $status,
                    'last_update' => $latestRecord->created_at->diffForHumans()
                ];
            }
        }
        
        return ['servers' => $servers, 'normalCount' => $normalCount, 'warningCount' => $warningCount];
    }
    
    private function getTemperatureDevices()
    {
        $devices = Device::where('device_type', 'suhu')->where('is_active', true)->get();
        $result = [];
        
        foreach ($devices as $device) {
            $latestData = Server::where('device_key', $device->device_key)->latest()->first();
            if ($latestData) {
                $status = ($latestData->suhu > 30) ? 'critical' : (($latestData->suhu > 28) ? 'warning' : 'normal');
                $room = Rooms::where('device_id', $device->device_key)->first();
                $result[] = [
                    'device_name' => $device->device_key,
                    'room_id' => $room ? $room->room_id : 'N/A',
                    'room_name' => $room ? $room->room_name : 'Unknown',
                    'temperature' => round($latestData->suhu, 1),
                    'humidity' => round($latestData->kelembapan, 1),
                    'status' => $status,
                    'last_update' => $latestData->created_at->diffForHumans()
                ];
            }
        }
        return $result;
    }
    
    private function getIotDeviceStatus()
    {
        $totalDevices = Device::where('device_type', 'suhu')->count();
        $activeDevices = Device::where('is_active', true)->where('device_type', 'suhu')->count();
        $warningDevices = Device::where('device_type', 'suhu')->where('is_active', true)
            ->whereHas('serverData', function($query) {
                $query->where('suhu', '>', 28)->whereDate('created_at', Carbon::today());
            })->count();
        $inactiveDevices = $totalDevices - $activeDevices;
        
        return ['total' => $totalDevices, 'active' => $activeDevices, 'warning' => $warningDevices, 'inactive' => $inactiveDevices];
    }
}