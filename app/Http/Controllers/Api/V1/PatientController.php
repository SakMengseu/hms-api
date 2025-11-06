<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\QueryBuilder;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $patients = Patient::with(['diseases', 'user', 'province', 'district', 'commune', 'village'])
        //     ->paginate($request->input('per_page', 15));

        $patients = QueryBuilder::for(Patient::class)
            ->with(['diseases', 'user', 'province', 'district', 'commune', 'village'])
            ->allowedFilters(['user_id', 'code', 'full_name', 'phone'])
            ->allowedSorts(['id', 'user_id', 'code', 'full_name', 'phone', 'created_at'])
            ->paginate($request->get('per_page', 15))
            ->appends($request->query());

        return response()->json($patients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'user_id' => 'sometimes|nullable|exists:users,id',
            'full_name' => 'required|string|max:255',
            'latin_name' => 'sometimes|nullable|string|max:255',
            'gender' => 'required|string|max:255',
            'dob' => 'required|date',
            'phone' => 'required|string|max:20',
            'nationality' => 'sometimes|nullable|string|max:255',
            'year_of_diagnosis' => 'sometimes|nullable',
            'occupation' => 'sometimes|nullable|string|max:255',
            'status' => 'sometimes|nullable|string|max:255',
            'address' => 'sometimes|nullable|string|max:255',
            'note' => 'sometimes|nullable|string|max:255',
            'province_id' => 'sometimes|exists:provinces,id',
            'district_id' => 'sometimes|exists:districts,id',
            'commune_id' => 'sometimes|exists:communes,id',
            'village_id' => 'sometimes|exists:villages,id',

            'diseases' => 'sometimes|array',
            'diseases.*.disease_id' => 'required|exists:diseases,id',
            'diseases.*.diagnosed_date' => 'nullable|date',
        ]);



        $patient = Patient::create($validator);
        // dd($request->diseases);
        if ($request->filled('diseases')) {
            $pivotData = [];
            foreach ($request->diseases as $d) {
                $pivotData[$d['disease_id']] = [
                    'diagnosed_date' => $d['diagnosed_date'] ?? now()->toDateString(),
                ];
            }
            $patient->diseases()->attach($pivotData);
            Log::info('Patient diseases attached', ['result' => array_keys($pivotData)]);
        }
        return response()->json([
            'message' => 'Patient created successfully',
            'patient' => $patient->load('diseases'),
        ], 201);
    }

    /**
     * Assign diseases to patient
     */
    public function assignDiseases(Request $request, string $id)
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

        $patient->diseases()->attach($pivotData);

        return response()->json([
            'message' => 'Diseases assigned to patient successfully',
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $patient = Patient::with(['diseases', 'user',])->find($id);

        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }

        return response()->json($patient);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = $request->validate([
            'user_id' => 'sometimes|nullable|exists:users,id',
            'full_name' => 'required|string|max:255',
            'latin_name' => 'sometimes|nullable|string|max:255',
            'gender' => 'required|string|max:255',
            'dob' => 'required|date',
            'phone' => 'required|string|max:20',
            'nationality' => 'sometimes|nullable|string|max:255',
            'year_of_diagnosis' => 'sometimes|nullable|numeric',
            'occupation' => 'sometimes|nullable|string|max:255',
            'status' => 'sometimes|nullable|string|max:255',
            'address' => 'sometimes|nullable|string|max:255',
            'note' => 'sometimes|nullable|string|max:255',
            'province_id' => 'sometimes|nullable|integer|exists:provinces,id',
            'district_id' => 'sometimes|nullable|integer|exists:districts,id',
            'commune_id'  => 'sometimes|nullable|integer|exists:communes,id',
            'village_id'  => 'sometimes|nullable|integer|exists:villages,id',

            // 'diseases' => 'sometimes|array',
            // 'diseases.*.disease_id' => 'required|exists:diseases,id',
            // 'diseases.*.diagnosed_date' => 'nullable|date',
        ]);


        $patient = Patient::findOrFail($id);

        // Update patient info
        $patient->update($validator);

        // // Sync diseases if key is provided
        // if ($request->has('disease')) {
        //     $pivotData = [];

        //     foreach ($request->disease as $d) {
        //         $pivotData[$d['disease_id']] = [
        //             'diagnosed_date' => $d['diagnosed_date'] ?? now()->toDateString(),
        //         ];
        //     }

        //     $patient->diseases()->sync($pivotData);
        // }


        return response()->json(
            [
                'message' => 'Patient updated successfully',
                'patient' => $patient->load(['diseases', 'user', 'province', 'district', 'commune', 'village']),
            ],
            200
        );
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $patient = Patient::findOrFail($id);
        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }

        $patient->delete();

        return response()->json(['message' => 'Patient deleted successfully'], 200);
    }
}

// mock data for testing in json
// {
//     "user_id": 1,
//     "full_name": "John Doe",
//     "latin_name": "John Doe",
//     "gender": "male",
//     "dob": "1990-01-01",
//     "phone": "1234567890",
//     "nationality": "American",
//     "year_of_diagnosis": "2020-01-01",
//     "occupation": "Engineer",
//     "status": "active",
//     "address": "123 Main Street",
//     "note": "Patient note",
//     "phone_number": "1234567890",
//     "province_id": 1,
//     "district_id": 1,
//     "commune_id": 1,
//     "village_id": 1
// }