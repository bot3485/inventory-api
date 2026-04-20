<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Api\Vendor;
use App\Models\Api\DeviceType;
use Illuminate\Support\Str;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $vendors = ['Cisco', 'MikroTik', 'Ubiquiti', 'HP', 'Dell', 'Apple'];
        foreach ($vendors as $v) {
            Vendor::create(['name' => $v, 'slug' => Str::slug($v)]);
        }

        $types = [
            ['name' => 'Switch', 'icon' => 'pi-share-alt'],
            ['name' => 'Router', 'icon' => 'pi-directions'],
            ['name' => 'PC', 'icon' => 'pi-desktop'],
            ['name' => 'Camera', 'icon' => 'pi-video'],
        ];
        foreach ($types as $t) {
            DeviceType::create($t + ['slug' => Str::slug($t['name'])]);
        }
    }
}
