<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest', function (): void {
    $response = $this->get(route('user.index'));

    $response->assertRedirect(route('login'));
});

test('consultant cannot access central users', function (): void {
    $user = User::factory()->consultant()->create();

    $response = $this->actingAs($user)
        ->get(route('user.index'));

    $response->assertStatus(403);
});

test('admin can access central users', function (): void {
    $user = User::factory()->admin()->create();

    $response = $this->actingAs($user)
        ->get(route('user.index'));

    $response
        ->assertOk()
        ->assertSee('wire:snapshot');
});

test('can see list of users', function (): void {
    $user = User::factory()->admin()->create();

    $response = $this->actingAs($user)
        ->get(route('user.index'));

    $response->assertOk()
        ->assertSee('wire:snapshot')
        ->assertSee($user->name)
        ->assertSee($user->email)
        ->assertSee($user->role->label());
});
