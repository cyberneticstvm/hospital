<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Laser extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function status()
    {
        return ($this->deleted_at) ? "<span class='badge badge-danger text-danger'>Cancelled</span>" : "<span class='badge badge-success text-success'>Active</span>";
    }
}
