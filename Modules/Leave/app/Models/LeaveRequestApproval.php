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

    public function isEligibleApprover(Employee $employee): bool
    {
        if ($this->approver_employee_id === $employee->id) {
            return true;
        }

        $originalApprover = Employee::withTrashed()->find($this->approver_employee_id);

        if (! $originalApprover) {
            return false;
        }

        $isOriginalInactive = $originalApprover->trashed() || ! $originalApprover->is_active;

        if (! $isOriginalInactive) {
            return false;
        }

        $originalPlacement = $originalApprover->placements()
            ->withTrashed()
            ->orderByDesc('start_date')
            ->with('position')
            ->first();

        $approverPlacement = $employee->currentPlacement();

        if (! $originalPlacement || ! $approverPlacement) {
            return false;
        }

        return $originalPlacement->department_id === $approverPlacement->department_id
            && $originalPlacement->position?->level === $approverPlacement->position?->level;
    }
}
