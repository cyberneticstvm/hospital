<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InhouseCampProcedure extends Model
{
    use HasFactory;

    protected $fillable = [
        'camp_id',
        'procedure',
    ];

    public function procedure(){
        return $this->belongsTo(Procedure::class);
    }

    public function camp(){
        return $this->belongsTo(InhouseCamp::class, 'camp_id');
    }
}
