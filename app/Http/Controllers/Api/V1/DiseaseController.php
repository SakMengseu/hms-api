<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Disease;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class DiseaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        // $diseases = Disease::all();
        $diseases = QueryBuilder::for(Disease::class)
            ->allowedFilters(['name'])
            ->allowedSorts(['id', 'name', 'created_at'])
            ->paginate($request->get('per_page', 15))
            ->appends($request->query());

        return response()->json($diseases);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|string|max:255|unique:diseases,name',
            'description' => 'nullable|string',
        ]);

        $disease = Disease::create($validator);

        return response()->json([
            'message' => 'Disease created successfully',
            'disease' => $disease
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $disease = Disease::find($id);

        if (!$disease) {
            return response()->json(['message' => 'Disease not found'], 404);
        }

        return response()->json($disease);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = $request->validate([
            'name' => 'required|string|max:255|unique:diseases,name',
            'description' => 'nullable|string',
        ]);

        $disease = Disease::find($id);

        if (!$disease) {
            return response()->json(['message' => 'Disease not found'], 404);
        }

        $disease->update($validator);

        return response()->json([
            'message' => 'Disease updated successfully',
            'disease' => $disease
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $disease = Disease::find($id);

        if (!$disease) {
            return response()->json(['message' => 'Disease not found'], 404);
        }

        $disease->delete();

        return response()->json(['message' => 'Disease deleted successfully'], 200);
    }
}
