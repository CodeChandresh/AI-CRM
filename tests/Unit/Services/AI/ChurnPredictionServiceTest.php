<?php

namespace Tests\Unit\Services\AI;

use App\Models\Customer;
use App\Models\SupportTicket;
use App\Models\UsagePattern;
use App\Services\AI\ChurnPredictionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use OpenAI\Client;
use Tests\TestCase;
use Mockery;

class ChurnPredictionServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_calculates_churn_risk_score_correctly()
    {
        // Arrange
        $customer = new Customer();
        $customer->save();

        UsagePattern::create([
            'customer_id' => $customer->id,
            'value' => 15,
        ]);
        UsagePattern::create([
            'customer_id' => $customer->id,
            'value' => 25,
        ]);

        SupportTicket::create([
            'customer_id' => $customer->id,
            'description' => '10', // description is added to score
        ]);
        SupportTicket::create([
            'customer_id' => $customer->id,
            'description' => '20', // description is added to score
        ]);

        $mockOpenAi = Mockery::mock(Client::class);
        $service = new ChurnPredictionService($mockOpenAi);

        // Act
        $score = $service->calculateChurnRiskScore($customer);

        // Assert
        // 15 + 25 + 10 + 20 = 70
        $this->assertEquals(70, $score);
    }

    /**
     * @test
     */
    public function it_returns_zero_when_no_data_is_present()
    {
        // Arrange
        $customer = new Customer();
        $customer->save();

        $mockOpenAi = Mockery::mock(Client::class);
        $service = new ChurnPredictionService($mockOpenAi);

        // Act
        $score = $service->calculateChurnRiskScore($customer);

        // Assert
        $this->assertEquals(0, $score);
    }
}
