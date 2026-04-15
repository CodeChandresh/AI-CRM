<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;
use Laravel\Horizon\Horizon;
use Laravel\Reverb\Reverb;
use Inertia\Inertia;
use Tightenco\Ziggy\Ziggy;
use MongoDB\BSON\ObjectId;
use OpenAI\Client;
use Spatie\Permission\PermissionRegistrar;
use Maatwebsite\Excel\Facades\Excel;
use Pusher\Pusher;
use Dayjs\Dayjs;

class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up Sanctum for API testing
        Sanctum::actingAs(
            \App\Models\User::factory()->create(),
            ['*']
        );

        // Set up Horizon for queue testing
        Horizon::test();

        // Set up Reverb for real-time testing
        Reverb::test();

        // Set up Inertia for Vue testing
        Inertia::setResolver(function ($page) {
            return Inertia::render($page);
        });

        // Set up Ziggy for route testing
        Ziggy::setResolver(function ($route) {
            return Ziggy::make($route);
        });

        // Set up MongoDB for database testing
        $this->app->bind('mongodb', function () {
            return new \MongoDB\Client();
        });

        // Set up OpenAI for AI testing
        $this->app->bind('openai', function () {
            return new Client();
        });

        // Set up Spatie Permission for role testing
        PermissionRegistrar::registerPermissions();

        // Set up Excel for file testing
        Excel::fake();

        // Set up Pusher for real-time testing
        Pusher::fake();

        // Set up Dayjs for date testing
        Dayjs::setLocale('en');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Clean up Sanctum tokens
        Sanctum::tokens()->delete();

        // Clean up Horizon queues
        Horizon::clean();

        // Clean up Reverb connections
        Reverb::disconnect();

        // Clean up Inertia sessions
        Inertia::clear();

        // Clean up Ziggy routes
        Ziggy::clear();

        // Clean up MongoDB collections
        $this->app->make('mongodb')->dropDatabase();

        // Clean up OpenAI sessions
        $this->app->make('openai')->clearSession();

        // Clean up Spatie Permission roles
        PermissionRegistrar::clearPermissions();

        // Clean up Excel files
        Excel::fake()->delete();

        // Clean up Pusher connections
        Pusher::fake()->disconnect();

        // Clean up Dayjs locale
        Dayjs::setLocale('en');
    }
}