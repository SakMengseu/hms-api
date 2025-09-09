<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabTest extends Model
{
    use HasUuid;
    use SoftDeletes;

    protected $fillable = [
        'patient_id',
        'test_name',
        'test_date',
        'results',
        'notes',
    ];

    protected $casts = [
        'results' => 'array',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
