<?php

namespace Database\Seeders\Cambodia;

use App\Models\Province;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ProvinceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $json       = file_get_contents(database_path('seeders/Cambodia/Data/provinces.json'));
        $data       = json_decode($json, false);
        $provinces  = $data->provinces;

        foreach ($provinces as $key => $province) :
            $number             = $key;

            Province::updateOrCreate([
                'code'          => ltrim($key, '0'),
            ], [
                'number'        => ltrim($number, '0'),
                'name'          => $province->name->km,
                'latin_name'    => $province->name->latin,
                'full_name'     => $province->administrative_unit->km . $province->name->km,
                'address'       => $province->administrative_unit->km . $province->name->km,
            ]);
        endforeach;
    }
}
