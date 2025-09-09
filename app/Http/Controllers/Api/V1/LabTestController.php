<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\LabTest;
use Illuminate\Http\Request;

class LabTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $labTests = LabTest::all();
        return response()->json($labTests);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'test_name' => 'required|string',
            'test_date' => 'nullable|date',
            'results' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $labTest = LabTest::create($validated);

        return response()->json([
            'message' => 'Lab test created successfully',
            'lab_test' => $labTest
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $labTest = LabTest::find($id);

        if (!$labTest) {
            return response()->json(['message' => 'Lab test not found'], 404);
        }

        return response()->json($labTest);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'test_name' => 'required|string',
            'test_date' => 'nullable|date',
            'results' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);


        $labTest = LabTest::find($id);

        if (!$labTest) {
            return response()->json(['message' => 'Lab test not found'], 404);
        }

        $labTest->update($validated);

        return response()->json([
            'message' => 'Lab test updated successfully',
            'lab_test' => $labTest
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $labTest = LabTest::find($id);

        if (!$labTest) {
            return response()->json(['message' => 'Lab test not found'], 404);
        }

        $labTest->delete();

        return response()->json(['message' => 'Lab test deleted successfully'], 200);
    }
}

// mock data but result is json object

// {
//     "name": "Lab Test 1"
// "test_date": "2023-06-01",
// "result": "Positive",
// "notes": "Sample notes"
//  "result": {
//     "test_name": "Lab Test 1",
//     "test_date": "2023-06-01",
//     "result": "Positive",
//     "notes": "Sample notes"
//   }
// }
