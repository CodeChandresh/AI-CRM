<?php

namespace App\Services\AI;

use App\Models\Customer;
use App\Models\SupportTicket;
use App\Models\UsagePattern;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Redis;
use OpenAI\Client;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;

class ChurnPredictionService
{
    /**
     * @var Client
     */
    private $openAiClient;

    public function __construct(Client $openAiClient)
    {
        $this->openAiClient = $openAiClient;
    }

    /**
     * Predict customer churn risk based on usage patterns and support tickets
     *
     * @param Customer $customer
     * @return float
     */
    public function predictChurnRisk(Customer $customer)
    {
        // Get customer usage patterns and support tickets
        $usagePatterns = UsagePattern::where('customer_id', $customer->id)->get();
        $supportTickets = SupportTicket::where('customer_id', $customer->id)->get();

        // Create a JSON payload for the OpenAI API
        $payload = [
            'usage_patterns' => $usagePatterns->pluck('value')->toArray(),
            'support_tickets' => $supportTickets->pluck('description')->toArray(),
        ];

        // Use the OpenAI API to predict churn risk
        $response = $this->openAiClient->predictChurnRisk($payload);

        // Return the predicted churn risk
        return $response->getChurnRisk();
    }

    /**
     * Calculate customer churn risk score based on usage patterns and support tickets
     *
     * @param Customer $customer
     * @return float
     */
    public function calculateChurnRiskScore(Customer $customer)
    {
        // Get customer usage patterns and support tickets
        $usagePatterns = UsagePattern::where('customer_id', $customer->id)->get();
        $supportTickets = SupportTicket::where('customer_id', $customer->id)->get();

        // Calculate churn risk score based on usage patterns and support tickets
        $churnRiskScore = 0;
        foreach ($usagePatterns as $usagePattern) {
            $churnRiskScore += $usagePattern->value;
        }
        foreach ($supportTickets as $supportTicket) {
            $churnRiskScore += $supportTicket->description;
        }

        // Return the calculated churn risk score
        return $churnRiskScore;
    }

    /**
     * Send a notification to the customer if their churn risk score exceeds a certain threshold
     *
     * @param Customer $customer
     * @param float $threshold
     */
    public function sendNotification(Customer $customer, float $threshold)
    {
        // Calculate customer churn risk score
        $churnRiskScore = $this->calculateChurnRiskScore($customer);

        // Check if churn risk score exceeds threshold
        if ($churnRiskScore > $threshold) {
            // Send a notification to the customer
            Bus::dispatch(new SendNotification($customer));
        }
    }
}