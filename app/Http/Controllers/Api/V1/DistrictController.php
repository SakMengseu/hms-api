<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $districts = District::all();
        $districts = QueryBuilder::for(District::class)
            ->allowedFilters(['province_id', 'name', 'code', 'number'])
            ->allowedSorts(['id', 'name', 'code', 'number', 'created_at'])
            ->paginate($request->get('per_page', 15))
            ->appends($request->query());

        return response()->json($districts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'province_id' => 'required|exists:provinces,id',
            'code' => 'sometimes|string|max:10|unique:districts,code',
            'number' => 'sometimes|string|max:10|unique:districts,number',
            'name' => 'required|string|max:255',
            'latin_name' => 'nullable|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        $district = District::create($validator);

        return response()->json([
            'message' => 'District created successfully',
            'district' => $district
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $district = District::find($id);

        if (!$district) {
            return response()->json(['message' => 'District not not found'], 404);
        }

        return response()->json($district);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = $request->validate([
            'province_id' => 'required|exists:provinces,id',
            'code' => 'sometimes|string|max:10|unique:districts,code',
            'number' => 'sometimes|string|max:10|unique:districts,number',
            'name' => 'required|string|max:255',
            'latin_name' => 'nullable|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        $district = District::find($id);

        if (!$district) {
            return response()->json(['message' => 'District not found'], 404);
        }

        $district->update($validator);

        return response()->json([
            'message' => 'District updated successfully',
            'district' => $district
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $district = District::find($id);

        if (!$district) {
            return response()->json(['message' => 'District not found'], 404);
        }

        $district->delete();

        return response()->json(['message' => 'District deleted successfully'], 200);
    }
}
