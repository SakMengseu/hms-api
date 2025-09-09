<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consultation extends Model
{
    use HasUuid, SoftDeletes;

    protected $table = 'consultations';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'height',
        'weight',
        'bmi',
        'bp',
        'bsl_fasting',
        'bsl_random',
        'pr',
        'waist_circumference',
        'symptoms',
        'diagnosis',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
