<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicineForm extends Model
{
    use HasUuid, SoftDeletes;

    protected $table = 'medicine_categories';

    protected $fillable = ['name', 'description'];

    public function medicineVariants()
    {
        return $this->hasMany(MedicineVariant::class);
    }
}
