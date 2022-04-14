<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class PatientMedicineRecord extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'medical_record_id',
        'mrn',
        'medicine',
        'dosage',
        'dosage1',
        'notes',
    ];
}
