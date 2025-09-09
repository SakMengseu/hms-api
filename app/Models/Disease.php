<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Disease extends Model
{
    use HasUuid;
    use SoftDeletes;

    protected $table = 'diseases';

    protected $fillable = [
        'id',
        'name',
        'description',
    ];
    protected $hidden = [

        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function patients()
    {
        return $this->belongsToMany(Patient::class, 'patient_diseases', 'disease_id', 'patient_id')
            ->withPivot('diagnosed_date')
            ->withTimestamps();
    }
}
