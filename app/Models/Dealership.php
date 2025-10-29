<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\DealershipFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Dealership extends Model
{
    /** @use HasFactory<DealershipFactory> */
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'uuid' => 'string',
        'name' => 'string',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime',
    ];

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }
}
