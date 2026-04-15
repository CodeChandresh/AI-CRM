<?php

namespace App\Jobs;

use App\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use OpenAIApi\Client;
use OpenAIApi\Model\TextClassification;
use OpenAIApi\Model\TextClassificationResult;
use Predis\Client as RedisClient;

class AnalyzeSentimentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The email instance.
     *
     * @var \App\Models\Email
     */
    public $email;

    /**
     * Create a new job instance.
     *
     * @param  \App\Models\Email  $email
     * @return void
     */
    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Initialize the OpenAI API client
        $openaiClient = new Client(config('services.openai.api_key'));

        // Initialize the Redis client
        $redisClient = new RedisClient(config('services.redis.host'), config('services.redis.port'));

        // Get the email content
        $emailContent = $this->email->content;

        // Analyze the sentiment using the OpenAI API
        $response = $openaiClient->textClassification([
            'model' => 'text-similarity-curie',
            'input' => $emailContent,
        ]);

        // Get the sentiment analysis result
        $sentimentAnalysisResult = $response->getResult();

        // Get the sentiment score
        $sentimentScore = $sentimentAnalysisResult->getScore();

        // Store the sentiment analysis result in Redis
        $redisClient->set("email:{$this->email->id}:sentiment_score", $sentimentScore);

        // Update the email with the sentiment analysis result
        $this->email->update([
            'sentiment_score' => $sentimentScore,
        ]);
    }
}