<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DischargeSummaryReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'summary_id',
        'review_date',
        'review_time',
    ];

    protected $casts = ['review_date' => 'date'];
}
