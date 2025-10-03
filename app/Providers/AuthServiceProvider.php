<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('access-desa-panel', function (User $user) {
            return in_array($user->role, ['desa' || 'superadmin']);
        });
        
        Gate::define('access-kecamatan-panel', function (User $user) {
            return in_array($user->role, ['kecamatan' || 'superadmin']);
        });
    }
}
