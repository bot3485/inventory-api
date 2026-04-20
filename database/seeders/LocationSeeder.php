<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/LocationSeeder.php

    public function run(): void
    {
        // 1. Создаем головной офис
        $office = \App\Models\Api\Location::create([
            'name' => 'Baku Head Office',
            'slug' => 'baku-head-office',
            'prefix' => 'BAK',
            'type' => 'building',
            'address' => '28 May Street, Baku',
            'metadata' => [
                'map_url' => 'https://goo.gl/maps/example',
                'media' => [
                    ['type' => 'image', 'url' => 'https://cdn.link/office_front.jpg', 'label' => 'Main Entrance'],
                    ['type' => 'video', 'url' => 'https://youtube.com/watch?v=example', 'label' => 'Office Tour'],
                ],
                'contacts' => ['reception' => '+994-XX-XXX-XX-XX']
            ],
            'sort_order' => 1
        ]);

        // 2. Создаем серверную внутри офиса
        \App\Models\Api\Location::create([
            'name' => 'Main Server Room',
            'slug' => 'main-server-room',
            'prefix' => 'SRV',
            'parent_id' => $office->id, // Привязываем к офису
            'type' => 'room',
            'metadata' => [
                'media' => [
                    ['type' => 'audio', 'url' => 'https://cdn.link/noise_alert.mp3', 'label' => 'Normal Noise Level'],
                    ['type' => 'image', 'url' => 'https://cdn.link/rack_layout.png', 'label' => 'Rack Plan'],
                ],
                'access_code' => '4455#',
                'temperature_limit' => '18°C-22°C'
            ],
            'sort_order' => 1
        ]);
    }
}
