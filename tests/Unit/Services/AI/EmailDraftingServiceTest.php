<?php

namespace Tests\Unit\Services\AI;

use App\Models\Lead;
use App\Models\Template;
use App\Models\User;
use App\Services\AI\EmailDraftingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Mockery;
use Mockery\MockInterface;
use OpenAI\Testing\ClientFake;
use OpenAI\Responses\Completions\CreateResponse;
use Predis\Connection\ConnectionException;
use Tests\TestCase;

class EmailDraftingServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var \Illuminate\Redis\RedisManager|MockInterface
     */
    private $redis;

    protected function setUp(): void
    {
        parent::setUp();

        $this->redis = Mockery::mock(\Illuminate\Redis\RedisManager::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * @test
     */
    public function it_returns_cached_email_if_exists()
    {
        $lead = Lead::factory()->create();
        $template = Template::factory()->create();
        $user = User::factory()->create();

        $emailKey = "email:{$lead->id}:{$template->id}";
        $cachedEmail = "This is a cached email.";

        $this->redis->shouldReceive('exists')
            ->once()
            ->with($emailKey)
            ->andReturn(true);

        $this->redis->shouldReceive('get')
            ->once()
            ->with($emailKey)
            ->andReturn($cachedEmail);

        $openAiClient = new ClientFake([]);

        $emailDraftingService = new EmailDraftingService($openAiClient, $this->redis);
        $result = $emailDraftingService->generateEmail($lead, $template, $user);

        $this->assertEquals($cachedEmail, $result);
    }

    /**
     * @test
     */
    public function it_generates_and_caches_email_if_not_exists()
    {
        $lead = Lead::factory()->create();
        $template = Template::factory()->create();
        $user = User::factory()->create();

        $emailKey = "email:{$lead->id}:{$template->id}";
        $generatedEmail = "This is a newly generated email.";

        $this->redis->shouldReceive('exists')
            ->once()
            ->with($emailKey)
            ->andReturn(false);

        $this->redis->shouldReceive('set')
            ->once()
            ->with($emailKey, $generatedEmail);

        $this->redis->shouldReceive('expire')
            ->once()
            ->with($emailKey, 3600);

        $openAiClient = new ClientFake([
            CreateResponse::fake([
                'choices' => [
                    [
                        'text' => $generatedEmail,
                    ],
                ],
            ]),
        ]);

        $emailDraftingService = new EmailDraftingService($openAiClient, $this->redis);
        $result = $emailDraftingService->generateEmail($lead, $template, $user);

        $this->assertEquals($generatedEmail, $result);
    }

    /**
     * @test
     */
    public function it_handles_redis_connection_error_during_caching()
    {
        $lead = Lead::factory()->create();
        $template = Template::factory()->create();
        $user = User::factory()->create();

        $emailKey = "email:{$lead->id}:{$template->id}";
        $generatedEmail = "This is a newly generated email.";

        $this->redis->shouldReceive('exists')
            ->once()
            ->with($emailKey)
            ->andReturn(false);

        $this->redis->shouldReceive('set')
            ->once()
            ->andThrow(new ConnectionException(Mockery::mock(\Predis\Connection\ConnectionInterface::class), 'Connection failed'));

        Log::shouldReceive('error')
            ->once()
            ->with('Redis connection error: Connection failed');

        $openAiClient = new ClientFake([
            CreateResponse::fake([
                'choices' => [
                    [
                        'text' => $generatedEmail,
                    ],
                ],
            ]),
        ]);

        $emailDraftingService = new EmailDraftingService($openAiClient, $this->redis);
        $result = $emailDraftingService->generateEmail($lead, $template, $user);

        $this->assertEquals('', $result);
    }

    /**
     * @test
     */
    public function it_handles_openai_api_error()
    {
        $lead = Lead::factory()->create();
        $template = Template::factory()->create();
        $user = User::factory()->create();

        $emailKey = "email:{$lead->id}:{$template->id}";

        $this->redis->shouldReceive('exists')
            ->once()
            ->with($emailKey)
            ->andReturn(false);

        $openAiClient = new ClientFake([
            new \Exception('OpenAI API rate limit exceeded')
        ]);

        Log::shouldReceive('error')
            ->once()
            ->with('OpenAI API error: OpenAI API rate limit exceeded');

        $emailDraftingService = new EmailDraftingService($openAiClient, $this->redis);
        $result = $emailDraftingService->generateEmail($lead, $template, $user);

        $this->assertEquals('', $result);
    }
}
