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
        'discount',
        'discounted_by',
        'discounted_at',
        'discount_notes',
        'notes',
        'created_by',
        'branch',
        'consultation_type',
        'review',
        'rc_type',
        'rc_number',
        'token',
        'status',
        'appointment_id',
        'camp_id',
        'sms',
    ];

    public function patient()
    {
        return $this->belongsTo(PatientRegistrations::class, 'patient_id', 'id');
    }

    public function mrecord()
    {
        return $this->hasOne(PatientMedicalRecord::class, 'mrn', 'id');
    }
}
