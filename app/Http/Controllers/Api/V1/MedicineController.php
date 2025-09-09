<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $Madicines = QueryBuilder::for(Medicine::class)
            ->allowedFilters('name', 'category_id')
            ->allowedSorts(['name', 'category_id', 'created_at'])
            ->paginate($request->get('per_page', 15))
            ->appends($request->query())
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'brand_name' => 'nullable',
            'category_id' => 'required',
            'description' => 'nullable',
        ]);

        $medicine = Medicine::create($validate);

        return response()->json([
            'message' => 'Medicine created successfully',
            'medicine' => $medicine
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $medicine = Medicine::find($id);

        if (!$medicine) {
            return response()->json(['message' => 'Medicine not found'], 404);
        }

        return response()->json($medicine, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = $request->validate([
            'name' => 'required',
            'brand_name' => 'nullable',
            'category_id' => 'required',
            'description' => 'nullable',
        ]);

        $medicine = Medicine::find($id);

        if (!$medicine) {
            return response()->json(['message' => 'Medicine not found'], 404);
        }

        $medicine->update($validate);

        return response()->json([
            'message' => 'Medicine updated successfully',
            'medicine' => $medicine
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $medicine = Medicine::find($id);

        if (!$medicine) {
            return response()->json(['message' => 'Medicine not found'], 404);
        }

        $medicine->delete();

        return response()->json(['message' => 'Medicine deleted successfully'], 200);
    }
}
