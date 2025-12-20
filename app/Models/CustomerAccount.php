<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = ['pdate' => 'datetime'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function delStatus()
    {
        return $this->deleted_at ? "<span class='text-danger'>Deleted</span>" : "<span class='text-success'>Active</span>";
    }
}
