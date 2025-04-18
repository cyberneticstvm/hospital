<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromotionSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = ['scheduled_date' => 'datetime'];

    public function status()
    {
        return ($this->deleted_at) ? "<span class='badge badge-danger'>Deleted</span>" : "<span class='badge badge-success'>Active</span>";
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function waSmsProcessedCount()
    {
        $ocount = PatientRegistrations::whereNotNull('wa_sms_status')->count();
        $lcount = PromotionContact::whereNotNull('wa_sms_status')->count();
        return ($this->scheduled_date == Carbon::today()) ? $ocount + $lcount : 0;
    }
}
