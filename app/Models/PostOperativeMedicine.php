<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostOperativeMedicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'surgery_id',
        'medical_record_id',
        'patient',
        'branch',
        'type',
        'notes',
        'status',
        'bill_generated',
        'created_by',
        'updated_by',
    ];
}
