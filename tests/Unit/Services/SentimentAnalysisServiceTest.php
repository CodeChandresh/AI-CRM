<?php

namespace Tests\Unit\Services;

use App\Services\SentimentAnalysisService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Mockery;
use Tests\TestCase;

class SentimentAnalysisServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var SentimentAnalysisService
     */
    protected $sentimentAnalysisService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sentimentAnalysisService = Mockery::mock(SentimentAnalysisService::class);
    }

    /**
     * @test
     */
    public function it_can_analyze_sentiment()
    {
        // Arrange
        $text = 'This is a great product!';
        $expectedResult = ['score' => 0.9, 'label' => 'positive'];

        // Act
        $this->sentimentAnalysisService->shouldReceive('analyze')->with($text)->andReturn($expectedResult);

        // Assert
        $result = $this->sentimentAnalysisService->analyze($text);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @test
     */
    public function it_can_handle_invalid_input()
    {
        // Arrange
        $text = null;

        // Act
        $result = $this->sentimentAnalysisService->analyze($text);

        // Assert
        $this->assertNull($result);
    }

    /**
     * @test
     */
    public function it_can_handle_api_errors()
    {
        // Arrange
        $text = 'This is a great product!';
        $this->sentimentAnalysisService->shouldReceive('analyze')->with($text)->andThrow(new \Exception('API error'));

        // Act
        $result = $this->sentimentAnalysisService->analyze($text);

        // Assert
        $this->assertNull($result);
    }
}