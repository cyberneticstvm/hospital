<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientSurgeryConsumableList extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_surgery_consumabe_id',
        'consumable_id',
        'cost',
        'qty',
        'total',
    ];

    public function consumable(){
        return $this->belongsTo(SurgeryConsumable::class, 'consumable_id', 'id');
    }
}
