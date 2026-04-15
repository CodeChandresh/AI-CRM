<?php

namespace App\Providers;

use Illuminate\Support\Facades\Horizon;
use Illuminate\Support\Facades\Reverb;
use Illuminate\Support\Facades\Inertia;
use Illuminate\Support\ServiceProvider;
use OpenAI\Client as OpenAIClient;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register AI service bindings
        $this->app->singleton(OpenAIClient::class, function () {
            return new OpenAIClient([
                'api_key' => config('services.openai.key'),
            ]);
        });

        // Bind interfaces to implementations
        $this->app->bind(
            \App\Contracts\LeadScoringInterface::class,
            \App\Services\LeadScoringService::class
        );

        $this->app->bind(
            \App\Contracts\EmailDraftingInterface::class,
            \App\Services\EmailDraftingService::class
        );

        // Repository bindings
        $this->app->bind(
            \App\Repositories\CustomerRepositoryInterface::class,
            \App\Repositories\CustomerRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configure Eloquent
        Model::unguarded(fn () => Model::preventLazyLoading());

        // Inertia setup with Ziggy
        Inertia::share([
            'auth' => function () {
                return [
                    'user' => auth()->user(),
                    'permissions' => auth()->user()?->getAllPermissions()?->pluck('name'),
                ];
            },
            'ziggy' => function () {
                return Ziggy::routes(request());
            },
            'flash' => function () {
                return [
                    'success' => session('success'),
                    'error' => session('error'),
                ];
            },
        ]);

        // Horizon configuration
        Horizon::auth(function ($request) {
            return auth()->user()?->hasRole('admin');
        });

        Horizon::routeDashboard('/admin/horizon');

        // Reverb real-time configuration
        Reverb::serve();

        // Sanctum guard configuration
        Sanctum::ignoreMigrations();

        // Default queue settings
        $this->app['queue']->setDefaultConnection('redis');
    }
}