<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Purchase extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'product',
        'supplier',
        'order_date',
        'delivery_date',
        'invoice_number',
        'qty',
        'price',
        'total',
        'batch_number',
        'created_by',
    ];
}
