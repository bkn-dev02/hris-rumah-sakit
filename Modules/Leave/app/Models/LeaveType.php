<?php

namespace Modules\Leave\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveType extends Model
{
    use SoftDeletes;

    protected $fillable = ['code', 'name', 'description', 'is_active', 'requires_quota'];

    protected $casts = [
        'is_active' => 'boolean',
        'requires_quota' => 'boolean',
    ];

    public function quotas(): HasMany
    {
        return $this->hasMany(EmployeeLeaveQuota::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }
}
