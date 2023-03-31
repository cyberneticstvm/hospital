<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'patient_name',
        'gender',
        'age',
        'mobile_number',
        'address',
        'branch',
        'doctor',
        'appointment_date',
        'appointment_time',
        'status',
        'notes',
        'camp_id',
        'created_by',
        'updated_by',
    ];

    public function branches(){
        return $this->belongsTo(Branch::class, 'branch', 'id');
    }

    public function doctors(){
        return $this->belongsTo(doctor::class, 'doctor', 'id');
    }

    protected $casts = ['appointment_date' => 'date'];
}
