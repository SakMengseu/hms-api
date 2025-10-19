<?php

namespace Database\Seeders\Cambodia;

use App\Models\Commune;
use App\Models\District;
use App\Models\Province;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CommuneTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $json       = file_get_contents(database_path('seeders/Cambodia/Data/communes.json'));
        $data       = json_decode($json, false);
        $communes  = $data->communes;

        foreach ($communes as $key => $commune) :
            $provinceCode   = substr($key, 0, 2);
            $districtCode   = substr($key, 0, 4);
            $number         = substr($key, 4, 2);

            $province       = Province::where('code', ltrim($provinceCode, '0'))->first();
            $district       = District::where('code', ltrim($districtCode, '0'))->first();

            Commune::updateOrCreate([
                'code'          => ltrim($key, '0'),
            ], [
                'number'        => ltrim($number, '0'),
                'province_id'   => $province->id,
                'district_id'   => $district->id,
                'name'          => $commune->name->km,
                'latin_name'    => $commune->name->latin,
                'full_name'     => $commune->administrative_unit->km . $commune->name->km,
                'address'       => $commune->administrative_unit->km . $commune->name->km . ', ' . $district->address,
            ]);
        endforeach;
    }
}
