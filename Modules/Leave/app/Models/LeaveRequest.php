<?php

namespace Modules\Leave\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Master\Models\Employee;

class LeaveRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'attachment',
        'status',
        'supervisor_id',
        'supervisor_decided_at',
        'supervisor_note',
        'hr_id',
        'hr_decided_at',
        'hr_note',
    ];

    protected $casts = [
        'start_date'             => 'date',
        'end_date'               => 'date',
        'supervisor_decided_at'  => 'datetime',
        'hr_decided_at'          => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }

    public function hrApprover(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'hr_id');
    }
}
