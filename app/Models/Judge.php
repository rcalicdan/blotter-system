<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Judge extends Model
{
    protected $fillable =[
        'first_name',
        'last_name',
        'court_branch',
        'status',
    ];

    public function getFullNameAttribute(): string
    {
        return "Hon. {$this->first_name} {$this->last_name}";
    }

    public function hearings(): HasMany
    {
        return $this->hasMany(Hearing::class);
    }
}