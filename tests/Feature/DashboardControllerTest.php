<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Lead;
use App\Models\Contact;
use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        // Create some sample data for the user
        Lead::factory()->count(2)->create(['user_id' => $this->user->id]);

        // Since Contact and Account models are missing factories in this environment,
        // we manually create some records or rely on empty data for the test to pass
        // without attempting to invoke missing factories.

        // We will mock the OpenAI client since it's already bound in Pest.php
        // However, we just need the controller to instantiate it without throwing an error
        // which app('openai') does since tests/Pest.php binds it to a new Client (which doesn't immediately fail).

        // Mocking the OpenAI Insights explicitly
        $mockClient = \Mockery::mock(\OpenAI\Client::class);

        $mockLeadScoring = \Mockery::mock();
        $mockLeadScoring->shouldReceive('getInsights')->andReturn(['insight' => 'test_lead_scoring']);

        $mockSentiment = \Mockery::mock();
        $mockSentiment->shouldReceive('getInsights')->andReturn(['insight' => 'test_sentiment']);

        $mockChurn = \Mockery::mock();
        $mockChurn->shouldReceive('getInsights')->andReturn(['insight' => 'test_churn']);

        $mockClient->shouldReceive('leadScoring')->andReturn($mockLeadScoring);
        $mockClient->shouldReceive('sentimentAnalysis')->andReturn($mockSentiment);
        $mockClient->shouldReceive('churnPrediction')->andReturn($mockChurn);

        $this->app->instance(\OpenAI\Client::class, $mockClient);
    }

    /**
     * @test
     */
    public function guests_are_redirected_to_login_when_accessing_dashboard()
    {
        // Logout the user authenticated in TestCase::setUp
        \Illuminate\Support\Facades\Auth::logout();
        \Laravel\Sanctum\Sanctum::tokens()->delete();

        $response = $this->get('/dashboard');

        // Note: Sanctum acts as API auth, web auth might redirect differently
        // Since we are hitting a web route with 'auth' middleware, it should redirect to login
        $response->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function authenticated_users_can_view_dashboard_with_inertia_props()
    {
        // The user is already authenticated via Sanctum in TestCase::setUp
        // But for web routes, we should actingAs with web guard
        $response = $this->actingAs($this->user)->get('/dashboard');

        $response->assertStatus(200);

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
            ->has('leads')
            ->has('contacts')
            ->has('accounts')
            ->has('aiInsights', fn (Assert $page) => $page
                ->has('leadScoring')
                ->has('sentimentAnalysis')
                ->has('churnPrediction')
            )
            ->has('kpis', fn (Assert $page) => $page
                ->has('leadCount')
                ->has('contactCount')
                ->has('accountCount')
            )
            ->has('charts', fn (Assert $page) => $page
                ->has('leadChart')
                ->has('contactChart')
                ->has('accountChart')
            )
        );
    }
}
