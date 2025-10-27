<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class ProductTransfer extends Model
{
    use HasFactory, HasRoles, SoftDeletes;

    /*protected $fillable = [
        'from_branch',
        'to_branch',
        'transfer_date',
        'transfer_note',
        'approved',
        'approved_by',
        'approved_at',
        'created_by',
    ];*/

    protected $guarded = [];

    protected $casts = ['transfer_date' => 'datetime'];

    function details()
    {
        return $this->hasMany(ProductTransferDetail::class, 'transfer_id', 'id');
    }

    public function fromBr()
    {
        return $this->belongsTo(Branch::class, 'from_branch', 'id');
    }

    public function toBr()
    {
        return $this->belongsTo(Branch::class, 'to_branch', 'id');
    }

    public function status()
    {
        return ($this->deleted_at) ? "<span class='badge badge-danger text-danger'>Cancelled</span>" : "<span class='badge badge-success text-success'>Active</span>";
    }
}
