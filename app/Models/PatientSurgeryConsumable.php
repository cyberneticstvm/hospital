<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientSurgeryConsumable extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_number',
        'patient_id',
        'medical_record_id',
        'branch',
        'surgery_id',
        'notes',
        'total',
        'discount',
        'total_after_discount',
        'created_by',
        'updated_by',
    ];

    public function patient(){
        return $this->belongsTo(PatientRegistrations::class, 'patient_id', 'id');
    }

    public function medicalrecord(){
        return $this->belongsTo(PatientMedicalRecord::class, 'medical_record_id', 'id');
    }

    public function surgery(){
        return $this->belongsTo(SurgeryType::class, 'surgery_id', 'id');
    }

    public function psclist(){
        return $this->hasMany(PatientSurgeryConsumableList::class, 'psc_id', 'id');
    }

    public function branches(){
        return $this->belongsTo(Branch::class, 'branch', 'id');
    }
}
