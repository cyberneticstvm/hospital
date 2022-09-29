<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'mrn',
        'medical_record_id',
        'patient_id',
        'doctor_id',
        'branch_id',
        'created_by',
        'updated_by',
    ];
}
