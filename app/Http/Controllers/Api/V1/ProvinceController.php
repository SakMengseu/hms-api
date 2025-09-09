<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Province;
use GuzzleHttp\Psr7\Query;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $provinces = Province::all();
        $provinces = QueryBuilder::for(Province::class)
            ->allowedFilters(['name', 'code', 'number'])
            ->allowedSorts(['id', 'name', 'code', 'number', 'created_at'])
            ->paginate(request()->get('per_page', 15))
            ->appends(request()->query());

        return response()->json($provinces);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'code' => 'sometimes|string|max:10|unique:provinces,code',
            'number' => 'sometimes|string|max:10|unique:provinces,number',
            'name' => 'required|string|max:255|unique:provinces,name',
            'latin_name' => 'nullable|string|max:255|unique:provinces,latin_name',
            'full_name' => 'nullable|string|max:255|unique:provinces,full_name',
            'address' => 'nullable|string|max:500',
        ]);

        $province = Province::create($validator);

        return response()->json([
            'message' => 'Province created successfully',
            'province' => $province
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $province = Province::find($id);

        if (!$province) {
            return response()->json(['message' => 'Province not found'], 404);
        }

        return response()->json($province);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = $request->validate([
            'code' => 'sometimes|string|max:10|unique:provinces,code',
            'number' => 'sometimes|string|max:10|unique:provinces,number',
            'name' => 'required|string|max:255',
            'latin_name' => 'nullable|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        $province = Province::find($id);

        if (!$province) {
            return response()->json(['message' => 'Province not found'], 404);
        }

        $province->update($validator);

        return response()->json([
            'message' => 'Province updated successfully',
            'province' => $province
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $province = Province::find($id);

        if (!$province) {
            return response()->json(['message' => 'Province not found'], 404);
        }

        $province->delete();

        return response()->json(['message' => 'Province deleted successfully'], 200);
    }
}
