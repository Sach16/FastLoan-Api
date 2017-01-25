<?php

namespace Whatsloan\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Auth\Access\Gate;

class AccessControlServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Gate $gate
     */
    public function boot(Gate $gate)
    {
        $gate->define('viewAdminPanel', function ($user) {
            return in_array($user->role,
                ['SUPER_ADMIN', 'DSA_OWNER']);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
