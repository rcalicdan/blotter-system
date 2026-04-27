<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Officer extends Model
{
    protected $fillable =[
        'first_name',
        'last_name',
        'badge_number',
        'rank',
        'status',
    ];

    public function getFullNameAttribute(): string
    {
        $rankPrefix = $this->rank ? "{$this->rank} " : '';
        return "{$rankPrefix}{$this->first_name} {$this->last_name}";
    }

    public function disputes(): HasMany
    {
        return $this->hasMany(Dispute::class);
    }
}