<?php

namespace App\Services\AI;

use App\Models\Email;
use App\Models\Reply;
use Illuminate\Support\Facades\Http;
use OpenAIApi\Client;
use OpenAIApi\Endpoints\TextClassification;
use OpenAIApi\Models\Classification;
use OpenAIApi\Models\ClassificationModel;
use OpenAIApi\Models\ClassificationResult;
use OpenAIApi\Models\TextClassificationRequest;
use OpenAIApi\Models\TextClassificationResponse;

class SentimentAnalysisService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Analyze the sentiment of an email or reply.
     *
     * @param Email|Reply $emailOrReply
     * @return array
     */
    public function analyzeSentiment($emailOrReply): array
    {
        $text = $emailOrReply->body;

        $textClassificationRequest = new TextClassificationRequest();
        $textClassificationRequest->setModelId('text-classification');
        $textClassificationRequest->setText($text);

        $response = $this->client->textClassification()->create($textClassificationRequest);

        $classificationResult = $response->getResult();

        $classification = $classificationResult->getClassifications()[0];

        $sentiment = $classification->getLabel();

        switch ($sentiment) {
            case 'positive':
                $sentiment = 'positive';
                break;
            case 'negative':
                $sentiment = 'negative';
                break;
            case 'neutral':
                $sentiment = 'neutral';
                break;
            case 'urgent':
                $sentiment = 'urgent';
                break;
            default:
                $sentiment = 'unknown';
                break;
        }

        return [
            'sentiment' => $sentiment,
            'confidence' => $classification->getScore(),
        ];
    }
}