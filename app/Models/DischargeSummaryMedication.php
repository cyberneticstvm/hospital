<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DischargeSummaryMedication extends Model
{
    use HasFactory;

    protected $fillable = [
        'summary_id',
        'medicine',
        'type',
        'qty',
        'notes',
    ];

    public function product(){
        return $this->belongsTo(Product::class, 'medicine', 'id');
    }
}
