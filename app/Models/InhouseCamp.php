<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InhouseCamp extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'from_date',
        'to_date',
        'validity',
        'status',
        'created_by',
        'updated_by',
    ];

    public function procedures(){
        return $this->hasMany(InhouseCampProcedure::class, 'camp_id');
    }
}
