<?php

namespace Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Master\Models\Shift;

class EmployeeShiftSchedule extends Model
{
    protected $fillable = ['employee_id', 'shift_id', 'start_date', 'end_date', 'notes'];
    protected $casts = ['start_date' => 'date', 'end_date' => 'date'];

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }
}
