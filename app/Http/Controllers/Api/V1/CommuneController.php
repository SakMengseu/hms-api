<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Commune;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class CommuneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        // $communes = Commune::all();
        $communes = QueryBuilder::for(Commune::class)
            ->allowedFilters(['province_id', 'district_id', 'name', 'code', 'number'])
            ->allowedSorts(['id', 'name', 'code', 'number', 'created_at'])
            ->paginate($request->get('per_page', 15))
            ->appends($request->query());

        return response()->json($communes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'province_id' => 'required|exists:provinces,id',
            'district_id' => 'required|exists:districts,id',
            'code' => 'sometimes|string|max:10|unique:districts,code',
            'number' => 'sometimes|string|max:10|unique:districts,number',
            'name' => 'required|string|max:255',
            'latin_name' => 'nullable|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        $commune = Commune::create($validator);

        return response()->json([
            'message' => 'Commune created successfully',
            'commune' => $commune
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $commune = Commune::find($id);

        if (!$commune) {
            return response()->json(['message' => 'Commune not not found'], 404);
        }

        return response()->json($commune);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = $request->validate([
            'province_id' => 'required|exists:provinces,id',
            'district_id' => 'required|exists:districts,id',
            'code' => 'sometimes|string|max:10|unique:districts,code',
            'number' => 'sometimes|string|max:10|unique:districts,number',
            'name' => 'required|string|max:255',
            'latin_name' => 'nullable|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        $commune = Commune::find($id);

        if (!$commune) {
            return response()->json(['message' => 'Commune not found'], 404);
        }

        $commune->update($validator);

        return response()->json([
            'message' => 'Commune updated successfully',
            'commune' => $commune
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $commune = Commune::find($id);

        if (!$commune) {
            return response()->json(['message' => 'Commune not found'], 404);
        }

        $commune->delete();

        return response()->json(['message' => 'Commune deleted successfully'], 200);
    }
}
