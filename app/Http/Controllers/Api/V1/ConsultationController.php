<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ConsultationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $consultations = QueryBuilder::for(Consultation::class)
            ->allowedFilters(['status', 'patient_id', 'doctor_id'])
            ->allowedSorts(['status', 'created_at'])
            ->with(['patient', 'doctor'])
            ->paginate()
            ->appends(request()->query());

        return response()->json($consultations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'bmi' => 'nullable|numeric',
            'bp' => 'nullable|string',
            'bsl_fasting' => 'nullable|string',
            'bsl_random' => 'nullable|string',
            'pr' => 'nullable|string',
            'waist_circumference' => 'nullable|numeric',
            'symptoms' => 'nullable|string',
            'diagnosis' => 'nullable|string',
        ]);

        $consultation = Consultation::create($validated);

        return response()->json([
            'message' => 'Consultation created successfully',
            'consultation' => $consultation
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $consultation = Consultation::with(['patient', 'doctor'])->find($id);
        if (!$consultation) {
            return response()->json(['message' => 'Consultation not found'], 404);
        }
        return response()->json($consultation);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'bmi' => 'nullable|numeric',
            'bp' => 'nullable|string',
            'bsl_fasting' => 'nullable|string',
            'bsl_random' => 'nullable|string',
            'pr' => 'nullable|string',
            'waist_circumference' => 'nullable|numeric',
            'symptoms' => 'nullable|string',
            'diagnosis' => 'nullable|string',
        ]);

        $consultation = Consultation::find($id);
        if (!$consultation) {
            return response()->json(['message' => 'Consultation not found'], 404);
        }

        $consultation->update($validated);

        return response()->json([
            'message' => 'Consultation updated successfully',
            'consultation' => $consultation
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $consultation = Consultation::find($id);
        if (!$consultation) {
            return response()->json(['message' => 'Consultation not found'], 404);
        }
        $consultation->delete();
        return response()->json(['message' => 'Consultation deleted successfully'], 200);
    }
}
//mock data 
// {
//     "patient_id": 1,
//     "doctor_id": 1,
//     "height": 180,
//     "weight": 70,
//     "bmi": 22.5,
//     "bp": "120/80",
//     "bsl_fasting": "100",
//     "bsl_random": "120",
//     "pr": "80",
//     "waist_circumference": 80,
//     "symptoms": "Fever, headache",
//     "diagnosis": "Malaria"
// }


