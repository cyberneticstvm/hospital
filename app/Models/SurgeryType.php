<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class SurgeryType extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'surgery_name',
        'description',
        'fee',
        'created_by'
    ];
}
