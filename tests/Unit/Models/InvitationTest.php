<?php

declare(strict_types=1);

use App\Models\Invitation;
use App\Models\Store;
use App\Models\User;

test('to array', function (): void {
    $invitation = Invitation::factory()->create()->refresh();

    expect(array_keys($invitation->toArray()))
        ->toBe([
            'id',
            'email',
            'token',
            'role',
            'invited_by',
            'store_id',
            'created_at',
            'updated_at',
        ]);
});

test('it can generate a token', function (): void {
    $invitation = Invitation::factory()->create();
    expect($invitation->token)->toBeString();
});

test('user relationship returns belongs to', function (): void {
    $user = User::factory()->create();
    $invitation = Invitation::factory()->create(['invited_by' => $user->id]);

    expect($invitation->invitedBy->id)->toBe($user->id);
});

test('store relationship returns belongs to', function (): void {
    $user = User::factory()->create();
    $store = Store::factory()->create();
    $invitation = Invitation::factory()->create([
        'invited_by' => $user->id,
        'store_id' => $store->id,
    ]);

    expect($invitation->store->id)->toBe($store->id);
});
