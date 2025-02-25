<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientProcedure extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'medical_record_id',
        'patient_id',
        'branch',
        'procedure',
        'type',
        'fee',
        'discount',
        'discount_category',
        'discount_category_id',
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

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
