<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ascan extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_record_id',
        'patient_id',
        'branch',
        'od_axl',
        'od_acd',
        'od_lens',
        'od_a_constant',
        'od_iol_power',
        'os_axl',
        'os_acd',
        'os_lens',
        'os_a_constant',
        'os_iol_power',
        'created_by',
        'updated_by',
    ];
}
