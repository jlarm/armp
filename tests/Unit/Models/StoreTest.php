<?php

declare(strict_types=1);

use App\Models\Store;

test('to array', function (): void {
    $store = Store::factory()->create()->refresh();

    expect(array_keys($store->toArray()))
        ->toBe([
            'id',
            'uuid',
            'dealership_id',
            'name',
            'slug',
            'address',
            'city',
            'state',
            'zip',
            'phone',
            'created_at',
            'updated_at',
        ]);
});
