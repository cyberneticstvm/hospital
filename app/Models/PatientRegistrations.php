<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class PatientRegistrations extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'patient_name',
        'patient_id',
        'gender',
        'dob',
        'age',
        'new_born_baby',
        'contact_person_name',
        'mobile_number',
        'email',
        'address',
        'city',
        'state',
        'country',
        'appointment_id',
        'created_by',
        'updated_by',
        'branch',
        'registration_fee',
        'otp',
    ];

    public function testsadvised(){
        return $this->hasMany(TestsAdvised::class);
    }

    public function branches(){
        return $this->belongsTo(Branch::class, 'branch', 'id');
    }

    protected $casts = ['created_at' => 'date'];
}
