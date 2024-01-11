<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorOptometrist extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function doctor()
    {
        return $this->belongsTo(doctor::class, 'doctor_id', 'id');
    }

    public function optometrist()
    {
        return $this->belongsTo(User::class, 'optometrist_id', 'id');
    }
}
