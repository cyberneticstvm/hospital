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
        'od_axl',
        'od_acd',
        'od_lens',
        'od_a_constant',
        'od_iol_power',
        'os_axl',
        'os_acd',
        'os_lens',
        'os_a_constant',
        'os_iol_power',
        'eye',
        'aconst_od1',
        'aconst_os1',
        'iol_od1',
        'iol_os1',
        'aconst_od2',
        'aconst_os2',
        'iol_od2',
        'iol_os2',
        'aconst_od3',
        'aconst_os3',
        'iol_od3',
        'iol_os3',
        'created_by',
        'updated_by',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'created_by');
    }
}
