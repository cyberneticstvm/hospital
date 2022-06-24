<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Surgery extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'medical_record_id',
        'doctor_id',
        'patient_id',
        'surgery_date',
        'surgery_type',
        'surgeon',
        'remarks',
        'updated_by'
    ];
}
