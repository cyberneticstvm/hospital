<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class doctor extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'doctor_name',
        'designation',
        'date_of_join',
        'doctor_fee',
    ];

    public function doctor_has_departments(){
        return $this->hasMany(doctor_has_department::class, 'doctor_id');
    }
}
