<?php

namespace Modules\Schedule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Schedule\Database\Factories\ManualConfirmationFactory;
use Modules\Master\Models\Employee;
use Modules\Master\Models\Shift;

class ManualConfirmation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'employee_id',
        'date',
        'shift_id',
        'sp_candidate_id',
        'note',
        'confirmed_by',
    ];

    // protected static function newFactory(): ManualConfirmationFactory
    // {
    //     // return ManualConfirmationFactory::new();
    // }

    protected $casts = [
        'date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function spCandidate()
    {
        return $this->belongsTo(SpCandidate::class);
    }

    public function confirmer()
    {
        return $this->belongsTo(Employee::class, 'confirmed_by');
    }
}
