<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        return [
            'first_name'        => $this->first_name,
            'last_name'         => $this->last_name,
            'full_name'         => $this->full_name,
            'gender'            => $this->gender,
            'date_of_birth'     => $this->date_of_birth,
            'phone'             => $this->phone,
            'email'             => $this->email,
            'specialty'         => $this->specialty,
            'license_number'    => $this->license_number,
            'department'        => $this->department,
            'experience_years'  => $this->experience_years,
            'address'           =>  $this->province?->name . ' ' .
                $this->district?->name . ' ' .
                $this->commune?->name . ' ' .
                $this->village?->name,

        ];
    }
}
