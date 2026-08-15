<?php

namespace Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Attendance\Models\AttendanceLocation;
use Modules\Master\Models\Employee;

class CheckIn extends Model
{
    protected $fillable = [
        'employee_id',
        'type',
        'checked_at',
        'latitude',
        'longitude',
        'photo',
        'location_id',
        'distance_meters',
        'ip',
        'device',
        'note',
        'emergency_photo',
        'emergency_reason',
        'emergency_status',
        'emergency_decided_by',
        'emergency_decided_at',
        'emergency_decision_note',
    ];
    protected function casts(): array
    {
        return [
            'checked_at' => 'datetime',
            'emergency_decided_at' => 'datetime',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(AttendanceLocation::class, 'location_id');
    }

    public function scopeEmergency($query)
    {
        return $query->where('type', 'emergency');
    }

    public function scopePendingEmergency($query)
    {
        return $query->where('type', 'emergency')->where('emergency_status', 'pending');
    }
}
