<?php

namespace App\Services;

use App\Models\Device;
use App\Models\Rooms;
use App\Models\Server;

class StatusDeviceService
{
    public function getDeviceStatus()
    {
        $devices = Device::where('device_type', 'suhu')->where('is_active', true)->get();
        $statusData = [];
        
        foreach ($devices as $device) {
            $room = Rooms::where('device_id', $device->device_key)->first();
            $latestMonitoring = Server::where('device_key', $device->device_key)->orderBy('created_at', 'desc')->first();
            
            if ($latestMonitoring) {
                $statusSuhu = $this->getSuhuStatus($latestMonitoring->suhu);
                $statusKelembaban = $this->getKelembabanStatus($latestMonitoring->kelembapan);
                $statusOverall = $this->getOverallStatus($statusSuhu, $statusKelembaban);
                
                $statusData[] = [
                    'device' => $device->device_key, 'device_name' => $device->name,
                    'ruang_id' => $room ? $room->room_id : '-', 'ruang_nama' => $room ? $room->room_name : 'Belum ditetapkan',
                    'suhu' => $latestMonitoring->suhu, 'kelembaban' => $latestMonitoring->kelembapan,
                    'status_suhu' => $statusSuhu, 'status_kelembaban' => $statusKelembaban,
                    'status_overall' => $statusOverall, 'waktu' => $latestMonitoring->created_at->format('d/m/Y H:i:s')
                ];
            }
        }
        return $statusData;
    }

    private function getSuhuStatus($suhu)
    {
        if ($suhu < 18) return "Terlalu Dingin";
        if ($suhu <= 27) return "Normal";
        return "Panas";
    }

    private function getKelembabanStatus($kelembaban)
    {
        if ($kelembaban < 30) return "Kering";
        if ($kelembaban <= 60) return "Normal";
        return "Lembab";
    }

    private function getOverallStatus($statusSuhu, $statusKelembaban)
    {
        if ($statusSuhu !== 'Normal' || $statusKelembaban !== 'Normal') return 'Warning';
        return 'Normal';
    }

    public function getDashboardStats()
    {
        $devices = Device::where('device_type', 'suhu')->where('is_active', true)->get();
        $stats = ['total_devices' => $devices->count(), 'normal_devices' => 0, 'warning_devices' => 0, 'critical_devices' => 0];
        
        foreach ($devices as $device) {
            $latest = Server::where('device_key', $device->device_key)->orderBy('created_at', 'desc')->first();
            if ($latest) {
                $statusSuhu = $this->getSuhuStatus($latest->suhu);
                $statusKelembaban = $this->getKelembabanStatus($latest->kelembapan);
                $statusOverall = $this->getOverallStatus($statusSuhu, $statusKelembaban);
                if ($statusOverall === 'Normal') $stats['normal_devices']++;
                elseif ($statusOverall === 'Warning') $stats['warning_devices']++;
                else $stats['critical_devices']++;
            }
        }
        return $stats;
    }
}