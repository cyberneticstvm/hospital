<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Product extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'category_id',
        'product_name',
        'hsn',
        'available_for_consultation',
        'tax_percentage',
    ];
}
