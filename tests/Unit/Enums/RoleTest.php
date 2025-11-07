<?php

declare(strict_types=1);

use App\Enums\Role;

test('label returns correct label for each role', function (Role $role, string $expectedLabel): void {
    expect($role->label())->toBe($expectedLabel);
})->with([
    [Role::ADMIN, 'Admin'],
    [Role::CONSULTANT, 'Consultant'],
    [Role::OWNER, 'Owner'],
    [Role::CFO, 'CFO'],
    [Role::GM, 'GM'],
    [Role::GSM, 'GSM'],
    [Role::MANAGER, 'Manager'],
    [Role::EMPLOYEE, 'Employee'],
    [Role::PORTER, 'Porter'],
]);

test('color returns correct color for each role', function (Role $role, string $expectedColor): void {
    expect($role->color())->toBe($expectedColor);
})->with([
    [Role::ADMIN, 'lime'],
    [Role::CONSULTANT, 'grey'],
    [Role::OWNER, 'blue'],
    [Role::CFO, 'green'],
    [Role::GM, 'yellow'],
    [Role::GSM, 'red'],
    [Role::MANAGER, 'cyan'],
    [Role::EMPLOYEE, 'orange'],
    [Role::PORTER, 'purple'],
]);
