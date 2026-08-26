<?php

namespace Modules\Schedule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Schedule\Database\Factories\SpCandidateFactory;
use Modules\Master\Models\Department;
use Modules\Master\Models\Employee;
use Modules\Master\Models\Shift;

class SpCandidate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'employee_id',
        'date',
        'shift_id',
        'department_id',
        'status',
        'detected_at',
        'late_checkin_at',
        'decision_by',
        'decision_at',
        'decision_note',
    ];

    // protected static function newFactory(): SpCandidateFactory
    // {
    //     // return SpCandidateFactory::new();
    // }

    protected $casts = [
        'date' => 'date',
        'detected_at' => 'datetime',
        'late_checkin_at' => 'datetime',
        'decision_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function decisionMaker()
    {
        return $this->belongsTo(Employee::class, 'decision_by');
    }

    public function manualConfirmations()
    {
        return $this->hasMany(ManualConfirmation::class);
    }

    public function spLetter()
    {
        return $this->hasOne(SpLetter::class);
    }

    public function isResolved(): bool
    {
        return in_array($this->status, [
            'cancelled_manual',
            'cancelled_late_checkin_decision',
            'resolved_issued',
        ]);
    }
}
