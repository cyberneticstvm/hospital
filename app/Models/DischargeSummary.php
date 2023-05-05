<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DischargeSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'medical_record_id',
        'doa',
        'dos',
        'dod',
        'branch',
        'reason_for_admission',
        'findings',
        'medication',
        'investigation_result',
        'general_examination',
        'diagnosis',
        'procedure',
        'discharge_condition',
        'special_instruction',
        'doctor',
        'created_by',
        'updated_by',
    ];

    public function patient(){
        return $this->belongsTo(PatientRegistrations::class, 'patient_id', 'id');
    }

    public function branches(){
        return $this->belongsTo(Branch::class, 'branch', 'id');
    }

    public function diagnosis(){
        return $this->hasMany(DischargeSummaryDiagnosis::class, 'summary_id', 'id');
    }

    public function procedures(){
        return $this->hasMany(DischargeSummaryProcedure::class, 'summary_id', 'id');
    }

    public function medicines(){
        return $this->hasMany(DischargeSummaryMedication::class, 'summary_id', 'id');
    }

    public function instructions(){
        return $this->hasMany(DischargeSummaryInstruction::class, 'summary_id', 'id');
    }

    public function reviews(){
        return $this->hasMany(DischargeSummaryReview::class, 'summary_id', 'id');
    }

    public function doctors(){
        return $this->hasOne(doctor::class, 'id', 'doctor');
    }

    protected $casts = ['doa' => 'date', 'dos' => 'date', 'dod' => 'date'];
}
