<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class LabClinic extends Model
{
    use HasFactory, HasRoles;

    protected $casts = ['created_at' => 'datetiem'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
