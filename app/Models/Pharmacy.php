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
        'customer_id',
        'other_info',
        'used_for',
        'branch',
        'created_by',
        'updated_by',
    ];
}
