<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientProcedure extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_record_id',
        'patient_id',
        'branch',
        'procedure',
        'type',
        'fee',
        'created_by',
    ];

    public function procedures()
    {
        return $this->belongsTo(Procedure::class, 'procedure', 'id');
    }

    public function branches()
    {
        return $this->belongsTo(Branch::class, 'branch', 'id');
    }

    public function patient()
    {
        return $this->belongsTo(PatientRegistrations::class, 'patient_id', 'id');
    }
}
