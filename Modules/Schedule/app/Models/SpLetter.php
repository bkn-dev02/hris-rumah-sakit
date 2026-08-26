<?php

namespace Modules\Schedule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Schedule\Database\Factories\SpLetterFactory;
use Modules\Master\Models\Employee;

class SpLetter extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'sp_candidate_id',
        'employee_id',
        'file_path',
        'sp_number',
        'issued_by',
        'issued_at',
    ];

    // protected static function newFactory(): SpLetterFactory
    // {
    //     // return SpLetterFactory::new();
    // }

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    public function spCandidate()
    {
        return $this->belongsTo(SpCandidate::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function issuer()
    {
        return $this->belongsTo(Employee::class, 'issued_by');
    }
}
