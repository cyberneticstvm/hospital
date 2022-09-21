<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class PatientRegistrations extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'patient_name',
        'patient_id',
        'gender',
        'dob',
        'age',
        'contact_person_name',
        'mobile_number',
        'email',
        'address',
        'city',
        'state',
        'country',
        'created_by',
        'branch',
        'registration_fee',
    ];
}
