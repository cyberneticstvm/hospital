<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Spectacle extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'medical_record_id',
        'patient_id',
        're_dist_sph',
        're_dist_cyl',
        're_dist_axis',
        're_dist_va',
        're_dist_prism',
        're_dist_add',
        're_int_sph',
        're_int_cyl',
        're_int_axis',
        're_int_va',
        're_int_prism',
        're_int_add',
        're_near_sph',
        're_near_cyl',
        're_near_axis',
        're_near_va',
        're_near_prism',
        're_near_add',
        'le_dist_sph',
        'le_dist_cyl',
        'le_dist_axis',
        'le_dist_va',
        'le_dist_prism',
        'le_dist_add',
        'le_int_sph',
        'le_int_cyl',
        'le_int_axis',
        'le_int_va',
        'le_int_prism',
        'le_int_add',
        'le_near_sph',
        'le_near_cyl',
        'le_near_axis',
        'le_near_va',
        'le_near_prism',
        'le_near_add',
        're_base_curve',
        're_dia',
        're_sph',
        're_cyl',
        're_axis',
        'le_base_curve',
        'le_dia',
        'le_sph',
        'le_cyl',
        'le_axis',
        'vd',
        'ipd',
        'npd',
        'rpd',
        'lpd',
        'vbr',
        'vbl',
        're_iop',
        'le_iop',
        'arm_od_sph',
        'arm_od_cyl',
        'arm_od_axis',
        'arm_os_sph',
        'arm_os_cyl',
        'arm_os_axis',
        'pgp_od_sph',
        'pgp_od_cyl',
        'pgp_od_axis',
        'pgp_od_add',
        'pgp_od_vision',
        'pgp_od_nv',
        'pgp_os_sph',
        'pgp_os_cyl',
        'pgp_os_axis',
        'pgp_os_add',
        'pgp_os_vision',
        'pgp_os_nv',
        'dr_od_sph',
        'dr_od_cyl',
        'dr_od_axis',
        'dr_od_add',
        'dr_od_vision',
        'dr_od_nv',
        'dr_os_sph',
        'dr_os_cyl',
        'dr_os_axis',
        'dr_os_add',
        'dr_os_vision',
        'dr_os_nv',
        'bm_k1_od_a',
        'bm_k1_os_a',
        'bm_k2_od_a',
        'bm_k2_os_a',
        'bm_k1_od_m',
        'bm_k1_os_m',
        'bm_k2_od_m',
        'bm_k2_os_m',
        'bm_od_axl',
        'bm_os_axl',
        'bm_od_acd',
        'bm_os_acd',
        'bm_od_lens',
        'bm_os_lens',
        'bm_od_kvalue_a',
        'bm_os_kvalue_a',
        'bm_od_iol',
        'bm_os_iol',
        'iop_at_r',
        'iop_at_l',
        'iop_nct_time',
        'iop_at_time',
        'remarks',
        'advice',
        'fee',
        'review_date',
        'glasses_prescribed',
        'created_by',
        'updated_by',
    ];

    public function updateduser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function patient()
    {
        return $this->belongsTo(PatientRegistrations::class, 'patient_id', 'id');
    }
}
