<?php

namespace Modules\Master\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Attendance\Models\AttendanceLocation;
use Modules\Security\Models\User;
use Modules\Master\Models\EmploymentStatus;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'slug',
        'employee_number',
        'name',
        'gender',
        'place_of_birth',
        'date_of_birth',
        'national_id_number',
        'address',
        'phone',
        'email',
        'marital_status',
        'education_level',
        'education_major',
        'photo',
        'hire_date',
        'employment_status_id',
        'is_active',
        'attendance_location_id',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'hire_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    protected bool $placementLoaded = false;
    protected ?EmployeePlacement $cachedPlacement = null;

    protected bool $shiftScheduleLoaded = false;
    protected ?EmployeeShiftSchedule $cachedShiftSchedule = null;

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function employmentStatus(): BelongsTo
    {
        return $this->belongsTo(EmploymentStatus::class);
    }

    public function placements(): HasMany
    {
        return $this->hasMany(EmployeePlacement::class);
    }

    public function currentPlacement(): ?EmployeePlacement
    {
        return $this->placements()->active()->latest('start_date')->first();
    }

    public function currentDepartment(): ?Department
    {
        return $this->currentPlacement()?->department;
    }

    public function currentPosition(): ?Position
    {
        return $this->currentPlacement()?->position;
    }

    public function currentShiftSchedule(): ?EmployeeShiftSchedule
    {
        return $this->shiftSchedules()->active()->latest('start_date')->first();
    }

    public function currentShift(): ?Shift
    {
        return $this->currentShiftSchedule()?->shift;
    }

    public function forgetPlacementCache(): void
    {
        $this->placementLoaded = false;
        $this->cachedPlacement = null;
    }

    public function forgetShiftScheduleCache(): void
    {
        $this->shiftScheduleLoaded = false;
        $this->cachedShiftSchedule = null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function shiftSchedules(): HasMany
    {
        return $this->hasMany(EmployeeShiftSchedule::class);
    }

    public function activeShiftFor(string $date): ?Shift
    {
        $schedule = $this->shiftSchedules()
            ->where('start_date', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $date);
            })
            ->latest('start_date')
            ->first();

        return $schedule?->shift;
    }

    public function attendanceLocation(): BelongsTo
    {
        return $this->belongsTo(AttendanceLocation::class, 'attendance_location_id');
    }
}
