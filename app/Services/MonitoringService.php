<?php

namespace App\Services;

use App\Models\Server;
use App\Models\Device;
use App\Models\Rooms;
use Carbon\Carbon;

class MonitoringService
{
    public function getDashboardStats()
    {
        $totalDevices = Device::where('device_type', 'suhu')->count();
        $latestData = $this->getLatestMonitoringData();
        $stats = ['total_devices' => $totalDevices, 'normal_devices' => 0, 'warning_devices' => 0, 'critical_devices' => 0, 'device_status' => []];
        
        foreach ($latestData as $data) {
            $status = $data->status_overall ?? 'Unknown';
            if ($status === 'Normal') $stats['normal_devices']++;
            elseif ($status === 'Warning') $stats['warning_devices']++;
            else $stats['critical_devices']++;
            
            $room = Rooms::where('device_id', $data->device_key)->first();
            $stats['device_status'][] = [
                'device' => $data->device_key, 'ruang_id' => $room ? $room->room_id : null,
                'ruang_nama' => $room ? $room->room_name : 'Unknown', 'suhu' => $data->suhu,
                'kelembaban' => $data->kelembapan, 'status_suhu' => $data->status_suhu,
                'status_overall' => $status, 'waktu' => $data->created_at->format('d/m/Y H:i:s')
            ];
        }
        return $stats;
    }
    
    public function getLatestMonitoringData()
    {
        $latestData = Server::selectRaw('device_key, MAX(created_at) as latest_time')->groupBy('device_key')->get()->keyBy('device_key');
        $results = [];
        foreach ($latestData as $deviceKey => $latest) {
            $data = Server::where('device_key', $deviceKey)->where('created_at', $latest->latest_time)->first();
            if ($data) $results[] = $data;
        }
        return $results;
    }
    
    public function getFilteredData($filters)
    {
        $query = Server::query();
        if (!empty($filters['device']) && $filters['device'] !== 'Semua') $query->where('device_key', $filters['device']);
        return $query->orderBy('created_at', 'desc')->paginate(10);
    }
}