<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Api\DeviceModel;
use App\Models\Api\Vendor;
use App\Models\Api\DeviceType;

class DeviceModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $cisco = Vendor::where('slug', 'cisco')->first();
        $switch = DeviceType::where('slug', 'switch')->first();

        DeviceModel::create([
            'vendor_id' => $cisco->id,
            'device_type_id' => $switch->id,
            'name' => 'Catalyst 2960-L',
            'slug' => 'catalyst-2960-l',
            'specs_template' => [
                'ports_count' => 24,
                'uplink_speed' => '1Gbps',
                'poe_support' => false,
                'rack_units' => '1U'
            ]
        ]);
    }
}
