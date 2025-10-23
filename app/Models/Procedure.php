<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'fee',
        'fee_stkta',
        'is_available_for_consultation'
    ];

    public function inhouseprocedures()
    {
        return $this->hasMany(InhouseCampProcedure::class, 'procedure');
    }
}
