<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FollowUp extends Model
{
    use HasUuid;
    use SoftDeletes;

    protected $table = 'follow_ups';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'consultation_id',
        'follow_up_date',
        'notes',
        'status',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
    
}
