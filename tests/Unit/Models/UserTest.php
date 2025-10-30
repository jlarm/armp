<?php

declare(strict_types=1);

use App\Models\Dealership;
use App\Models\Store;
use App\Models\User;

test('to array', function (): void {
    $user = User::factory()->create()->refresh();

    $keys = array_keys($user->toArray());
    sort($keys);

    $expectedKeys = [
        'created_at',
        'email',
        'email_verified_at',
        'id',
        'name',
        'role',
        'updated_at',
    ];

    expect($keys)->toBe($expectedKeys);
});

test('initials returns first letter of first two words', function (): void {
    $user = User::factory()->create(['name' => 'John Doe']);

    expect($user->initials())->toBe('JD');
});

test('initials returns single letter for one word name', function (): void {
    $user = User::factory()->create(['name' => 'John']);

    expect($user->initials())->toBe('J');
});

test('initials only uses first two words', function (): void {
    $user = User::factory()->create(['name' => 'John Michael Doe']);

    expect($user->initials())->toBe('JM');
});

test('dealerships relationship returns belongs to many', function (): void {
    $user = User::factory()->create();
    $dealership = Dealership::factory()->create();

    $user->dealerships()->attach($dealership);

    expect($user->dealerships)->toHaveCount(1)
        ->and($user->dealerships->first()->id)->toBe($dealership->id);
});

test('stores relationship returns belongs to many', function (): void {
    $user = User::factory()->create();
    $dealership = Dealership::factory()->create();
    $store = Store::factory()->for($dealership)->create();

    $user->stores()->attach($store);

    expect($user->stores)->toHaveCount(1)
        ->and($user->stores->first()->id)->toBe($store->id);
});

test('accessible dealerships returns all dealerships for admin', function (): void {
    $user = User::factory()->admin()->create();
    $dealerships = Dealership::factory()->count(3)->create();

    $accessible = $user->accessibleDealerships()->get();

    expect($accessible)->toHaveCount(3);
});

test('accessible dealerships returns only associated dealerships for consultant', function (): void {
    $user = User::factory()->consultant()->create();
    $associatedDealership = Dealership::factory()->create();
    $otherDealership = Dealership::factory()->create();

    $user->dealerships()->attach($associatedDealership);

    $accessible = $user->accessibleDealerships()->get();

    expect($accessible)->toHaveCount(1)
        ->and($accessible->first()->id)->toBe($associatedDealership->id);
});

test('accessible dealerships returns dealerships with associated stores for store level user', function (): void {
    $user = User::factory()->storeLevel()->create();
    $dealership1 = Dealership::factory()->create();
    $dealership2 = Dealership::factory()->create();
    $store = Store::factory()->for($dealership1)->create();

    $user->stores()->attach($store);

    $accessible = $user->accessibleDealerships()->get();

    expect($accessible)->toHaveCount(1)
        ->and($accessible->first()->id)->toBe($dealership1->id);
});

test('has access to dealership returns true for admin', function (): void {
    $user = User::factory()->admin()->create();
    $dealership = Dealership::factory()->create();

    expect($user->hasAccessToDealership($dealership))->toBeTrue();
});

test('has access to dealership returns true for consultant with associated dealership', function (): void {
    $user = User::factory()->consultant()->create();
    $dealership = Dealership::factory()->create();

    $user->dealerships()->attach($dealership);

    expect($user->hasAccessToDealership($dealership))->toBeTrue();
});

test('has access to dealership returns false for consultant without associated dealership', function (): void {
    $user = User::factory()->consultant()->create();
    $dealership = Dealership::factory()->create();

    expect($user->hasAccessToDealership($dealership))->toBeFalse();
});

test('has access to dealership returns false for store level user', function (): void {
    $user = User::factory()->storeLevel()->create();
    $dealership = Dealership::factory()->create();

    expect($user->hasAccessToDealership($dealership))->toBeFalse();
});

test('has access to store returns true for admin', function (): void {
    $user = User::factory()->admin()->create();
    $dealership = Dealership::factory()->create();
    $store = Store::factory()->for($dealership)->create();

    expect($user->hasAccessToStore($store))->toBeTrue();
});

test('has access to store returns true for consultant with associated dealership', function (): void {
    $user = User::factory()->consultant()->create();
    $dealership = Dealership::factory()->create();
    $store = Store::factory()->for($dealership)->create();

    $user->dealerships()->attach($dealership);

    expect($user->hasAccessToStore($store))->toBeTrue();
});

test('has access to store returns false for consultant without associated dealership', function (): void {
    $user = User::factory()->consultant()->create();
    $dealership = Dealership::factory()->create();
    $store = Store::factory()->for($dealership)->create();

    expect($user->hasAccessToStore($store))->toBeFalse();
});

test('has access to store returns true for store level user with associated store', function (): void {
    $user = User::factory()->storeLevel()->create();
    $dealership = Dealership::factory()->create();
    $store = Store::factory()->for($dealership)->create();

    $user->stores()->attach($store);

    expect($user->hasAccessToStore($store))->toBeTrue();
});

test('has access to store returns false for store level user without associated store', function (): void {
    $user = User::factory()->storeLevel()->create();
    $dealership = Dealership::factory()->create();
    $store = Store::factory()->for($dealership)->create();

    expect($user->hasAccessToStore($store))->toBeFalse();
});
