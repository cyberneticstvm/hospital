<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalFitness extends Model
{
    use HasFactory;

    protected $fillable = [        
        'medical_record_id',
        'patient',
        'branch',
        'head',
        'fitness_advice',
        'notes',
        'created_by',
        'updated_by'
    ];
}
