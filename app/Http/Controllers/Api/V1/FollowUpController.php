<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\FollowUp;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class FollowUpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $follow_ups = QueryBuilder::for(FollowUp::class)
            ->allowedFilters(['status', 'patient_id', 'doctor_id'])
            ->allowedSorts(['status', 'created_at'])
            ->with(['patient', 'doctor'])
            ->paginate()
            ->appends(request()->query());

        return response()->json($follow_ups);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'consultation_id' => 'sometimes|nullable|exists:consultations,id',
            'follow_up_date' => 'required|date',
            'status' => 'nullable|string',
        ]);

        $follow_up = FollowUp::create($validated);

        return response()->json([
            'message' => 'Follow up created successfully',
            'follow_up' => $follow_up
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $follow_up = FollowUp::find($id);

        if (!$follow_up) {
            return response()->json(['message' => 'Follow up not found'], 404);
        }

        return response()->json($follow_up);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'consultation_id' => 'sometimes|exists:consultations,id',
            'follow_up_date' => 'required|date',
            'status' => 'nullable|string',
        ]);

        $follow_up = FollowUp::find($id);

        if (!$follow_up) {
            return response()->json(['message' => 'Follow up not found'], 404);
        }

        $follow_up->update($validated);

        return response()->json([
            'message' => 'Follow up updated successfully',
            'follow_up' => $follow_up
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $follow_up = FollowUp::find($id);

        if (!$follow_up) {
            return response()->json(['message' => 'Follow up not found'], 404);
        }

        $follow_up->delete();

        return response()->json(['message' => 'Follow up deleted successfully'], 200);
    }
}

//mock data
// {
//     "patient_id": 1,
//     "doctor_id": 1,
//     "follow_up_date": "2023-06-01",
//     "status": "pending"
// }
