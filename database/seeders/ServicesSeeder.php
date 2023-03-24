<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Service::firstOrCreate(
            ['code' => "001"],
            ['description' => "Desarrollo Web"]
        );

        Service::firstOrCreate(
            ['code' => "002"],
            ['description' => "Diseño Web"]
        );

        Service::firstOrCreate(
            ['code' => "003"],
            ['description' => "Marketing Digital"]
        );

        Service::firstOrCreate(
            ['code' => "004"],
            ['description' => "Servicio de Testing"]
        );

        Service::firstOrCreate(
            ['code' => "005"],
            ['description' => "Seguridad Informática"]
        );
    }
}
