<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tonometry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'medical_record_id',
        'patient_id',
        'branch',
        'nct_od',
        'nct_os',
        'nct_time',
        'at_od',
        'at_os',
        'at_time',
        'created_by',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function status()
    {
        return ($this->deleted_at) ? "<span class='badge badge-danger'>Cancelled</span>" : "<span class='badge badge-info'>Active</span>";
    }
}
