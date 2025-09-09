<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PrescriptionItem;
use Illuminate\Http\Request;

class PrescriptionItemController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'prescription_id' => 'required|exists:prescriptions,id',
            'variant_id'      => 'required|exists:medicine_variants,id',
            'quantity'        => 'required|integer|min:1',
            'instructions'    => 'nullable|string',
        ]);

        $item = PrescriptionItem::create($validated);

        return response()->json($item->load(['prescription', 'variant']), 201);
    }

    public function update(Request $request, $id)
    {
        $item = PrescriptionItem::findOrFail($id);

        $validated = $request->validate([
            'quantity'     => 'sometimes|integer|min:1',
            'instructions' => 'nullable|string',
        ]);

        $item->update($validated);

        return response()->json($item->load(['prescription', 'variant']));
    }

    public function destroy($id)
    {
        $item = PrescriptionItem::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Prescription item deleted successfully']);
    }
}
