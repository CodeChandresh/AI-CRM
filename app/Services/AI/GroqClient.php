<?php

namespace App\Services\AI;

use App\Services\AI\GroqClientException;
use App\Services\AI\GroqClientOptions;
use App\Services\AI\GroqClientResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use OpenAIClient\Client;
use OpenAIClient\Exception\OpenAIClientException;
use Predis\Client as RedisClient;

class GroqClient
{
    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var RedisClient
     */
    private $redisClient;

    /**
     * @var Client
     */
    private $openAiClient;

    /**
     * @var GroqClientOptions
     */
    private $options;

    /**
     * @param Client $httpClient
     * @param RedisClient $redisClient
     * @param Client $openAiClient
     * @param GroqClientOptions $options
     */
    public function __construct(
        Client $httpClient,
        RedisClient $redisClient,
        Client $openAiClient,
        GroqClientOptions $options
    ) {
        $this->httpClient = $httpClient;
        $this->redisClient = $redisClient;
        $this->openAiClient = $openAiClient;
        $this->options = $options;
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @return GroqClientResponse
     * @throws GroqClientException
     */
    public function query(string $endpoint, array $data): GroqClientResponse
    {
        $cacheKey = $this->getCacheKey($endpoint, $data);

        if ($this->shouldUseCache($cacheKey)) {
            return $this->getFromCache($cacheKey);
        }

        $response = $this->sendRequest($endpoint, $data);

        if ($response->getStatusCode() >= 400) {
            throw new GroqClientException($response->getReasonPhrase(), $response->getStatusCode());
        }

        $this->cacheResponse($cacheKey, $response);

        return $response;
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @return GroqClientResponse
     * @throws GroqClientException
     */
    private function sendRequest(string $endpoint, array $data): GroqClientResponse
    {
        $options = $this->options->getOptions();

        $response = $this->httpClient->post($endpoint, [
            'json' => $data,
            'headers' => [
                'Authorization' => 'Bearer ' . $options->getApiKey(),
                'X-RateLimit-Limit' => $options->getRateLimit(),
                'X-RateLimit-Remaining' => $options->getRateLimitRemaining(),
            ],
        ]);

        return $response;
    }

    /**
     * @param string $cacheKey
     * @return GroqClientResponse
     */
    private function getFromCache(string $cacheKey): GroqClientResponse
    {
        $response = Cache::get($cacheKey);

        if (!$response) {
            throw new GroqClientException('Cache miss');
        }

        return $response;
    }

    /**
     * @param string $cacheKey
     * @param GroqClientResponse $response
     */
    private function cacheResponse(string $cacheKey, GroqClientResponse $response): void
    {
        Cache::put($cacheKey, $response, $this->options->getCacheTtl());
    }

    /**
     * @param string $cacheKey
     * @return bool
     */
    private function shouldUseCache(string $cacheKey): bool
    {
        return Cache::has($cacheKey);
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @return string
     */
    private function getCacheKey(string $endpoint, array $data): string
    {
        return md5(implode(',', [$endpoint, json_encode($data)]));
    }
}