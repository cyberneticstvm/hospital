<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationNote extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = ['date_of_surgery' => 'datetime', 'test_dose_time' => 'datetime'];

    public function surgeond()
    {
        return $this->belongsTo(doctor::class, 'surgeon', 'id');
    }
}
