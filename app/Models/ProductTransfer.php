<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class ProductTransfer extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'from_branch',
        'to_branch',
        'transfer_date',
        'transfer_note',
        'created_by',
    ];
}
