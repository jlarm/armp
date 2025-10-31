<?php

declare(strict_types=1);

use App\Livewire\Invitation\Accept;
use App\Models\Invitation;
use App\Models\User;

describe('Submission and Creation', function (): void {
    test('creates a new user', function (): void {
        $consultant = User::factory()->consultant()->create();
        $invitation = Invitation::factory()->create(['invited_by' => $consultant->id]);

        $response = $this->get(route('invitation.accept', $invitation->token));
        $response->assertOk();

        Livewire::test(Accept::class, ['invitation' => $invitation])
            ->set('form.name', 'John Doe')
            ->set('form.password', 'password123')
            ->set('form.password_confirmation', 'password123')
            ->call('accept')
            ->assertHasNoErrors();

        expect(User::query()->where('email', $invitation->email)->exists())->toBeTrue()
            ->and(Invitation::query()->count())->toBe(0);
    });

    test('user gets logged in', function (): void {
        $consultant = User::factory()->consultant()->create();
        $invitation = Invitation::factory()->create(['invited_by' => $consultant->id]);

        $response = $this->get(route('invitation.accept', $invitation->token));
        $response->assertOk();

        Livewire::test(Accept::class, ['invitation' => $invitation])
            ->set('form.name', 'John Doe')
            ->set('form.password', 'password123')
            ->set('form.password_confirmation', 'password123')
            ->call('accept')
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
        expect(auth()->user()->email)->toBe($invitation->email);
    });

    test('user email is verified on acceptance', function (): void {
        $consultant = User::factory()->consultant()->create();
        $invitation = Invitation::factory()->create(['invited_by' => $consultant->id]);

        Livewire::test(Accept::class, ['invitation' => $invitation])
            ->set('form.name', 'John Doe')
            ->set('form.password', 'password123')
            ->set('form.password_confirmation', 'password123')
            ->call('accept')
            ->assertHasNoErrors();

        $user = User::query()->where('email', $invitation->email)->first();

        expect($user->hasVerifiedEmail())->toBeTrue()
            ->and($user->email_verified_at)->not->toBeNull();
    });
});

describe('Validation', function (): void {
    test('accept invitation url is valid', function (): void {
        $admin = User::factory()->admin()->create();
        $invitation = Invitation::factory()->create(['invited_by' => $admin->id]);

        $this->get(route('invitation.accept', $invitation->token))
            ->assertOk()
            ->assertSee($invitation->email);
    });

    test('invalid token is invalid', function (): void {
        $token = Invitation::generateToken();

        $this->get(route('invitation.accept', $token))
            ->assertRedirect(route('login'));
    });

    test('registered user already exists', function (): void {
        $user = User::factory()->consultant()->create();
        $invitation = Invitation::factory()->create(['email' => $user->email, 'invited_by' => $user->id]);

        $this->get(route('invitation.accept', $invitation->token))
            ->assertRedirect(route('login'));

        expect($invitation->exists())->toBeTrue();
    });

    test('name is required', function (): void {
        $consultant = User::factory()->consultant()->create();
        $invitation = Invitation::factory()->create(['invited_by' => $consultant->id]);

        Livewire::test(Accept::class, ['invitation' => $invitation])
            ->set('form.name', '')
            ->set('form.password', 'password123')
            ->set('form.password_confirmation', 'password123')
            ->call('accept')
            ->assertHasErrors();
    });

    test('password is required', function (): void {
        $consultant = User::factory()->consultant()->create();
        $invitation = Invitation::factory()->create(['invited_by' => $consultant->id]);

        Livewire::test(Accept::class, ['invitation' => $invitation])
            ->set('form.name', 'John Doe')
            ->set('form.password', '')
            ->set('form.password_confirmation', '')
            ->call('accept')
            ->assertHasErrors();
    });

    test('password must be at least 8 characters', function (): void {
        $consultant = User::factory()->consultant()->create();
        $invitation = Invitation::factory()->create(['invited_by' => $consultant->id]);

        Livewire::test(Accept::class, ['invitation' => $invitation])
            ->set('form.name', 'John Doe')
            ->set('form.password', 'pass')
            ->set('form.password_confirmation', 'pass')
            ->call('accept')
            ->assertHasErrors();
    });

    test('confirmation password required', function (): void {
        $consultant = User::factory()->consultant()->create();
        $invitation = Invitation::factory()->create(['invited_by' => $consultant->id]);

        Livewire::test(Accept::class, ['invitation' => $invitation])
            ->set('form.name', 'John Doe')
            ->set('form.password', 'password221')
            ->set('form.password_confirmation', '')
            ->call('accept')
            ->assertHasErrors();
    });

    test('password confirmation must match password', function (): void {
        $consultant = User::factory()->consultant()->create();
        $invitation = Invitation::factory()->create(['invited_by' => $consultant->id]);

        Livewire::test(Accept::class, ['invitation' => $invitation])
            ->set('form.name', 'John Doe')
            ->set('form.password', 'password223')
            ->set('form.password_confirmation', 'passadasds')
            ->call('accept')
            ->assertHasErrors();
    });
});

describe('Role and Store assignment', function (): void {});

describe('Security and Edge cases', function (): void {
    test('handles database errors gracefully', function (): void {
        $consultant = User::factory()->consultant()->create();
        $invitation = Invitation::factory()->create(['invited_by' => $consultant->id]);

        User::factory()->create(['email' => $invitation->email]);

        Livewire::test(Accept::class, ['invitation' => $invitation])
            ->set('form.name', 'John Doe')
            ->set('form.password', 'password123')
            ->set('form.password_confirmation', 'password123')
            ->call('accept')
            ->assertHasNoErrors();

        expect($invitation->fresh())->not->toBeNull();
    });
});

describe('Livewire component', function (): void {});
