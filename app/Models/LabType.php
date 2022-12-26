<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class LabType extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'category_id',
        'lab_type_name',
        'description',
        'fee',
        'surgery_type',
        'tested_from',
        'created_by'
    ];
}
