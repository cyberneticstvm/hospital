<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Camp extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_name',
        'age',
        'standard',
        're_sph',
        're_cyl',
        're_axis',
        're_add',
        're_vb',
        're_va',
        'le_sph',
        'le_cyl',
        'le_axis',
        'le_add',
        'le_vb',
        'le_va',
        'branch',
        'treatment_required',
        'specs_required',
        'camp_date',
        'created_by',
        'updated_by',
    ];
}