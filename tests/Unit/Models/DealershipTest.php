<?php

declare(strict_types=1);

use App\Models\Dealership;

test('to array', function (): void {
    $dealership = Dealership::factory()->create()->refresh();

    expect(array_keys($dealership->toArray()))
        ->toBe([
            'id',
            'uuid',
            'name',
            'created_at',
            'updated_at',
        ]);
});
