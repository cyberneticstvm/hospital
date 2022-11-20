<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_record_id',
        'pharmacy_id',
        'patient_id',
        'branch',
        'amount',
        'payment_mode',
        'notes',
        'created_by',
    ];
}
