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

    /**
     * @test
     */
    public function it_prevents_path_traversal_in_draft_email_template_id()
    {
        Queue::fake();
        Storage::fake('public');

        // We try to pass an array or a string that could bypass standard integer validation
        // depending on how Laravel treats it, but our explicit cast to int should neutralize it.
        $response = $this->actingAs($this->user)->postJson('/api/ai/draft', [
            'lead_id' => $this->lead->id,
            'template_id' => '1/../../../../etc/passwd',
        ]);

        // Validation might fail first, which gives 422. If validation passes (e.g. because of loose rules),
        // we want to ensure it fails safely. It will either fail validation (422) or fail because the file
        // 1.txt is not found, returning 404 (or 200 if 1.txt exists, but it won't traverse).
        // Since we didn't create templates/1.txt in this test, and the path traversal string
        // '1/../../../../etc/passwd' cast to (int) evaluates to 1, we expect a 404 (Lead or template not found).
        $response->assertStatus(422); // Validation requires integer
    }
}
