<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\MedicineForm;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class MedicineFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $medicineForms = QueryBuilder::for(MedicineForm::class)
            ->allowedFilters('name')
            ->get();

        return response()->json($medicineForms);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        $medicineForm = MedicineForm::create($validate);

        return response()->json([
            'message' => 'Medicine form created successfully',
            'medicineForm' => $medicineForm
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $medicineForm = MedicineForm::find($id);

        if (!$medicineForm) {
            return response()->json(['message' => 'Medicine form not found'], 404);
        }

        return response()->json($medicineForm);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        $medicineForm = MedicineForm::find($id);

        if (!$medicineForm) {
            return response()->json(['message' => 'Medicine form not found'], 404);
        }

        $medicineForm->update($validate);

        return response()->json([
            'message' => 'Medicine form updated successfully',
            'medicineForm' => $medicineForm
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $medicineForm = MedicineForm::find($id);

        if (!$medicineForm) {
            return response()->json(['message' => 'Medicine form not found'], 404);
        }

        $medicineForm->delete();

        return response()->json(['message' => 'Medicine form deleted successfully'], 200);
    }
}
