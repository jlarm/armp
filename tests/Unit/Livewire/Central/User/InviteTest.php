<?php

declare(strict_types=1);

use App\Livewire\Central\User\Invite;
use App\Models\Invitation;
use App\Models\User;

test('admin can invite consultant', function (): void {
    $user = User::factory()->admin()->create();

    Livewire::actingAs($user)
        ->test(Invite::class)
        ->set('form.email', 'jdoe@email.com')
        ->call('sendInvite');

    expect(Invitation::query()->count())->toBe(1);
});

test('consultant cannot invite consultant', function (): void {
    $user = User::factory()->consultant()->create();

    Livewire::actingAs($user)
        ->test(Invite::class)
        ->set('form.email', 'jdoe@email.com')
        ->call('sendInvite');

    expect(Invitation::query()->count())->toBe(0);
});

test('email field cannot be blank', function (): void {
    $user = User::factory()->admin()->create();

    Livewire::actingAs($user)
        ->test(Invite::class)
        ->set('form.email', '')
        ->call('sendInvite')
        ->assertHasErrors(['form.email']);
});

test('email field must be an email address', function (): void {
    $user = User::factory()->admin()->create();

    Livewire::actingAs($user)
        ->test(Invite::class)
        ->set('form.email', 'ladjkalkjf')
        ->call('sendInvite')
        ->assertHasErrors(['form.email']);
});

test('email field must be unique to users', function (): void {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->consultant()->create(['email' => 'jdoe@email.com']);

    Livewire::actingAs($admin)
        ->test(Invite::class)
        ->set('form.email', $user->email)
        ->call('sendInvite')
        ->assertHasErrors(['form.email']);
});

test('email field must be unique to invites', function (): void {
    $admin = User::factory()->admin()->create();
    $userInvite = Invitation::factory()->create(['email' => 'jdoe@email.com']);

    Livewire::actingAs($admin)
        ->test(Invite::class)
        ->set('form.email', $userInvite->email)
        ->call('sendInvite')
        ->assertHasErrors(['form.email']);
});
