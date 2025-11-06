<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientDiseaseController extends Controller
{

    /**
     * Assign diseases to patient
     */
    public function assignDiseases(Request $request, string $id)
    {
        $validated = $request->validate([
            'diseases' => 'required|array',
            'diseases.*.disease_id' => 'required|exists:diseases,id',
            'diseases.*.diagnosed_date' => 'nullable|date',
        ]);

        $patient = Patient::findOrFail($id);

        $existing = $patient->diseases()->pluck('diagnosed_date', 'disease_id')->toArray();

        $pivotData = [];
        foreach ($validated['diseases'] as $d) {
            $date = $d['diagnosed_date'] ?? ($existing[$d['disease_id']] ?? now()->toDateString());
            $pivotData[$d['disease_id']] = ['diagnosed_date' => $date];
        }

        $patient->diseases()->sync($pivotData);

        return response()->json([
            'message' => 'Patient diseases updated successfully!',
            'patient' => $patient->load('diseases'),
        ]);
    }

    /**
     * update assigned diseases for patient
     */
    public function updateDiseases(Request $request, string $id)
    {
        $validator = $request->validate([
            'diseases' => 'required|array',
            'diseases.*.disease_id' => 'required|exists:diseases,id',
            'diseases.*.diagnosed_date' => 'nullable|date',
        ]);
        $patient = Patient::findOrFail($id);
        $pivotData = [];
        foreach ($validator['diseases'] as $d) {
            $pivotData[$d['disease_id']] = [
                'diagnosed_date' => $d['diagnosed_date'] ?? now()->toDateString(),
            ];
        }

        $patient->diseases()->sync($pivotData);

        return response()->json([
            'message' => 'Diseases updated successfully',
            'patient' => $patient->load('diseases'),
        ], 200);
    }

    /**
     *   Remove assigned diseases from patient
     */
    public function removeDiseases(Request $request, string $id)
    {
        $validator = $request->validate([
            'disease_ids' => 'required|array',
            'disease_ids.*' => 'required|exists:diseases,id',
        ]);
        $patient = Patient::findOrFail($id);
        $patient->diseases()->detach($validator['disease_ids']);
        return response()->json([
            'message' => 'Diseases removed from patient successfully',
            'patient' => $patient->load('diseases'),
        ], 200);
    }
}
