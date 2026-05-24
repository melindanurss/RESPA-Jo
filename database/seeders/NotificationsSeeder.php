<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use Carbon\Carbon;

class NotificationsSeeder extends Seeder
{
    public function run(): void
    {
        $notifications = [
            [
                'type' => 'suhu',
                'title' => 'Suhu Server Room Tinggi',
                'message' => 'Device wemos1 di Server Room menunjukkan suhu 32°C (di atas batas normal 27°C)',
                'data' => [
                    'device_key' => 'wemos1',
                    'device_name' => 'wemos1',
                    'room_id' => 'SVR',
                    'room_name' => 'Server Room',
                    'suhu' => 32,
                    'kelembaban' => 45,
                    'action_url' => '/monitoring/suhu?device=wemos1'
                ],
                'device_key' => 'wemos1',
                'room_id' => 'SVR',
                'severity' => 'warning',
                'created_at' => Carbon::now()->subHours(2)
            ],
            [
                'type' => 'infus',
                'title' => 'Volume Infus Rendah',
                'message' => 'Infus device123 untuk pasien Budi Santoso tersisa 15ml',
                'data' => [
                    'device_key' => 'device123',
                    'device_name' => 'Infus Device 123',
                    'patient_name' => 'Budi Santoso',
                    'room' => 'ICU',
                    'volume_remaining' => 15,
                    'action_url' => '/monitoring/infus?device=device123'
                ],
                'device_key' => 'device123',
                'severity' => 'warning',
                'created_at' => Carbon::now()->subHours(1)
            ],
            [
                'type' => 'suhu',
                'title' => 'Kembali Normal',
                'message' => 'Device nodemcu1 di Ruang Direksi kembali normal (25°C)',
                'data' => [
                    'device_key' => 'nodemcu1',
                    'device_name' => 'nodemcu1',
                    'room_id' => 'DIREK',
                    'room_name' => 'Ruang Direksi',
                    'suhu' => 25,
                    'kelembaban' => 55,
                    'action_url' => '/monitoring/suhu?device=nodemcu1'
                ],
                'device_key' => 'nodemcu1',
                'room_id' => 'DIREK',
                'severity' => 'info',
                'is_read' => true,
                'read_at' => Carbon::now()->subMinutes(30),
                'created_at' => Carbon::now()->subMinutes(45)
            ]
        ];
        
        foreach ($notifications as $notification) {
            Notification::create($notification);
        }
    }
}