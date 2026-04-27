<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CriminalRecord extends Model
{
    protected $fillable =[
        'person_id',
        'charge',
        'date_committed',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date_committed' => 'date',
        ];
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}