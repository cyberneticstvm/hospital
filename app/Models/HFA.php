<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HFA extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_record_id',
        'patient_id',
        'branch',
        'document',
        'notes',
        'status',
        'created_by',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function mrecord()
    {
        return $this->belongsTo(PatientMedicalRecord::class, 'medical_record_id', 'id');
    }

    public function patient()
    {
        return $this->belongsTo(PatientRegistrations::class, 'patient_id', 'id');
    }

    public function branchdetails()
    {
        return $this->belongsTo(Branch::class, 'branch', 'id');
    }
}
