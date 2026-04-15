<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Horizon\Horizon;

class HorizonServiceProvider extends ServiceProvider
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

        // Horizon dashboard authorization
        Gate::define('view-horizon', function ($user) {
            return $user->hasPermissionTo('view-horizon');
        });

        Gate::define('manage-horizon', function ($user) {
            return $user->hasPermissionTo('manage-horizon');
        });

        // Define permissions for Horizon
        $this->permissions([
            'view-horizon' => 'View Horizon dashboard',
            'manage-horizon' => 'Manage Horizon dashboard',
        ]);

        Horizon::auth(function ($request) {
            return Gate::allows('view-horizon');
        });
    }

    /**
     * Register the application's permissions.
     *
     * @return void
     */
    protected function permissions(array $permissions)
    {
        foreach ($permissions as $permission => $name) {
            Gate::define($permission, function ($user) use ($permission) {
                return $user->hasPermissionTo($permission);
            });

            // Add the permission to the user model
            // $this->app['config']['permissions'][$permission] = $name;
        }
    }
}