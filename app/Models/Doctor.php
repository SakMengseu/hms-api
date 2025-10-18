<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use HasUuid;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'full_name',
        'gender',
        'date_of_birth',
        'phone',
        'email',
        'specialty',
        'license_number',
        'department',
        'experience_years',
        'province_id',
        'district_id',
        'commune_id',
        'village_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
