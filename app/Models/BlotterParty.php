<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BlotterPartyRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlotterParty extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'blotter_id',
        'person_id',
        'role',
    ];

    protected function casts(): array
    {
        return [
            'role' => BlotterPartyRole::class,
        ];
    }

    public function blotterEntry(): BelongsTo
    {
        return $this->belongsTo(BlotterEntry::class, 'blotter_id');
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
