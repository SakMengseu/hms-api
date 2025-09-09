<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class District extends Model
{
    use HasUuid;
    use SoftDeletes;

    protected $fillable = [
        'province_id',
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

    public function communes()
    {
        return $this->hasMany(Commune::class);
    }

    public function villages()
    {
        return $this->hasManyThrough(Village::class, Commune::class);
    }
}
