<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'birthdate',
        'address',
        'contact_number',
    ];

    protected function casts(): array
    {
        return [
            'birthdate' => 'date',
        ];
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function blotterParties(): HasMany
    {
        return $this->hasMany(BlotterParty::class);
    }

    public function disputeParties(): HasMany
    {
        return $this->hasMany(DisputeParty::class);
    }

    public function hearingAttendees(): HasMany
    {
        return $this->hasMany(HearingAttendee::class);
    }
}