<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function testsadvised()
    {
        return $this->hasMany(TestsAdvised::class);
    }

    public function branches()
    {
        return $this->belongsTo(Branch::class, 'branch', 'id');
    }

    public function patientAge()
    {
        if ($this->dob):
            return Carbon::parse($this->dob)->age;
        else:
            return Carbon::parse($this->created_at)->age + $this->age;
        endif;
    }

    protected $casts = ['created_at' => 'date'];
}
