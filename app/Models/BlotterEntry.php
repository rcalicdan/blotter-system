<?php

namespace App\Models;

use App\Enums\BlotterStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BlotterEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'blotter_number',
        'incident_date',
        'incident_time',
        'incident_location',
        'narrative',
        'status',
        'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'incident_date' => 'date',
            'status'        => BlotterStatus::class,
        ];
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function parties(): HasMany
    {
        return $this->hasMany(BlotterParty::class);
    }

    public function dispute(): HasOne
    {
        return $this->hasOne(Dispute::class, 'blotter_id');
    }
}