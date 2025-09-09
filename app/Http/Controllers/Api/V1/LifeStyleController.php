<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\LifeStyle;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class LifeStyleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $lifeStyles = LifeStyle::all();
        $lifeStyles = QueryBuilder::for(LifeStyle::class)
            ->allowedFilters(['patient_id', 'smoking', 'alcohol', 'exercise', 'use_traditional_medicine'])
            ->allowedSorts(['id', 'patient_id', 'smoking', 'alcohol', 'exercise', 'use_traditional_medicine', 'created_at'])
            ->paginate(request()->get('per_page', 15))
            ->appends(request()->query());

        return response()->json($lifeStyles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'smoking' => 'sometimes|boolean',
            'alcohol' => 'sometimes|boolean',
            'exercise' => 'sometimes|boolean',
            'use_traditional_medicine' => 'sometimes|boolean',
            'other' => 'sometimes|nullable|string|max:255',
        ]);

        $lifeStyle = LifeStyle::create($validator);

        return response()->json([
            'message' => 'LifeStyle created successfully',
            'lifeStyle' => $lifeStyle
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lifeStyle = LifeStyle::find($id);
        if (!$lifeStyle) {
            return response()->json(['message' => 'LifeStyle not found'], 404);
        }

        return response()->json($lifeStyle);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validator = $request->validate([
            'patient_id' => 'sometimes|exists:patients,id',
            'smoking' => 'sometimes|boolean',
            'alcohol' => 'sometimes|boolean',
            'exercise' => 'sometimes|boolean',
            'use_traditional_medicine' => 'sometimes|boolean',
            'other' => 'sometimes|nullable|string|max:255',
        ]);

        $lifeStyle = LifeStyle::findOrFail($id);

        if (!$lifeStyle) {
            return response()->json(['message' => 'LifeStyle not found'], 404);
        }

        $lifeStyle->update($validator);

        return response()->json([
            'message' => 'LifeStyle updated successfully',
            'lifeStyle' => $lifeStyle
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lifeStyle = LifeStyle::find($id);

        if (!$lifeStyle) {
            return response()->json(['message' => 'LifeStyle not found'], 404);
        }

        $lifeStyle->delete();

        return response()->json(['message' => 'LifeStyle deleted successfully'], 200);
    }
}
// mock data
// {
//     "patient_id": 1,
//     "smoking": true,
//     "alcohol": true,
//     "exercise": true,
//     "use_traditional_medicine": true,
//     "other": "None"
// }