<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_name',
        'medical_record_id',
        'other_info',
        'branch',
        'created_by',
        'updated_by',
    ];
}
