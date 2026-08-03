<?php

namespace Modules\Security\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Master\Models\Employee;
use Modules\Security\Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Security\Traits\LogsActivity;

class User extends Authenticatable
{
    use HasFactory, SoftDeletes, HasApiTokens, LogsActivity;

    protected $table = 'users';

    protected $fillable = [
        'slug',
        'employee_id',
        'username',
        'email',
        'password',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'is_active'     => 'boolean',
            'last_login_at' => 'datetime',
            'password'      => 'hashed',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withPivot('created_at');
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }


    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function hasPermission(string $code): bool
    {
        return $this->roles()
            ->where('is_active', true)
            ->whereHas('permissions', function ($query) use ($code) {
                $query->where('code', $code)->where('is_active', true);
            })
            ->exists();
    }
}
