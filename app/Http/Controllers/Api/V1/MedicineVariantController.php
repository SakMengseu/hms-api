<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\MedicineVariant;
use GuzzleHttp\Psr7\Query;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class MedicineVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medicineVariants = QueryBuilder::for(MedicineVariant::class)
            ->allowedFilters(
                'name',
                'medicine_id',
                'form_id',
                AllowedFilter::exact('category_id', 'medicine.category_id')
            )
            ->allowedSorts('name', 'medicine_id', 'form_id')
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'form_id' => 'required|exists:medicine_forms,id',
            'dosage' => 'nullable|string',
            'unit' => 'nullable|string',
            'price' => 'required|string',
            'sell_price' => 'required|string',
        ]);

        $medicineVariant = MedicineVariant::create($validated);

        return response()->json([
            'message' => 'Medicine Variant created successfully',
            'medicineVariant' => $medicineVariant
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $medicineVariant = MedicineVariant::find($id);

        if (!$medicineVariant) {
            return response()->json(['message' => 'Medicine Variant not found'], 404);
        }

        return response()->json($medicineVariant);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'form_id' => 'required|exists:medicine_forms,id',
            'dosage' => 'nullable|string',
            'unit' => 'nullable|string',
            'price' => 'required|string',
            'sell_price' => 'required|string',
        ]);

        $medicineVariant = MedicineVariant::find($id);

        if (!$medicineVariant) {
            return response()->json(['message' => 'Medicine Variant not found'], 404);
        }

        $medicineVariant->update($validated);

        return response()->json([
            'message' => 'Medicine Variant updated successfully',
            'medicineVariant' => $medicineVariant
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $medicineVariant = MedicineVariant::find($id);

        if (!$medicineVariant) {
            return response()->json(['message' => 'Medicine Variant not found'], 404);
        }

        return response()->json(['message' => 'Medicine Variant deleted successfully'], 200);
    }
}
