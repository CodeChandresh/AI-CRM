<?php

namespace Tests\Unit\Services;

use App\Models\Lead;
use App\Services\LeadScoringService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class LeadScoringServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_scores_leads()
    {
        // Arrange
        $lead = Lead::factory()->create();
        $leadScoringService = new LeadScoringService();

        // Act
        $score = $leadScoringService->score($lead);

        // Assert
        $this->assertNotNull($score);
        $this->assertGreaterThan(0, $score);
    }

    /**
     * @test
     */
    public function it_scores_leads_with_sentiment_analysis()
    {
        // Arrange
        $lead = Lead::factory()->create();
        $lead->sentiment = 'positive';
        $lead->save();
        $leadScoringService = new LeadScoringService();

        // Act
        $score = $leadScoringService->score($lead);

        // Assert
        $this->assertNotNull($score);
        $this->assertGreaterThan(0, $score);
    }

    /**
     * @test
     */
    public function it_scores_leads_with_churn_prediction()
    {
        // Arrange
        $lead = Lead::factory()->create();
        $lead->churn_probability = 0.5;
        $lead->save();
        $leadScoringService = new LeadScoringService();

        // Act
        $score = $leadScoringService->score($lead);

        // Assert
        $this->assertNotNull($score);
        $this->assertGreaterThan(0, $score);
    }

    /**
     * @test
     */
    public function it_scores_leads_with_ai_integration()
    {
        // Arrange
        $lead = Lead::factory()->create();
        $leadScoringService = new LeadScoringService();

        // Act
        $score = $leadScoringService->score($lead);

        // Assert
        $this->assertNotNull($score);
        $this->assertGreaterThan(0, $score);
    }
}