<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturnDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function sreturn()
    {
        return $this->belongsTo(SalesReturn::class, 'return_id', 'id');
    }
}
