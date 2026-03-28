<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DisputeStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Dispute extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_number',
        'blotter_id',
        'subject',
        'description',
        'status',
        'filed_by',
        'assigned_to',
    ];

    protected function casts(): array
    {
        return [
            'status' => DisputeStatus::class,
        ];
    }

    public function blotterEntry(): BelongsTo
    {
        return $this->belongsTo(BlotterEntry::class, 'blotter_id');
    }

    public function filer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'filed_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function parties(): HasMany
    {
        return $this->hasMany(DisputeParty::class);
    }

    public function hearings(): HasMany
    {
        return $this->hasMany(Hearing::class);
    }

    public function resolution(): HasOne
    {
        return $this->hasOne(Resolution::class);
    }
}
