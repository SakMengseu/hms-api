<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $doctors = QueryBuilder::for(Doctor::class)
            ->allowedFilters(['user_id', 'specialty', 'license_number', 'department', 'experience_years'])
            ->allowedSorts(['id', 'user_id', 'specialty', 'license_number', 'department', 'experience_years', 'created_at'])
            ->paginate($request->get('per_page', 15))
            ->appends($request->query());

        return response()->json($doctors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'specialty' => 'required|string|max:255',
            'license_number' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'experience_years' => 'required|integer',
        ]);

        $doctor = Doctor::create($validator);

        return response()->json(
            [
                'message' => 'Doctor created successfully',
                'doctor' => $doctor
            ],
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $doctor = Doctor::find($id);

        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        return response()->json($doctor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $doctor = Doctor::find($id);

        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        $validator = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'specialty' => 'sometimes|string|max:255',
            'license_number' => 'sometimes|string|max:255',
            'department' => 'sometimes|string|max:255',
            'experience_years' => 'sometimes|integer',
        ]);

        $doctor->update($validator);

        return response()->json(
            [
                'message' => 'Doctor updated successfully',
                'doctor' => $doctor
            ],
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $doctor = Doctor::find($id);

        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        $doctor->delete();

        return response()->json(['message' => 'Doctor deleted successfully'], 200);
    }
}

// mock test data
// {
//     "user_id": 1,
//     "specialty": "Cardiology",
//     "license_number": "LIC123456",
//     "department": "Cardiology",
//     "experience_years": 10
// }