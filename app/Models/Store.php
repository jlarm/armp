<?php

declare(strict_types=1);

namespace App\Models;

use App\Concerns\HasRoleFilteredUsers;
use App\Enums\State;
use Carbon\CarbonInterface;
use Database\Factories\StoreFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property-read int $id
 * @property-read string $uuid
 * @property-read string $name
 * @property-read string $slug
 * @property-read string $address
 * @property-read string $city
 * @property-read State $state
 * @property-read string $zip
 * @property-read string $phone
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
final class Store extends Model
{
    /** @use HasFactory<StoreFactory> */
    use HasFactory, HasRoleFilteredUsers;

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'integer',
            'uuid' => 'string',
            'dealership_id' => 'integer',
            'name' => 'string',
            'slug' => 'string',
            'address' => 'string',
            'city' => 'string',
            'state' => State::class,
            'zip' => 'string',
            'phone' => 'string',
        ];
    }

    /**
     * @return BelongsTo<Dealership, $this>
     */
    public function dealership(): BelongsTo
    {
        return $this->belongsTo(Dealership::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
