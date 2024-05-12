<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Branch extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'branch_name',
        'display_name',
        'contact_number',
        'address',
        'registration_fee',
        'fee_vision',
        'inhouse_camp_limit',
        'booking_available',
        'hospital_id',
    ];

    public function getUserBranches()
    {
        return $this->hasMany(UserBranch::class);
    }
}
