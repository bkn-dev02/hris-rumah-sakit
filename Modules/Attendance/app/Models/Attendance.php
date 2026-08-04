<?php

namespace Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Attendance\Models\AttendanceLocation;
use Modules\Attendance\Models\AttendanceStatus;
use Modules\Attendance\Models\AttendanceCorrection;
use Modules\Attendance\Models\CheckIn;
use Modules\Attendance\Models\CheckOut;
use Modules\Master\Models\Employee;
use Modules\Master\Models\Shift;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'work_date',
        'shift_id',
        'check_in_id',
        'check_out_id',
        'attendance_status_id',
        'determination_type',
        'source',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'employee_id' => 'integer',
            'work_date' => 'date',
            'shift_id' => 'integer',
            'check_in_id' => 'integer',
            'check_out_id' => 'integer',
            'attendance_status_id' => 'integer',
            'determination_type' => 'string',
            'source' => 'string',
            'notes' => 'string',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(AttendanceStatus::class, 'attendance_status_id');
    }

    public function corrections(): HasMany
    {
        return $this->hasMany(AttendanceCorrection::class);
    }

    public function isComplete(): bool
    {
        return $this->hasCheckedIn() && $this->hasCheckedOut();
    }

    public function needsReview(): bool
    {
        return is_null($this->attendance_status_id) && $this->isComplete();
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('work_date', $date);
    }

    public function scopeIncomplete($query)
    {
        return $query->whereNotNull('check_in_at')->whereNull('check_out_at');
    }

    public function scopeUnresolved($query)
    {
        return $query->whereNull('attendance_status_id');
    }

    public function checkIn(): BelongsTo
    {
        return $this->belongsTo(CheckIn::class);
    }

    public function checkOut(): BelongsTo
    {
        return $this->belongsTo(CheckOut::class);
    }
}
