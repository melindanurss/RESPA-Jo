<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;

class DevicesSeeder extends Seeder
{
    public function run(): void
    {
        $devices = [
            [
                'device_key' => 'nodemcu1',
                'name' => 'NodeMCU Sensor 1',
                'device_type' => 'suhu',
                'is_active' => true,
                'description' => 'Sensor suhu dan kelembaban di Ruang Direksi',
                'location' => 'Ruang Direksi',
            ],
            [
                'device_key' => 'wemos1',
                'name' => 'WeMos D1 Mini',
                'device_type' => 'suhu',
                'is_active' => true,
                'description' => 'Sensor suhu dan kelembaban di Server Room',
                'location' => 'Server Room',
            ],
        ];
        
        foreach ($devices as $device) {
            Device::updateOrCreate(
                ['device_key' => $device['device_key']],
                $device
            );
        }
    }
}