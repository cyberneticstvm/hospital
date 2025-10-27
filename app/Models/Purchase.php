<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, HasRoles, SoftDeletes;

    protected $guarded = [];

    protected $casts = ['order_date' => 'datetime', 'delivery_date' => 'datetime'];

    public function supplierr()
    {
        return $this->belongsTo(Supplier::class, 'supplier', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(PurchaseDetail::class, 'purchase_id', 'id');
    }

    public function status()
    {
        return ($this->deleted_at) ? "<span class='badge badge-danger text-danger'>Cancelled</span>" : "<span class='badge badge-success text-success'>Active</span>";
    }
}
