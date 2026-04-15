<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Jobs\ScoreLeadJob;
use App\Jobs\AnalyzeSentimentJob;
use App\Jobs\PredictChurnJob;
use App\Jobs\SendEmailJob;

class AIFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected $lead;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->lead = Lead::factory()->create();
    }

    /**
     * @test
     */
    public function it_can_score_a_lead_using_ai()
    {
        Queue::fake();

        $response = $this->actingAs($this->user)->postJson('/api/ai/score', [
            'lead_id' => $this->lead->id,
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Lead scored successfully']);
        Queue::assertDispatched(ScoreLeadJob::class);
    }

    /**
     * @test
     */
    public function it_can_analyze_lead_sentiment()
    {
        Queue::fake();

        $response = $this->actingAs($this->user)->postJson('/api/ai/analyze', [
            'lead_id' => $this->lead->id,
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Sentiment analyzed successfully']);
        Queue::assertDispatched(AnalyzeSentimentJob::class);
    }

    /**
     * @test
     */
    public function it_can_predict_lead_churn()
    {
        Queue::fake();

        $response = $this->actingAs($this->user)->postJson('/api/ai/predict', [
            'lead_id' => $this->lead->id,
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Churn predicted successfully']);
        Queue::assertDispatched(PredictChurnJob::class);
    }

    /**
     * @test
     */
    public function it_can_draft_an_email_using_ai()
    {
        Queue::fake();
        Storage::fake('public');

        // Create a mock template file
        Storage::disk('public')->put('templates/1.txt', 'Hello, this is a test template.');

        $response = $this->actingAs($this->user)->postJson('/api/ai/draft', [
            'lead_id' => $this->lead->id,
            'template_id' => 1,
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Email drafted successfully']);
    }
}
