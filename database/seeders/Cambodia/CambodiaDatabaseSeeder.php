<?php

namespace Database\Seeders\Cambodia;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CambodiaDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Model::unguard();

        $this->call([
            ProvinceTableSeeder::class,
            DistrictTableSeeder::class,
            CommuneTableSeeder::class,
            VillageTableSeeder::class,
        ]);
    }
}
