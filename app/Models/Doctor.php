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
        'specialty',
        'license_number',
        'department',
        'experience_years',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
