<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Supplier extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'name',
        'contact_number',
        'email',
        'address',
        'exapiry_notification',
        'created_by',
    ];
}
