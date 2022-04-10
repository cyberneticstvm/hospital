<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class ProductTransfer extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'product',
        'from_branch',
        'to_branch',
        'qty',
        'batch_number',
        'transfer_date',
        'transfer_note',
        'created_by',
    ];
}
