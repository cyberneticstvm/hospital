<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class department extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'department_name',
    ];
}
