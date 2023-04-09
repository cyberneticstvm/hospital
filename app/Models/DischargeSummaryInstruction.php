<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DischargeSummaryInstruction extends Model
{
    use HasFactory;

    protected $fillable = [
        'summary_id',
        'instruction_id',
    ];

    public function instruction(){
        return $this->belongsTo(PostOperativeInstruction::class, 'instruction_id', 'id');
    }
}
