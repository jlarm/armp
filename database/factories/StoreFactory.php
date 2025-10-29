<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\State;
use App\Models\Dealership;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Store>
 */
final class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $storeName = $this->faker->company();

        return [
            'uuid' => (string) Str::uuid(),
            'dealership_id' => Dealership::factory(),
            'name' => $storeName,
            'slug' => (string) Str::slug($storeName),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->randomElement(State::class),
            'zip' => fake()->numberBetween(100000, 999999),
            'phone' => fake()->phoneNumber(),
        ];
    }
}
