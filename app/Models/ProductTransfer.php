<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class ProductTransfer extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'from_branch',
        'to_branch',
        'transfer_date',
        'transfer_note',
        'approved',
        'approved_by',
        'approved_at',
        'created_by',
    ];

    public function fromBr()
    {
        return $this->belongsTo(Branch::class, 'from_branch', 'id');
    }

    public function toBr()
    {
        return $this->belongsTo(Branch::class, 'to_branch', 'id');
    }
}
