<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AxialLength extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'medical_record_id',
        'patient_id',
        'branch_id',
        'axl',
        'acd',
        'lens',
        'created_by',
        'updated_by',
    ];

    public function patient()
    {
        return $this->belongsTo(PatientRegistrations::class, 'patient_id', 'id');
    }
}
