<?php

namespace Tests\Unit\Services;

use App\Models\Customer;
use App\Models\SupportTicket;
use App\Models\UsagePattern;
use App\Services\AI\ChurnPredictionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use OpenAI\Client;
use Tests\TestCase;

class ChurnPredictionServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_calculates_churn_risk_score()
    {
        // Arrange
        $customer = Customer::factory()->create();

        UsagePattern::create([
            'customer_id' => $customer->id,
            'value' => 15,
        ]);
        UsagePattern::create([
            'customer_id' => $customer->id,
            'value' => 20,
        ]);

        SupportTicket::create([
            'customer_id' => $customer->id,
            'description' => '10', // Value added to score
        ]);
        SupportTicket::create([
            'customer_id' => $customer->id,
            'description' => '5', // Value added to score
        ]);

        $openAiClientMock = Mockery::mock(Client::class);
        $churnPredictionService = new ChurnPredictionService($openAiClientMock);

        // Act
        $score = $churnPredictionService->calculateChurnRiskScore($customer);

        // Assert
        // Expected score: 15 + 20 + 10 + 5 = 50
        $this->assertEquals(50, $score);
    }
}
