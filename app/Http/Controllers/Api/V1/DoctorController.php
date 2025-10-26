<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Comment\Doc;
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

        return DoctorResource::collection($doctors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ✅ 1. Validate input
        $validated = $request->validate([
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'gender'            => 'required|string|max:10',
            'date_of_birth'     => 'required|date',
            'phone'             => 'required|string|max:20',
            'email'             => 'required|string|email|max:255|unique:users,email',
            'specialty'         => 'nullable|string|max:255',
            'license_number'    => 'nullable|string|max:255|unique:doctors,license_number',
            'department'        => 'nullable|string|max:255',
            'experience_years'  => 'nullable|integer',
            'province_id'       => 'nullable|integer|exists:provinces,id',
            'district_id'       => 'nullable|integer|exists:districts,id',
            'commune_id'        => 'nullable|integer|exists:communes,id',
            'village_id'        => 'nullable|integer|exists:villages,id',
        ]);

        // ✅ 2. Create User record
        $user = User::create([
            'name'     => $validated['first_name'] . ' ' . $validated['last_name'],
            'email'    => $validated['email'],
            'password' => bcrypt('defaultpassword'), // or generate a random password
        ]);

        // ✅ 3. Add extra fields for Doctor
        $validated['full_name'] = $validated['first_name'] . ' ' . $validated['last_name'];
        $validated['user_id'] = $user->id;

        //sync roles
        $user->syncRoles(['doctor']);

        // ✅ 4. Create Doctor record
        $doctor = Doctor::create($validated);

        // ✅ 5. Return JSON response
        return response()->json([
            'message' => 'Doctor created successfully',
            'doctor'  => $doctor
        ], 201);
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
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'gender'            => 'required|string|max:10',
            'date_of_birth'     => 'required|date',
            'phone'             => 'required|string|max:20',
            'email'             => 'required|string|email|max:255',
            'specialty'         => 'nullable|string|max:255',
            'license_number'    => 'nullable|string|max:255',
            'department'        => 'nullable|string|max:255',
            'experience_years'  => 'nullable|integer',
            'province_id'       => 'nullable|integer|exists:provinces,id',
            'district_id'       => 'nullable|integer|exists:districts,id',
            'commune_id'        => 'nullable|integer|exists:communes,id',
            'village_id'        => 'nullable|integer|exists:villages,id',
        ]);

        // create full_name field
        $full_name = $request->input('first_name') . ' ' . $request->input('last_name');
        $validator['full_name'] = $full_name;

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