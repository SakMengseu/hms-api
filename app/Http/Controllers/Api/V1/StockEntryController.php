<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\StockEntry;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class StockEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stockEntries = QueryBuilder::for(StockEntry::class)
            ->allowedFilters(['medicine_variant_id', 'quantity', 'created_at'])
            ->allowedSorts('medicine_variant_id', 'quantity', 'created_at')
            ->get();
        return response()->json($stockEntries);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'medicine_variant_id' => 'required|exists:medicine_variants,id',
            'consultation_id' => 'nullable|exists:consultations,id',
            'quantity' => 'required|numeric',
            'type' => 'required|in:IN,OUT',
            'source' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $stockEntry = StockEntry::create($validated);
        return response()->json([
            'message' => 'Stock entry created successfully',
            'stockEntry' => $stockEntry
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $stockEntry = StockEntry::find($id);

        if (!$stockEntry) {
            return response()->json(['message' => 'Stock entry not found'], 404);
        }

        return response()->json($stockEntry);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'medicine_variant_id' => 'required|exists:medicine_variants,id',
            'consultation_id' => 'nullable|exists:consultations,id',
            'quantity' => 'required|numeric',
            'type' => 'required|in:IN,OUT',
            'source' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $stockEntry = StockEntry::find($id);

        if (!$stockEntry) {
            return response()->json(['message' => 'Stock entry not found'], 404);
        }

        $stockEntry->update($validated);

        return response()->json([
            'message' => 'Stock entry updated successfully',
            'stockEntry' => $stockEntry
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $stockEntry = StockEntry::find($id);
        if (!$stockEntry) {
            return response()->json(['message' => 'Stock entry not found'], 404);
        }
        $stockEntry->delete();
        return response()->json(['message' => 'Stock entry deleted successfully'], 200);
    }
}
