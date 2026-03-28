<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DisputePartyRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisputeParty extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'dispute_id',
        'person_id',
        'role',
    ];

    protected function casts(): array
    {
        return [
            'role' => DisputePartyRole::class,
        ];
    }

    public function dispute(): BelongsTo
    {
        return $this->belongsTo(Dispute::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
