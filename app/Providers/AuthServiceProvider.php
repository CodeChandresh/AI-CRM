<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Define gates for role-based permissions
        Gate::define('view-users', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('create-users', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('edit-users', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('delete-users', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('view-roles', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('create-roles', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('edit-roles', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('delete-roles', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('view-permissions', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('create-permissions', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('edit-permissions', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('delete-permissions', function (User $user) {
            return $user->hasRole('admin');
        });
    }

    /**
     * Register the application's gate keepers.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}