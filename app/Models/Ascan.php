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
        'axl',
        'acd',
        'lens',
        'a_constant',
        'iol_power',
        'fee',
        'created_by',
        'updated_by',
    ];
}
