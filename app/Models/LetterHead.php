<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterHead extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch',
        'date',
        'from',
        'to',
        'subject',
        'matter',
        'description',
        'created_by',
        'updated_by',
    ];
}
