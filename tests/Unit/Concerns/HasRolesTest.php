<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Models\User;

test('isAdmin returns true for admin users', function (): void {
    $admin = User::factory()->admin()->create();
    $consultant = User::factory()->consultant()->create();

    expect($admin->isAdmin())->toBeTrue()
        ->and($consultant->isAdmin())->toBeFalse();
});

test('isConsultant returns true for consultant users', function (): void {
    $consultant = User::factory()->consultant()->create();
    $admin = User::factory()->admin()->create();

    expect($consultant->isConsultant())->toBeTrue()
        ->and($admin->isConsultant())->toBeFalse();
});

test('isOwner returns true for owner users', function (): void {
    $owner = User::factory()->create(['role' => Role::OWNER]);
    $admin = User::factory()->admin()->create();

    expect($owner->isOwner())->toBeTrue()
        ->and($admin->isOwner())->toBeFalse();
});

test('isCfo returns true for cfo users', function (): void {
    $cfo = User::factory()->create(['role' => Role::CFO]);
    $owner = User::factory()->create(['role' => Role::OWNER]);

    expect($cfo->isCfo())->toBeTrue()
        ->and($owner->isCfo())->toBeFalse();
});

test('isGm returns true for gm users', function (): void {
    $gm = User::factory()->create(['role' => Role::GM]);
    $cfo = User::factory()->create(['role' => Role::CFO]);

    expect($gm->isGm())->toBeTrue()
        ->and($cfo->isGm())->toBeFalse();
});

test('isGsm returns true for gsm users', function (): void {
    $gsm = User::factory()->create(['role' => Role::GSM]);
    $gm = User::factory()->create(['role' => Role::GM]);

    expect($gsm->isGsm())->toBeTrue()
        ->and($gm->isGsm())->toBeFalse();
});

test('isManager returns true for manager users', function (): void {
    $manager = User::factory()->create(['role' => Role::MANAGER]);
    $gsm = User::factory()->create(['role' => Role::GSM]);

    expect($manager->isManager())->toBeTrue()
        ->and($gsm->isManager())->toBeFalse();
});

test('isEmployee returns true for employee users', function (): void {
    $employee = User::factory()->create(['role' => Role::EMPLOYEE]);
    $manager = User::factory()->create(['role' => Role::MANAGER]);

    expect($employee->isEmployee())->toBeTrue()
        ->and($manager->isEmployee())->toBeFalse();
});

test('isPorter returns true for porter users', function (): void {
    $porter = User::factory()->create(['role' => Role::PORTER]);
    $employee = User::factory()->create(['role' => Role::EMPLOYEE]);

    expect($porter->isPorter())->toBeTrue()
        ->and($employee->isPorter())->toBeFalse();
});

test('hasRole returns true when user has specific role', function (): void {
    $admin = User::factory()->admin()->create();
    $consultant = User::factory()->consultant()->create();

    expect($admin->hasRole(Role::ADMIN))->toBeTrue()
        ->and($admin->hasRole(Role::CONSULTANT))->toBeFalse()
        ->and($consultant->hasRole(Role::CONSULTANT))->toBeTrue()
        ->and($consultant->hasRole(Role::ADMIN))->toBeFalse();
});

test('hasAnyRole returns true when user has any of the specified roles', function (): void {
    $admin = User::factory()->admin()->create();
    $owner = User::factory()->create(['role' => Role::OWNER]);

    expect($admin->hasAnyRole([Role::ADMIN, Role::CONSULTANT]))->toBeTrue()
        ->and($admin->hasAnyRole([Role::OWNER, Role::CFO]))->toBeFalse()
        ->and($owner->hasAnyRole([Role::OWNER, Role::CFO, Role::GM]))->toBeTrue()
        ->and($owner->hasAnyRole([Role::ADMIN, Role::CONSULTANT]))->toBeFalse();
});

test('hasAnyRole returns false when user has none of the specified roles', function (): void {
    $porter = User::factory()->create(['role' => Role::PORTER]);

    expect($porter->hasAnyRole([Role::ADMIN, Role::CONSULTANT, Role::OWNER]))->toBeFalse();
});

test('hasAnyRole returns false for empty array', function (): void {
    $admin = User::factory()->admin()->create();

    expect($admin->hasAnyRole([]))->toBeFalse();
});

test('canManageDealerships returns true for admins', function (): void {
    $admin = User::factory()->admin()->create();

    expect($admin->canManageDealerships())->toBeTrue();
});

test('canManageDealerships returns true for consultants', function (): void {
    $consultant = User::factory()->consultant()->create();

    expect($consultant->canManageDealerships())->toBeTrue();
});

test('canManageDealerships returns false for store level users', function (): void {
    $owner = User::factory()->create(['role' => Role::OWNER]);
    $cfo = User::factory()->create(['role' => Role::CFO]);
    $gm = User::factory()->create(['role' => Role::GM]);
    $gsm = User::factory()->create(['role' => Role::GSM]);
    $manager = User::factory()->create(['role' => Role::MANAGER]);
    $employee = User::factory()->create(['role' => Role::EMPLOYEE]);
    $porter = User::factory()->create(['role' => Role::PORTER]);

    expect($owner->canManageDealerships())->toBeFalse()
        ->and($cfo->canManageDealerships())->toBeFalse()
        ->and($gm->canManageDealerships())->toBeFalse()
        ->and($gsm->canManageDealerships())->toBeFalse()
        ->and($manager->canManageDealerships())->toBeFalse()
        ->and($employee->canManageDealerships())->toBeFalse()
        ->and($porter->canManageDealerships())->toBeFalse();
});

test('isStoreLevel returns true for owner role', function (): void {
    $owner = User::factory()->create(['role' => Role::OWNER]);

    expect($owner->isStoreLevel())->toBeTrue();
});

test('isStoreLevel returns true for cfo role', function (): void {
    $cfo = User::factory()->create(['role' => Role::CFO]);

    expect($cfo->isStoreLevel())->toBeTrue();
});

test('isStoreLevel returns true for gm role', function (): void {
    $gm = User::factory()->create(['role' => Role::GM]);

    expect($gm->isStoreLevel())->toBeTrue();
});

test('isStoreLevel returns true for gsm role', function (): void {
    $gsm = User::factory()->create(['role' => Role::GSM]);

    expect($gsm->isStoreLevel())->toBeTrue();
});

test('isStoreLevel returns true for manager role', function (): void {
    $manager = User::factory()->create(['role' => Role::MANAGER]);

    expect($manager->isStoreLevel())->toBeTrue();
});

test('isStoreLevel returns true for employee role', function (): void {
    $employee = User::factory()->create(['role' => Role::EMPLOYEE]);

    expect($employee->isStoreLevel())->toBeTrue();
});

test('isStoreLevel returns true for porter role', function (): void {
    $porter = User::factory()->create(['role' => Role::PORTER]);

    expect($porter->isStoreLevel())->toBeTrue();
});

test('isStoreLevel returns false for admin role', function (): void {
    $admin = User::factory()->admin()->create();

    expect($admin->isStoreLevel())->toBeFalse();
});

test('isStoreLevel returns false for consultant role', function (): void {
    $consultant = User::factory()->consultant()->create();

    expect($consultant->isStoreLevel())->toBeFalse();
});

test('all store level roles are correctly identified', function (): void {
    $owner = User::factory()->create(['role' => Role::OWNER]);
    $cfo = User::factory()->create(['role' => Role::CFO]);
    $gm = User::factory()->create(['role' => Role::GM]);
    $gsm = User::factory()->create(['role' => Role::GSM]);
    $manager = User::factory()->create(['role' => Role::MANAGER]);
    $employee = User::factory()->create(['role' => Role::EMPLOYEE]);
    $porter = User::factory()->create(['role' => Role::PORTER]);

    expect($owner->isStoreLevel())->toBeTrue()
        ->and($cfo->isStoreLevel())->toBeTrue()
        ->and($gm->isStoreLevel())->toBeTrue()
        ->and($gsm->isStoreLevel())->toBeTrue()
        ->and($manager->isStoreLevel())->toBeTrue()
        ->and($employee->isStoreLevel())->toBeTrue()
        ->and($porter->isStoreLevel())->toBeTrue();
});
