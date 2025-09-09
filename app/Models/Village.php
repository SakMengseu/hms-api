<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Village extends Model
{
    use HasUuid;
    use SoftDeletes;

    protected $fillable = [
        'province_id',
        'district_id',
        'commune_id',
        'code',
        'number',
        'name',
        'latin_name',
        'full_name',
        'address'
    ];

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
}
