<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class PatientReference extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'patient_id',
        'department_id',
        'doctor_id',
        'doctor_fee',
        'notes',
        'created_by',
        'branch',
        'token',
        'status',
    ];
}
