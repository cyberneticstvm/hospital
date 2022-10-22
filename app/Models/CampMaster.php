<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'camp_id',
        'venue',
        'address',
        'type',
        'from',
        'to',
        'cordinator',
        'optometrist',
        'branch',
        'created_by',
        'updated_by',
    ];
}
