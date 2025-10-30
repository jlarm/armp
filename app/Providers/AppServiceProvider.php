<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Dealership;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->bootModelsDefaults();

        Gate::define('access-dealership', fn (User $user, Dealership $dealership): bool => $user->hasAccessToDealership($dealership));
        Gate::define('access-users', fn (User $user): bool => $user->isAdmin());

        Gate::define('access-store', fn (User $user, Store $store): bool => $user->hasAccessToStore($store));
    }

    private function bootModelsDefaults(): void
    {
        Model::unguard();
    }
}
