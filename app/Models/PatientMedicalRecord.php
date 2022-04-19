<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class PatientMedicalRecord extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'mrn',
        'patient_id',
        'doctor_id',
        'symptoms',
        'diagnosis',
        'doctor_recommondations',
        'review_date',
        'created_by',
        'is_admission',
        'is_surgery',
    ];
}
