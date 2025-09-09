<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Province extends Model
{
    use HasUuid;
    use SoftDeletes;

    // protected $table = 'provinces';

    protected $fillable = [
        'code',
        'number',
        'name',
        'latin_name',
        'full_name',
        'address'
    ];

    public function districts()
    {
        return $this->hasMany(District::class);
    }

    public function communes()
    {
        return $this->hasManyThrough(Commune::class, District::class);
    }

    public function villages()
    {
        return $this->hasManyThrough(Village::class, Commune::class);
    }
}
