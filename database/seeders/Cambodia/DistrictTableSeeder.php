<?php

namespace Database\Seeders\Cambodia;

use App\Models\District;
use App\Models\Province;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DistrictTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $json       = file_get_contents(database_path('seeders/Cambodia/Data/districts.json'));
        $data       = json_decode($json, false);
        $districts  = $data->districts;

        foreach ($districts as $key => $district) :
            $provinceCode   = substr($key, 0, 2);
            $number         = substr($key, 2, 2);

            $province       = Province::where('code', ltrim($provinceCode, '0'))->first();

            District::updateOrCreate([
                'number'      => ltrim($number, '0'),
                'province_id' => $province->id,
            ], [
                'code'        => ltrim($key, '0'),
                'name'        => $district->name->km,
                'latin_name'  => $district->name->latin,
                'full_name'   => $district->administrative_unit->km . $district->name->km,
                'address'     => $district->administrative_unit->km . $district->name->km . ', ' . $province->address,
            ]);

        endforeach;
    }
}
