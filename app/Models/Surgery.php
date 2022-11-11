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
        'branch',
        'surgery_date',
        'surgery_type',
        'surgeon',
        'eye',
        'remarks',
        'status',
        'surgery_fee',
        'updated_by'
    ];
}
