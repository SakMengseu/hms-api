<?php

use App\Http\Controllers\Api\V1\CommuneController;
use App\Http\Controllers\Api\V1\DiseaseController;
use App\Http\Controllers\Api\V1\DistrictController;
use App\Http\Controllers\Api\V1\ProvinceController;
use App\Http\Controllers\Api\V1\VillageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {
    // Cambodia Administrative Divisions
    Route::apiResource('provinces', ProvinceController::class);
    Route::apiResource('districts', DistrictController::class);
    Route::apiResource('communes', CommuneController::class);
    Route::apiResource('villages', VillageController::class);

    //Doctor Management
    Route::apiResource('doctors', \App\Http\Controllers\Api\V1\DoctorController::class);

    // Patient Management
    Route::apiResource('diseases', DiseaseController::class);
    Route::apiResource('patients', \App\Http\Controllers\Api\V1\PatientController::class);

    // Life Style Management
    Route::apiResource('life-styles', \App\Http\Controllers\Api\V1\LifeStyleController::class);

    // Appointment Management
    Route::apiResource('appointments', \App\Http\Controllers\Api\V1\AppointmentController::class);

    // Consultation Management
    Route::apiResource('consultations', \App\Http\Controllers\Api\V1\ConsultationController::class);

    // Follow Up Management
    Route::apiResource('follow-ups', \App\Http\Controllers\Api\V1\FollowUpController::class);

    // Lab Test Management
    Route::apiResource('lab-tests', \App\Http\Controllers\Api\V1\LabTestController::class);

    Route::apiResource('prescriptions', \App\Http\Controllers\Api\V1\PrescriptionController::class);
    Route::apiResource('prescription-items', \App\Http\Controllers\Api\V1\PrescriptionItemController::class);

    // Stock Management
    Route::apiResource('medicine_categories', \App\Http\Controllers\Api\V1\MedicineCategoryController::class);
    Route::apiResource('medicines', \App\Http\Controllers\Api\V1\MedicineController::class);
    Route::apiResource('medicine_form', \App\Http\Controllers\Api\V1\MedicineFormController::class);
    Route::apiResource('medicine_variants', \App\Http\Controllers\Api\V1\MedicineVariantController::class);
    Route::apiResource('stock-entries', \App\Http\Controllers\Api\V1\StockEntryController::class);

    // Payment Management
    Route::apiResource('payments', \App\Http\Controllers\Api\V1\PaymentController::class);
});
