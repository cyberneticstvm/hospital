<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DischargeSummaryDiagnosis extends Model
{
    use HasFactory;

    protected $fillable = [
        'summary_id',
        'diagnosis',
    ];
}
