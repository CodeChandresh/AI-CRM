<?php

namespace Tests\Unit\Services;

use App\Models\Customer;
use App\Models\SupportTicket;
use App\Models\UsagePattern;
use App\Services\AI\ChurnPredictionService;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use OpenAI\Client;
use Tests\TestCase;

class ChurnPredictionServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * @test
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function it_predicts_churn_risk_successfully()
    {
        // Arrange
        $customerId = '12345';
        $customer = new Customer();
        $customer->id = $customerId;

        $usagePatternsCollection = Mockery::mock(Collection::class);
        $usagePatternsCollection->shouldReceive('pluck')->with('value')->andReturn(Mockery::mock(Collection::class, function ($mock) {
            $mock->shouldReceive('toArray')->andReturn(['high_usage', 'frequent_logins']);
        }));

        $usagePatternMock = Mockery::mock('alias:' . UsagePattern::class);
        $usagePatternMock->shouldReceive('where')
            ->with('customer_id', $customerId)
            ->andReturn(Mockery::mock(['get' => $usagePatternsCollection]));

        $supportTicketsCollection = Mockery::mock(Collection::class);
        $supportTicketsCollection->shouldReceive('pluck')->with('description')->andReturn(Mockery::mock(Collection::class, function ($mock) {
            $mock->shouldReceive('toArray')->andReturn(['Cannot access billing']);
        }));

        $supportTicketMock = Mockery::mock('alias:' . SupportTicket::class);
        $supportTicketMock->shouldReceive('where')
            ->with('customer_id', $customerId)
            ->andReturn(Mockery::mock(['get' => $supportTicketsCollection]));

        // Mock OpenAI Response
        $openAiResponse = Mockery::mock();
        $openAiResponse->shouldReceive('getChurnRisk')->andReturn(85.5);

        // Mock OpenAI Client. Use makePartial to allow mocked method missing on official SDK
        $openAiClient = Mockery::mock(Client::class)->makePartial();
        $openAiClient->shouldReceive('predictChurnRisk')
            ->with([
                'usage_patterns' => ['high_usage', 'frequent_logins'],
                'support_tickets' => ['Cannot access billing'],
            ])
            ->andReturn($openAiResponse);

        $service = new ChurnPredictionService($openAiClient);

        // Act
        $risk = $service->predictChurnRisk($customer);

        // Assert
        $this->assertEquals(85.5, $risk);
    }

    /**
     * @test
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function it_predicts_churn_risk_with_no_data()
    {
        // Arrange
        $customerId = '67890';
        $customer = new Customer();
        $customer->id = $customerId;

        $usagePatternsCollection = Mockery::mock(Collection::class);
        $usagePatternsCollection->shouldReceive('pluck')->with('value')->andReturn(Mockery::mock(Collection::class, function ($mock) {
            $mock->shouldReceive('toArray')->andReturn([]);
        }));

        $usagePatternMock = Mockery::mock('alias:' . UsagePattern::class);
        $usagePatternMock->shouldReceive('where')
            ->with('customer_id', $customerId)
            ->andReturn(Mockery::mock(['get' => $usagePatternsCollection]));

        $supportTicketsCollection = Mockery::mock(Collection::class);
        $supportTicketsCollection->shouldReceive('pluck')->with('description')->andReturn(Mockery::mock(Collection::class, function ($mock) {
            $mock->shouldReceive('toArray')->andReturn([]);
        }));

        $supportTicketMock = Mockery::mock('alias:' . SupportTicket::class);
        $supportTicketMock->shouldReceive('where')
            ->with('customer_id', $customerId)
            ->andReturn(Mockery::mock(['get' => $supportTicketsCollection]));

        // Mock OpenAI Response
        $openAiResponse = Mockery::mock();
        $openAiResponse->shouldReceive('getChurnRisk')->andReturn(12.0);

        // Mock OpenAI Client
        $openAiClient = Mockery::mock(Client::class)->makePartial();
        $openAiClient->shouldReceive('predictChurnRisk')
            ->with([
                'usage_patterns' => [],
                'support_tickets' => [],
            ])
            ->andReturn($openAiResponse);

        $service = new ChurnPredictionService($openAiClient);

        // Act
        $risk = $service->predictChurnRisk($customer);

        // Assert
        $this->assertEquals(12.0, $risk);
    }
}
