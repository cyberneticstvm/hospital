<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tonometry extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_record_id',
        'patient_id',
        'branch',
        'nct_od',
        'nct_os',
        'nct_time',
        'at_od',
        'at_os',
        'at_time',
        'created_by',
        'updated_by',
    ];
}
