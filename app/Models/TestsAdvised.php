<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestsAdvised extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_record_id',
        'patient_id',
        'doctor_id',
        'branch',
        'test',
        'notes',
        'attachment',
        'status',
        'updated_by',
    ];

    public function patient(){
        return $this->belongsTo(PatientRegistrations::class, 'patient_id');
    }

    public function doctor(){
        return $this->belongsTo(doctor::class, 'doctor_id');
    }

    public function test(){
        return $this->belongsTo(Test::class, 'test');
    }
}
