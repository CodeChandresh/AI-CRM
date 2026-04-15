<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Queue;
use App\Jobs\ScoreLead;
use App\Jobs\SendLeadEmail;
use App\Jobs\AnalyzeLeadSentiment;
use App\Jobs\PredictLeadChurn;

class LeadManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $lead;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->lead = Lead::factory()->create(['user_id' => $this->user->id]);
    }

    /**
     * @test
     */
    public function a_user_can_create_a_lead()
    {
        $response = $this->actingAs($this->user)->postJson(route('leads.store'), [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
        ]);

        $response->assertStatus(201);
        $this->assertCount(2, Lead::all());
    }

    /**
     * @test
     */
    public function a_user_can_view_a_lead()
    {
        $response = $this->actingAs($this->user)->get(route('leads.show', $this->lead->id));

        $response->assertStatus(200);
        $response->assertJson($this->lead->toArray());
    }

    /**
     * @test
     */
    public function a_user_can_update_a_lead()
    {
        $response = $this->actingAs($this->user)->patchJson(route('leads.update', $this->lead->id), [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
        ]);

        $response->assertStatus(200);
        $this->lead->refresh();
        $this->lead->name === $this->faker->name;
        $this->lead->email === $this->faker->email;
        $this->lead->phone === $this->faker->phoneNumber;
    }

    /**
     * @test
     */
    public function a_user_can_delete_a_lead()
    {
        $response = $this->actingAs($this->user)->delete(route('leads.destroy', $this->lead->id));

        $response->assertStatus(204);
        $this->assertCount(0, Lead::all());
    }

    /**
     * @test
     */
    public function a_lead_is_scored_after_creation()
    {
        Queue::fake();

        $this->lead = Lead::factory()->create(['user_id' => $this->user->id]);

        Queue::assertJobCount(1, ScoreLead::class);
    }

    /**
     * @test
     */
    public function a_lead_email_is_sent_after_creation()
    {
        Queue::fake();

        $this->lead = Lead::factory()->create(['user_id' => $this->user->id]);

        Queue::assertJobCount(1, SendLeadEmail::class);
    }

    /**
     * @test
     */
    public function a_lead_sentiment_is_analyzed_after_creation()
    {
        Queue::fake();

        $this->lead = Lead::factory()->create(['user_id' => $this->user->id]);

        Queue::assertJobCount(1, AnalyzeLeadSentiment::class);
    }

    /**
     * @test
     */
    public function a_lead_churn_is_predicted_after_creation()
    {
        Queue::fake();

        $this->lead = Lead::factory()->create(['user_id' => $this->user->id]);

        Queue::assertJobCount(1, PredictLeadChurn::class);
    }

    /**
     * @test
     */
    public function only_authorized_users_can_manage_leads()
    {
        $unauthorizedUser = User::factory()->create();

        $response = $this->actingAs($unauthorizedUser)->get(route('leads.index'));

        $response->assertStatus(403);
    }
}