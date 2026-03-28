<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function getNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function initials(): string
    {
        return Str::of($this->first_name)->substr(0, 1)->upper()
            ->append(Str::of($this->last_name)->substr(0, 1)->upper())
            ->toString()
        ;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === UserRole::SuperAdmin;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isStaff(): bool
    {
        return $this->role === UserRole::Staff;
    }

    public function blotterEntries(): HasMany
    {
        return $this->hasMany(BlotterEntry::class, 'recorded_by');
    }

    public function filedDisputes(): HasMany
    {
        return $this->hasMany(Dispute::class, 'filed_by');
    }

    public function assignedDisputes(): HasMany
    {
        return $this->hasMany(Dispute::class, 'assigned_to');
    }

    public function hearings(): HasMany
    {
        return $this->hasMany(Hearing::class, 'conducted_by');
    }

    public function resolutions(): HasMany
    {
        return $this->hasMany(Resolution::class, 'resolved_by');
    }
}
