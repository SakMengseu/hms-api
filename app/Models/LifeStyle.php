<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LifeStyle extends Model
{
    use HasUuid;
    use SoftDeletes;
    protected $table = 'lifestyles';
    protected $fillable = [
        'patient_id',
        'smoking',
        'alcohol',
        'exercise',
        'use_traditional_medicine',
        'other',
    ];

    protected $casts = [
        'smoking' => 'boolean',
        'alcohol' => 'boolean',
        'exercise' => 'boolean',
        'use_traditional_medicine' => 'boolean',
    ];

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }
}
