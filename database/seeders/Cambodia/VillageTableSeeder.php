<?php

namespace Database\Seeders\Cambodia;

use App\Models\Commune;
use App\Models\District;
use App\Models\Province;
use App\Models\Village;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class VillageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */ 
    public function run()
    {
        Model::unguard();

        $json       = file_get_contents(database_path('seeders/Cambodia/Data/villages.json'));
        $data       = json_decode($json, false);
        $villages   = $data->villages;

        foreach ($villages as $key => $village) :

            $provinceCode   = substr($key, 0, 2);
            $districtCode   = substr($key, 0, 4);
            $communeCode    = substr($key, 0, 6);
            $number         = substr($key, 6, 2);

            $province       = Province::where('code', ltrim($provinceCode, '0'))->first();
            $district       = District::where('code', ltrim($districtCode, '0'))->first();
            $commune        = Commune::where('code', ltrim($communeCode, '0'))->first();

            Village::updateOrCreate([
                'code'          => ltrim($key, '0'),
            ], [
                'number'        => ltrim($number, '0'),
                'province_id'   => $province->id,
                'district_id'   => $district->id,
                'commune_id'    => $commune->id,
                'name'          => $village->name->km,
                'latin_name'    => $village->name->latin,
                'full_name'     => $village->administrative_unit->km . $village->name->km,
                'address'       => $village->administrative_unit->km . $village->name->km . ', ' . $commune->address,
            ]);
        endforeach;
    }
}
