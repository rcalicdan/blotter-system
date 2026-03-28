<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HearingAttendee extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'hearing_id',
        'person_id',
        'attended',
    ];

    protected function casts(): array
    {
        return [
            'attended' => 'boolean',
        ];
    }

    public function hearing(): BelongsTo
    {
        return $this->belongsTo(Hearing::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
