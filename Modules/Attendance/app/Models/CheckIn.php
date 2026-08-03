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
        'checked_at',
        'latitude',
        'longitude',
        'photo',
        'location_id',
        'distance_meters',
        'ip',
        'device',
        'note',
    ];
    protected $casts = ['checked_at' => 'datetime'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(AttendanceLocation::class, 'location_id');
    }
}
