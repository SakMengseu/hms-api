<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockEntry extends Model
{
    use HasUuid, SoftDeletes;

    protected $table = 'stock_entries';

    protected $fillable = [
        'variant_id',
        'consultation_id',
        'quantity',
        'type',
        'source',
        'note',
    ];

    protected $casts = [
        'type' => 'enum:IN,OUT',
    ];

    public function variant()
    {
        return $this->belongsTo(MedicineVariant::class);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
