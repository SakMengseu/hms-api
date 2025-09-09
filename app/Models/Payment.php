<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasUuid, SoftDeletes;

    protected $table = 'payments';

    protected $fillable = [
        'patient_id',
        'prescription_id',
        'amount',
        'currency',
        'method',
        'reference',
        'status',
        'paid_at',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
}
