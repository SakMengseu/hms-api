<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $appoointments = QueryBuilder::for(Appointment::class)
            ->allowedFilters(['status', 'appointment_date', 'patient_id', 'doctor_id'])
            ->allowedSorts(['appointment_date', 'status', 'created_at'])
            ->with(['patient', 'doctor'])
            ->paginate()
            ->appends(request()->query());


        return response()->json($appoointments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
            'status' => 'required|string',
        ]);

        $appointment = Appointment::create($validator);
        return response()->json(
            [
                'message' => 'Appointment created successfully',
                'data' => $appointment
            ],
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }
        return response()->json($appointment, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = $request->validate([
            'patient_id' => 'exists:patients,id',
            'doctor_id' => 'exists:doctors,id',
            'appointment_date' => 'date',
            'status' => 'string',
        ]);
        $appointment = Appointment::findOrFail($id);
        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }
        $appointment->update($validator);
        return response()->json(
            [
                'message' => 'Appointment updated successfully',
                'data' => $appointment
            ],
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $appointment = Appointment::findOrFail($id);
        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }
        $appointment->delete();
        return response()->json(['message' => 'Appointment deleted successfully'], 200);
    }
}
// mock data 
// {
//     "patient_id": 1,
//     "doctor_id": 1,
//     "appointment_date": "2023-06-01",
//     "status": "pending"
// }