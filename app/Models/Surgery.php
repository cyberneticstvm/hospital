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

    public function patient()
    {
        return $this->belongsTo(PatientRegistrations::class, 'patient_id', 'id');
    }

    public function doctor()
    {
        return $this->belongsTo(doctor::class, 'doctor_id', 'id');
    }

    public function branchdetails()
    {
        return $this->belongsTo(Branch::class, 'branch', 'id');
    }

    public function stype()
    {
        return $this->belongsTo(SurgeryType::class, 'surgery_type', 'id');
    }

    public function surgeondetails()
    {
        return $this->belongsTo(doctor::class, 'surgeon', 'id');
    }
}
