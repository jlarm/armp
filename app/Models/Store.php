<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\State;
use Database\Factories\StoreFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Store extends Model
{
    /** @use HasFactory<StoreFactory> */
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'uuid' => 'string',
        'name' => 'string',
        'address' => 'string',
        'city' => 'string',
        'state' => State::class,
        'zip' => 'string',
        'phone' => 'string',
    ];

    public function dealership(): BelongsTo
    {
        return $this->belongsTo(Dealership::class);
    }
}
