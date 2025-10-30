<?php

declare(strict_types=1);
use App\Models\Dealership;
use App\Models\Store;
use App\Models\User;

test('to array', function (): void {
    $dealership = Dealership::factory()->create()->refresh();

    expect(array_keys($dealership->toArray()))
        ->toBe([
            'id',
            'uuid',
            'name',
            'created_by',
            'created_at',
            'updated_at',
        ]);
});

test('stores relationship returns has many', function (): void {
    $dealership = Dealership::factory()->create();
    $store = Store::factory()->for($dealership)->create();

    expect($dealership->stores)->toHaveCount(1)
        ->and($dealership->stores->first()->id)->toBe($store->id);
});

test('creator relationship returns belongs to', function (): void {
    $user = User::factory()->consultant()->create();
    $dealership = Dealership::factory()->create(['created_by' => $user->id]);

    expect($dealership->creator->id)->toBe($user->id);
});

test('users relationship return belongs to many', function (): void {
    $user = User::factory()->admin()->create();
    $user2 = User::factory()->consultant()->create();
    $dealership = Dealership::factory()->create(['created_by' => $user->id]);

    $user->dealerships()->attach($dealership);
    $user2->dealerships()->attach($dealership);

    expect($dealership->users->count())->toBe(2)
        ->and($dealership->users->first()->id)->toBe($user->id);
});

test('consultant relationship returns belongs to many', function (): void {
    $user = User::factory()->consultant()->create();
    $dealership = Dealership::factory()->create(['created_by' => $user->id]);

    $user->dealerships()->attach($dealership);

    expect($user->dealerships)->toHaveCount(1)
        ->and($user->dealerships->first()->id)->toBe($dealership->id);
});

test('all accessible users includes admins', function (): void {
    $dealership = Dealership::factory()->create();
    $admin = User::factory()->admin()->create();
    $consultant = User::factory()->consultant()->create();

    $accessibleUsers = $dealership->allAccessibleUsers()->get();

    expect($accessibleUsers->contains($admin))->toBeTrue()
        ->and($accessibleUsers->contains($consultant))->toBeFalse();
});

test('all accessible users includes users directly associated with dealership', function (): void {
    $dealership = Dealership::factory()->create();
    $consultant = User::factory()->consultant()->create();
    $unassociatedUser = User::factory()->consultant()->create();

    $consultant->dealerships()->attach($dealership);

    $accessibleUsers = $dealership->allAccessibleUsers()->get();

    expect($accessibleUsers->contains($consultant))->toBeTrue()
        ->and($accessibleUsers->contains($unassociatedUser))->toBeFalse();
});

test('all accessible users includes users associated with dealership stores', function (): void {
    $dealership = Dealership::factory()->create();
    $store = Store::factory()->for($dealership)->create();
    $storeUser = User::factory()->storeLevel()->create();
    $unassociatedUser = User::factory()->storeLevel()->create();

    $storeUser->stores()->attach($store);

    $accessibleUsers = $dealership->allAccessibleUsers()->get();

    expect($accessibleUsers->contains($storeUser))->toBeTrue()
        ->and($accessibleUsers->contains($unassociatedUser))->toBeFalse();
});

test('all accessible users returns unique users from multiple sources', function (): void {
    // Create a non-admin creator to avoid them being included in accessible users
    $creator = User::factory()->consultant()->create();
    $dealership = Dealership::factory()->create(['created_by' => $creator->id]);
    $store = Store::factory()->for($dealership)->create();

    $admin = User::factory()->admin()->create();
    $consultant = User::factory()->consultant()->create();
    $storeUser = User::factory()->storeLevel()->create();

    // Associate consultant with both dealership and store
    $consultant->dealerships()->attach($dealership);
    $consultant->stores()->attach($store);

    // Associate store user with store
    $storeUser->stores()->attach($store);

    $accessibleUsers = $dealership->allAccessibleUsers()->get();

    expect($accessibleUsers)->toHaveCount(3)
        ->and($accessibleUsers->contains($admin))->toBeTrue()
        ->and($accessibleUsers->contains($consultant))->toBeTrue()
        ->and($accessibleUsers->contains($storeUser))->toBeTrue();
});
