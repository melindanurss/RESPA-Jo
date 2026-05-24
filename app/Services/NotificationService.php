<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Device;
use App\Models\Rooms;
use Carbon\Carbon;

class NotificationService
{
    public static function createTemperatureNotification($deviceKey, $suhu, $kelembaban, $oldStatus, $newStatus)
    {
        $device = Device::where('device_key', $deviceKey)->first();
        $room = Rooms::where('device_id', $deviceKey)->first();
        $deviceName = $device->name ?? $deviceKey;
        $roomName = $room ? $room->room_name : 'Unknown';
        $severity = $newStatus === 'Warning' ? 'warning' : 'info';
        $title = "Status Suhu Berubah";
        $message = "Device {$deviceName} di {$roomName} berubah dari {$oldStatus} ke {$newStatus}. Suhu: {$suhu}°C, Kelembaban: {$kelembaban}%";
        $data = ['device_key' => $deviceKey, 'device_name' => $deviceName, 'room_id' => $room ? $room->room_id : null,
            'room_name' => $roomName, 'suhu' => $suhu, 'kelembaban' => $kelembaban, 'old_status' => $oldStatus,
            'new_status' => $newStatus, 'action_url' => '/monitoring/suhu?device=' . $deviceKey];
        
        return Notification::create(['type' => 'suhu', 'title' => $title, 'message' => $message, 'data' => $data,
            'device_key' => $deviceKey, 'room_id' => $room ? $room->room_id : null, 'severity' => $severity]);
    }
    
    public static function getUnreadCount()
    {
        return Notification::where('is_read', false)->count();
    }
    
    public static function getLatestNotifications($limit = 10)
    {
        return Notification::orderBy('created_at', 'desc')->limit($limit)->get();
    }
}