<?php

namespace App\Models;

use App\Enums\HearingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Hearing extends Model
{
    use HasFactory;

    protected $fillable = [
        'dispute_id',
        'scheduled_date',
        'scheduled_time',
        'location',
        'status',
        'notes',
        'conducted_by',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'status'         => HearingStatus::class,
        ];
    }

    public function dispute(): BelongsTo
    {
        return $this->belongsTo(Dispute::class);
    }

    public function conductor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'conducted_by');
    }

    public function attendees(): HasMany
    {
        return $this->hasMany(HearingAttendee::class);
    }

    public function resolution(): HasOne
    {
        return $this->hasOne(Resolution::class);
    }
}