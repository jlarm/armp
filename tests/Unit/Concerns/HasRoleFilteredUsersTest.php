<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Models\Dealership;
use App\Models\User;

test('usersByRole filters users by owner role', function (): void {
    $dealership = Dealership::factory()->create();
    $owner = User::factory()->create(['role' => Role::OWNER]);
    $consultant = User::factory()->consultant()->create();

    $owner->dealerships()->attach($dealership);
    $consultant->dealerships()->attach($dealership);

    expect($dealership->usersByRole(Role::OWNER)->get())->toHaveCount(1)
        ->and($dealership->usersByRole(Role::OWNER)->first()->id)->toBe($owner->id);
});

test('owners returns only owner role users', function (): void {
    $dealership = Dealership::factory()->create();
    $owner = User::factory()->create(['role' => Role::OWNER]);
    $consultant = User::factory()->consultant()->create();

    $owner->dealerships()->attach($dealership);
    $consultant->dealerships()->attach($dealership);

    expect($dealership->owners()->get())->toHaveCount(1)
        ->and($dealership->owners()->first()->id)->toBe($owner->id);
});

test('cfos returns only cfo role users', function (): void {
    $dealership = Dealership::factory()->create();
    $cfo = User::factory()->create(['role' => Role::CFO]);
    $manager = User::factory()->create(['role' => Role::MANAGER]);

    $cfo->dealerships()->attach($dealership);
    $manager->dealerships()->attach($dealership);

    expect($dealership->cfos()->get())->toHaveCount(1)
        ->and($dealership->cfos()->first()->id)->toBe($cfo->id);
});

test('gms returns only gm role users', function (): void {
    $dealership = Dealership::factory()->create();
    $gm = User::factory()->create(['role' => Role::GM]);
    $employee = User::factory()->create(['role' => Role::EMPLOYEE]);

    $gm->dealerships()->attach($dealership);
    $employee->dealerships()->attach($dealership);

    expect($dealership->gms()->get())->toHaveCount(1)
        ->and($dealership->gms()->first()->id)->toBe($gm->id);
});

test('gsms returns only gsm role users', function (): void {
    $dealership = Dealership::factory()->create();
    $gsm = User::factory()->create(['role' => Role::GSM]);
    $porter = User::factory()->create(['role' => Role::PORTER]);

    $gsm->dealerships()->attach($dealership);
    $porter->dealerships()->attach($dealership);

    expect($dealership->gsms()->get())->toHaveCount(1)
        ->and($dealership->gsms()->first()->id)->toBe($gsm->id);
});

test('managers returns only manager role users', function (): void {
    $dealership = Dealership::factory()->create();
    $manager = User::factory()->create(['role' => Role::MANAGER]);
    $owner = User::factory()->create(['role' => Role::OWNER]);

    $manager->dealerships()->attach($dealership);
    $owner->dealerships()->attach($dealership);

    expect($dealership->managers()->get())->toHaveCount(1)
        ->and($dealership->managers()->first()->id)->toBe($manager->id);
});

test('employees returns only employee role users', function (): void {
    $dealership = Dealership::factory()->create();
    $employee = User::factory()->create(['role' => Role::EMPLOYEE]);
    $cfo = User::factory()->create(['role' => Role::CFO]);

    $employee->dealerships()->attach($dealership);
    $cfo->dealerships()->attach($dealership);

    expect($dealership->employees()->get())->toHaveCount(1)
        ->and($dealership->employees()->first()->id)->toBe($employee->id);
});

test('porters returns only porter role users', function (): void {
    $dealership = Dealership::factory()->create();
    $porter = User::factory()->create(['role' => Role::PORTER]);
    $gm = User::factory()->create(['role' => Role::GM]);

    $porter->dealerships()->attach($dealership);
    $gm->dealerships()->attach($dealership);

    expect($dealership->porters()->get())->toHaveCount(1)
        ->and($dealership->porters()->first()->id)->toBe($porter->id);
});

test('consultants returns only consultant role users', function (): void {
    $dealership = Dealership::factory()->create();
    $consultant = User::factory()->consultant()->create();
    $admin = User::factory()->admin()->create();

    $consultant->dealerships()->attach($dealership);
    $admin->dealerships()->attach($dealership);

    expect($dealership->consultants()->get())->toHaveCount(1)
        ->and($dealership->consultants()->first()->id)->toBe($consultant->id);
});

test('admins returns only admin role users', function (): void {
    $dealership = Dealership::factory()->create();
    $admin = User::factory()->admin()->create();
    $consultant = User::factory()->consultant()->create();

    $admin->dealerships()->attach($dealership);
    $consultant->dealerships()->attach($dealership);

    expect($dealership->admins()->get())->toHaveCount(1)
        ->and($dealership->admins()->first()->id)->toBe($admin->id);
});

test('role filtered methods return empty collection when no users match', function (): void {
    $dealership = Dealership::factory()->create();
    $consultant = User::factory()->consultant()->create();

    $consultant->dealerships()->attach($dealership);

    expect($dealership->owners()->get())->toHaveCount(0)
        ->and($dealership->cfos()->get())->toHaveCount(0)
        ->and($dealership->gms()->get())->toHaveCount(0)
        ->and($dealership->gsms()->get())->toHaveCount(0)
        ->and($dealership->managers()->get())->toHaveCount(0)
        ->and($dealership->employees()->get())->toHaveCount(0)
        ->and($dealership->porters()->get())->toHaveCount(0)
        ->and($dealership->admins()->get())->toHaveCount(0);
});

test('role filtered methods can return multiple users of same role', function (): void {
    $dealership = Dealership::factory()->create();
    $owner1 = User::factory()->create(['role' => Role::OWNER]);
    $owner2 = User::factory()->create(['role' => Role::OWNER]);
    $consultant = User::factory()->consultant()->create();

    $owner1->dealerships()->attach($dealership);
    $owner2->dealerships()->attach($dealership);
    $consultant->dealerships()->attach($dealership);

    expect($dealership->owners()->get())->toHaveCount(2)
        ->and($dealership->owners()->get()->pluck('id')->toArray())
        ->toContain($owner1->id, $owner2->id);
});
