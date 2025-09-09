<?php

namespace App\Models;

use App\Models\Traits\GeneratesPatientCode;
use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasUuid;
    use GeneratesPatientCode;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'code',
        'full_name',
        'latin_name',
        'gender',
        'dob',
        'phone',
        'nationality',
        'year_of_diagnosis',
        'occupation',
        'status',
        'address',
        'note',
        'province_id',
        'district_id',
        'commune_id',
        'village_id',
    ];

    public function diseases()
    {
        // pivot table: patient_diseases, FKs: patient_id, disease_id
        return $this->belongsToMany(Disease::class, 'patient_diseases', 'patient_id', 'disease_id')
            ->withPivot('diagnosed_date')
            ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lifeStyle()
    {
        return $this->hasOne(LifeStyle::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
    public function district()
    {
        return $this->belongsTo(District::class);
    }
    public function commune()
    {
        return $this->belongsTo(Commune::class);
    }
    public function village()
    {
        return $this->belongsTo(Village::class);
    }
}
