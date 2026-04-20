<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Api\Device;
use App\Models\Api\DeviceModel;
use App\Models\Api\Location;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $model = DeviceModel::where('slug', 'catalyst-2960-l')->first();
        $location = Location::where('slug', 'main-server-room')->first();

        Device::create([
            'device_model_id' => $model->id,
            'location_id' => $location->id,
            'serial_number' => 'SN-CISCO-12345',
            'inventory_number' => 'INV-BAK-001',
            'status' => 'active',
            'ip_address' => '192.168.1.10',
            'mac_address' => '00:1B:44:11:3A:B7',
            'specs' => [
                'firmware' => '15.2(E)',
                'uptime_start' => '2026-01-01'
            ]
        ]);
    }
}
