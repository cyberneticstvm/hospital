<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurgeryConsumableItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'surgery_id',
        'consumable_id',
        'default_qty',
        'created_by',
        'updated_by',
    ];

    public function surgery(){
        return $this->belongsTo(SurgeryType::class, 'surgery_id', 'id');
    }

    public function consumable(){
        return $this->belongsTo(SurgeryConsumable::class, 'consumable_id', 'id');
    }
}
