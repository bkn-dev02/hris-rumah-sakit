<?php

namespace Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceStatus extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'category',
        'determination_type',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAutoDetermined($query)
    {
        return $query->where('determination_type', 'auto');
    }

    public function scopeManualOnly($query)
    {
        return $query->where('determination_type', 'manual');
    }

    public function isAutoDetermined(): bool
    {
        return $this->determination_type === 'auto';
    }
}
