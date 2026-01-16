<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class doctor extends Model
{
    use HasFactory, HasRoles, SoftDeletes;

    protected $fillable = [
        'doctor_name',
        'designation',
        'reg_no',
        'additional_qualification',
        'date_of_join',
        'doctor_fee',
        'doc_type',
    ];

    public function doctor_has_departments()
    {
        return $this->hasMany(doctor_has_department::class, 'doctor_id');
    }

    public function doctype($id)
    {
        return DB::table('types')->where('id', $id)->first()->name ?? '';
    }
}
