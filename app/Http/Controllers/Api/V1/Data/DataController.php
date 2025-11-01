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

    // ✅ Get all districts (optionally filtered by province)
    public function districts($provinceId = null)
    {
        $query = QueryBuilder::for(District::class)
            ->allowedFilters(['name', 'province_id']);

        if ($provinceId) {
            $query->where('province_id', $provinceId);
        }

        $districts = $query->get(['id', 'name', 'province_id']);
        return response()->json($districts);
    }

    // ✅ Get all communes (optionally filtered by district)
    public function communes($districtId = null)
    {
        $query = QueryBuilder::for(Commune::class)
            ->allowedFilters(['name', 'district_id']);

        if ($districtId) {
            $query->where('district_id', $districtId);
        }

        $communes = $query->get(['id', 'name', 'district_id']);
        return response()->json($communes);
    }

    // ✅ Get all villages (optionally filtered by commune)
    public function villages($communeId = null)
    {
        $query = QueryBuilder::for(Village::class)
            ->allowedFilters(['name', 'commune_id']);

        if ($communeId) {
            $query->where('commune_id', $communeId);
        }

        $villages = $query->get(['id', 'name', 'commune_id']);
        return response()->json($villages);
    }
}
