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
        'remarks',
        'advice',
        'review_date',
        'created_by',
    ];
}
