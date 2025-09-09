<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrescriptionItem extends Model
{
    use HasUuid, SoftDeletes;
    protected $table = 'prescription_items';

    protected $fillable = [
        'prescription_id',
        'variant_id',
        'quantity',
        'instructions',
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function variant()
    {
        return $this->belongsTo(MedicineVariant::class);
    }
}
