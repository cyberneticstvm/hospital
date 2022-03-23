<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class doctor_has_department extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'department_id',
    ];

    public function doctors(){
        return $this->belongsTo(doctor::class);
    }
}
