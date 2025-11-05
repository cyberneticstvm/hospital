<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesReturn extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(SalesReturnDetail::class, 'return_id', 'id');
    }
}
