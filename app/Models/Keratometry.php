<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keratometry extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_record_id',
        'patient_id',
        'branch',
        'k1_od_auto',
        'k1_os_auto',
        'k1_od_axis_a',
        'k1_os_axis_a',
        'k2_od_auto',
        'k2_os_auto',
        'k2_od_axis_a',
        'k2_os_axis_a',
        'k1_od_manual',
        'k1_os_manual',
        'k1_od_axis_m',
        'k1_os_axis_m',
        'k2_od_manual',
        'k2_os_manual',
        'k2_od_axis_m',
        'k2_os_axis_m',
        'created_by',
        'updated_by',
    ];
}
