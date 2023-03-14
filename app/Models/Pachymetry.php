<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pachymetry extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_record_id',
        'patient_id',
        'branch',
        'od_iop',
        'od_cct',
        'od_ciop',
        'os_iop',
        'os_cct',
        'os_ciop',
        'img1',
        'img1_value',
        'img2',
        'img2_value',
        'img3',
        'img3_value',
        'img4',
        'img4_value',
        'created_by',
        'updated_by',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'created_by');
    }
}
