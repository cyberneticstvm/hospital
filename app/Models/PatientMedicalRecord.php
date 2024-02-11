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
        'branch',
        'patient_id',
        'doctor_id',
        'symptoms',
        'symptoms_other',
        'history',
        'diagnosis',
        'allergic_drugs',
        'doctor_recommondations',
        'va_od',
        'va_os',
        'signs',
        'vision_od_img1',
        'vision_os_img1',
        'vision_od_img2',
        'vision_os_img2',
        'vision_od_img3',
        'vision_os_img3',
        'vision_od_img4',
        'vision_os_img4',
        'sel_1_od',
        'sel_1_os',
        'sel_2_od',
        'sel_2_os',
        'sel_3_od',
        'sel_3_os',
        'sel_4_od',
        'sel_4_os',
        'sel_5_od',
        'sel_5_os',
        'sel_6_od',
        'sel_6_os',
        'sel_7_od',
        'sel_7_os',
        'sel_8_od',
        'sel_8_os',
        'sel_9_od',
        'sel_9_os',
        'sel_10_od',
        'sel_10_os',
        'sel_11_od',
        'sel_11_os',
        'sel_12_od',
        'sel_12_os',
        'sel_13_od',
        'sel_13_os',
        'sel_14_od',
        'sel_14_os',
        'sel_15_od',
        'sel_15_os',
        'sel_16_od',
        'sel_16_os',
        'sel_17_od',
        'sel_17_os',
        'sel_18_od',
        'sel_18_os',
        'sel_19_od',
        'sel_19_os',
        'sel_20_od',
        'sel_20_os',
        'gonio_od_top',
        'gonio_od_right',
        'gonio_od_bottom',
        'gonio_od_left',
        'gonio_od',
        'gonio_os_top',
        'gonio_os_right',
        'gonio_os_bottom',
        'gonio_os_left',
        'gonio_os',
        'review_date',
        'created_by',
        'is_patient_admission',
        'is_patient_surgery',
    ];

    public function branches()
    {
        return $this->belongsTo(Branch::class, 'branch', 'id');
    }

    public function procedures()
    {
        return $this->hasMany(PatientProcedure::class, 'medical_record_id', 'id');
    }

    public function patient()
    {
        return $this->belongsTo(PatientRegistrations::class, 'patient_id', 'id');
    }

    public function doctor()
    {
        return $this->belongsTo(doctor::class, 'doctor_id', 'id');
    }

    public function branchdetails()
    {
        return $this->belongsTo(Branch::class, 'branch', 'id');
    }
}
