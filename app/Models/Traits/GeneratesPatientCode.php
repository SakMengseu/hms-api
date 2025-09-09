<?php

namespace App\Models\Traits;

use App\Models\Patient;
use Illuminate\Support\Str;

trait GeneratesPatientCode
{
    /**
     * Generate the next patient code in format P-00001
     */
    public function generatePatientCode(): string
    {
        // Get the latest patient by ID
        $lastPatient = Patient::latest('id')->first();
        $nextId = $lastPatient ? $lastPatient->id + 1 : 1;

        // Format with leading zeros (5 digits)
        return 'P-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }
}
