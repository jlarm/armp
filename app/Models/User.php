<?php

declare(strict_types=1);

namespace App\Models;

use App\Concerns\HasRoles;
use App\Enums\Role;
use Carbon\CarbonInterface;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $email
 * @property-read CarbonInterface|null $email_verified_at
 * @property-read string $password
 * @property-read Role $role
 * @property-read string|null $remember_token
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
final class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn (string $word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function dealerships(): BelongsToMany
    {
        return $this->belongsToMany(Dealership::class)->withTimestamps();
    }

    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class)->withTimestamps();
    }

    public function accessibleDealerships(): Builder
    {
        if ($this->isAdmin()) {
            return Dealership::query();
        }

        if ($this->isConsultant()) {
            return Dealership::query()->whereHas('users', fn (Builder $q) => $q->where('user_id', $this->id));
        }

        return Dealership::query()->whereHas('stores.users', fn (Builder $q) => $q->where('user_id', $this->id));
    }

    public function hasAccessToDealership(Dealership $dealership): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isConsultant()) {
            return $this->dealerships->contains($dealership->id);
        }

        return false;
    }

    public function hasAccessToStore(Store $store): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isConsultant()) {
            return $this->dealerships->contains($store->dealership_id);
        }

        return $this->stores->contains($store->id);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'name' => 'string',
            'email' => 'string',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_secret' => 'string',
            'two_factor_recovery_code' => 'string',
            'two_factor_confirmed_at' => 'datetime',
            'role' => Role::class,
            'remember_token' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
