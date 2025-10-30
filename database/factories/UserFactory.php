<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
final class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    private static ?string $password; // @phpstan-ignore property.unusedType

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => self::$password ??= 'password',
            'role' => fake()->randomElement(Role::class),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the model is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes): array => [
            'role' => Role::ADMIN,
        ]);
    }

    /**
     * Indicate that the model is a consultant.
     */
    public function consultant(): static
    {
        return $this->state(fn (array $attributes): array => [
            'role' => Role::CONSULTANT,
        ]);
    }

    /**
     * Indicate that the model is a store-level user.
     */
    public function storeLevel(): static
    {
        return $this->state(fn (array $attributes): array => [
            'role' => fake()->randomElement([
                Role::OWNER,
                Role::CFO,
                Role::GM,
                Role::GSM,
                Role::MANAGER,
                Role::EMPLOYEE,
                Role::PORTER,
            ]),
        ]);
    }
}
