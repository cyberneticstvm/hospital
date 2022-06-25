<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class PatientMedicalRecord extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'mrn',
        'patient_id',
        'doctor_id',
        'symptoms',
        'symptoms_other',
        'history',
        'diagnosis',
        'doctor_recommondations',
        'signs',
        'k1_od_auto',
        'k1_os_auto',
        'k2_od_auto',
        'k2_os_auto',
        'k1_od_manual',
        'k1_os_manual',
        'k2_od_manual',
        'k2_os_manual',
        'axl',
        'vision_od_img1',
        'vision_os_img1',
        'vision_od_img2',
        'vision_os_img2',
        'review_date',
        'created_by',
        'is_admission',
        'is_surgery',
    ];
}
