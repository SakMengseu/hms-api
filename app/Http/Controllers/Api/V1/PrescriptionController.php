<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\StockEntry;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prescriptions = QueryBuilder::for(Prescription::class)
            ->allowedFilters(['patient_id', 'doctor_id', 'created_at'])
            ->allowedSorts('patient_id', 'doctor_id', 'created_at')
            ->get();
        return response()->json($prescriptions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id'  => 'required|exists:doctors,id',
            'note'       => 'nullable|string',

            'items'                => 'sometimes|array',
            'items.*.variant_id'   => 'required|exists:medicine_variants,id',
            'items.*.quantity'     => 'required|integer|min:1',
            'items.*.instructions' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Create prescription
            $prescription = Prescription::create([
                'patient_id' => $validated['patient_id'],
                'doctor_id'  => $validated['doctor_id'],
                'note'       => $validated['note'] ?? null,
            ]);

            // Loop items
            foreach ($validated['items'] as $item) {
                // Create prescription item
                PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'variant_id'      => $item['variant_id'],
                    'quantity'        => $item['quantity'],
                    'instructions'    => $item['instructions'] ?? null,
                ]);

                // Deduct stock
                StockEntry::create([
                    'variant_id'      => $item['variant_id'],
                    'prescription_id' => $prescription->id, // ✅ link to prescription
                    'quantity'        => $item['quantity'],
                    'type'            => 'OUT',
                    'source'          => 'Prescription',
                ]);
            }

            DB::commit();

            return response()->json(
                [
                    'message'      => 'Prescription created successfully',
                    'prescription' => $prescription->load(['patient', 'doctor', 'items', 'items.variant']),
                ],
                201
            );
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error creating prescription',
                'error'   => $th->getMessage(),
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $prescription = Prescription::with(['patient', 'doctor', 'items', 'items.variant'])->find($id);

        if (!$prescription) {
            return response()->json(['message' => 'Prescription not found'], 404);
        }
        return response()->json($prescription, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'note'      => 'nullable|string',

            'items'           => 'sometimes|array|min:1',
            'items.*.variant_id'   => 'required|exists:medicine_variants,id',
            'items.*.quantity'     => 'required|integer|min:1',
            'items.*.instructions' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $prescription = Prescription::find($id);

            if (!$prescription) {
                return response()->json(['message' => 'Prescription not found'], 404);
            }

            // Update only prescription fields (exclude items)
            $prescription->update(collect($validated)->except('items')->toArray());

            if (isset($validated['items'])) {
                foreach ($validated['items'] as $item) {
                    // Update or create prescription items
                    PrescriptionItem::updateOrCreate(
                        [
                            'prescription_id' => $prescription->id,
                            'variant_id'      => $item['variant_id'],
                        ],
                        [
                            'quantity'     => $item['quantity'],
                            'instructions' => $item['instructions'] ?? null,
                        ]
                    );

                    // Update stock entry (OUT means reducing stock)
                    StockEntry::updateOrCreate(
                        [
                            'variant_id'      => $item['variant_id'],
                            'prescription_id' => $prescription->id, // ✅ cleaner link
                        ],
                        [
                            'quantity' => $item['quantity'],
                            'type'     => 'OUT',
                            'source'   => 'Prescription',
                        ]
                    );
                }
            }

            DB::commit();

            return response()->json(
                [
                    'message' => 'Prescription updated successfully',
                    'prescription' => $prescription->load([
                        'patient',
                        'doctor',
                        'items',
                        'items.variant'
                    ])
                ],
                200
            );
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error updating prescription',
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $prescription = Prescription::find($id);
        if (!$prescription) {
            return response()->json(['message' => 'Prescription not found'], 404);
        }
        $prescription->delete();
        return response()->json(['message' => 'Prescription deleted successfully'], 200);
    }
}
