<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pachymetry extends Model
{
    use HasFactory, SoftDeletes;

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

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function patient()
    {
        return $this->belongsTo(PatientRegistrations::class, 'patient_id', 'id');
    }

    public function status()
    {
        return ($this->deleted_at) ? "<span class='badge badge-danger text-danger'>Cancelled</span>" : "<span class='badge badge-success text-success'>Active</span>";
    }
}
