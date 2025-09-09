<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Village;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class VillageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $villages = QueryBuilder::for(Village::class)
            ->allowedFilters(['name', 'code', 'number'])
            ->allowedSorts(['id', 'name', 'code', 'number', 'created_at'])
            ->paginate($request->get('per_page', 15))
            ->appends($request->query());

        return response()->json($villages);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'province_id' => 'required|exists:provinces,id',
            'district_id' => 'required|exists:districts,id',
            'commune_id' => 'required|exists:communes,id',
            'code' => 'sometimes|string|max:10|unique:districts,code',
            'number' => 'sometimes|string|max:10|unique:districts,number',
            'name' => 'required|string|max:255',
            'latin_name' => 'nullable|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        $village = Village::create($validator);

        return response()->json([
            'message' => 'Village created successfully',
            'village' => $village
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $village = Village::find($id);

        if (!$village) {
            return response()->json(['message' => 'Village not found'], 404);
        }

        return response()->json($village);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = $request->validate([
            'province_id' => 'required|exists:provinces,id',
            'district_id' => 'required|exists:districts,id',
            'commune_id' => 'required|exists:communes,id',
            'code' => 'sometimes|string|max:10|unique:districts,code',
            'number' => 'sometimes|string|max:10|unique:districts,number',
            'name' => 'required|string|max:255',
            'latin_name' => 'nullable|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        $village = Village::find($id);

        if (!$village) {
            return response()->json(['message' => 'Village not found'], 404);
        }

        $village->update($validator);

        return response()->json([
            'message' => 'Village updated successfully',
            'village' => $village
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $village = Village::find($id);

        if (!$village) {
            return response()->json(['message' => 'Village not found'], 404);
        }

        $village->delete();

        return response()->json(['message' => 'Village deleted successfully'], 200);
    }
}
