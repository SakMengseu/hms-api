<?php

namespace App\Models\Traits;

use App\Models\Prescription;

trait GeneratesPrescriptionNumber
{
    /**
     * Boot the trait and automatically generate prescription number on creating
     */
    public static function bootGeneratesPrescriptionNumber()
    {
        static::creating(function ($model) {
            if (empty($model->prescription_number)) {
                $model->prescription_number = self::generatePrescriptionNumber();
            }
        });
    }

    /**
     * Generate the next prescription number
     *
     * @return string
     */
    public static function generatePrescriptionNumber()
    {
        // Get the last prescription number
        $lastPrescription = Prescription::orderBy('id', 'desc')->first();

        if (!$lastPrescription || !$lastPrescription->prescription_number) {
            $number = 1;
        } else {
            // Extract numeric part
            $lastNumber = (int) str_replace('PN-', '', $lastPrescription->prescription_number);
            $number = $lastNumber + 1;
        }

        // Format with leading zeros
        return 'PN-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
