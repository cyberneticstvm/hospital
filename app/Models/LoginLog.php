<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'device',
        'ip',
        'country_name',
        'region_name',
        'city_name',
        'zip_code',
        'latitude',
        'longitude',
        'logged_in',
        'logged_out',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'session_id');
    }
}
