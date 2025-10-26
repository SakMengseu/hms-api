<?php

namespace App\Http\Controllers\Api\V1\Data;

use App\Http\Controllers\Controller;
use App\Models\Commune;
use App\Models\District;
use App\Models\Province;
use App\Models\User;
use App\Models\Village;
use Spatie\QueryBuilder\QueryBuilder;

class DataController extends Controller
{
    // ✅ Get all patients 
    public function patients()
    {
        // $roles = Role::all(['id', 'name']);
        return response()->json('patients');
    }


    // ✅ Get all provinces
    public function provinces()
    {
        $provinces = QueryBuilder::for(Province::class)
            ->allowedFilters(['name'])
            ->get(['id', 'name']);
        return response()->json($provinces);
    }

    // ✅ Get all districts by province id
    public function districts($provinceId)
    {
        $districts = QueryBuilder::for(District::class)
            ->allowedFilters(['name', 'province_id'])
            ->where('province_id', $provinceId)
            ->get(['id', 'name']);
        return response()->json($districts);
    }

    // ✅ Get all communes by district id
    public function communes($districtId)
    {
        $communes = QueryBuilder::for(Commune::class)
            ->allowedFilters(['name', 'district_id'])
            ->where('district_id', $districtId)
            ->get(['id', 'name']);
        return response()->json($communes);
    }

    // ✅ Get all villages by commune id
    public function villages($communeId)
    {
        $villages = QueryBuilder::for(Village::class)
            ->allowedFilters(['name', 'commune_id'])
            ->where('commune_id', $communeId)
            ->get(['id', 'name']);
        return response()->json($villages);
    }
}
