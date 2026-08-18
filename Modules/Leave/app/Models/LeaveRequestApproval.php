<?php

namespace Modules\Leave\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Master\Models\Employee;

class LeaveRequestApproval extends Model
{
    protected $fillable = [
        'leave_request_id',
        'approver_employee_id',
        'sequence',
        'type',
        'status',
        'decided_at',
        'note',
    ];

    protected $casts = [
        'decided_at' => 'datetime',
    ];

    public function leaveRequest(): BelongsTo
    {
        return $this->belongsTo(LeaveRequest::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'approver_employee_id');
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'supervisor' => 'Atasan',
            'hrd' => 'HRD',
            'director' => 'Direktur',
            default => ucfirst($this->type),
        };
    }
}
