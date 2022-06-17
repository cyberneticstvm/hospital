<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Admission extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'medical_record_id',
        'doctor_id',
        'patient_id',
        'room_type',
        'room_number',
        'bystander_name',
        'bystander_contact_number',
        'patient_bystander_relation',
        'remarks',
        'admission_date',
        'updated_by',
    ];
}
