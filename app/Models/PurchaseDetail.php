<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    use HasFactory;

    protected $casts = ['expiry_date' => 'datetime'];

    protected $guarded = [];

    public function productDetail()
    {
        return $this->belongsTo(Product::class, 'product', 'id');
    }
}
