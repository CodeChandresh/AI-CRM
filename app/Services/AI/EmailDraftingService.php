<?php

// app/Services/AI/EmailDraftingService.php

namespace App\Services\AI;

use App\Models\Lead;
use App\Models\Template;
use App\Models\User;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Str;
use OpenAI\Client;
use Predis\Connection\ConnectionException;
use Predis\Connection\ConnectionInterface;

class EmailDraftingService
{
    /**
     * @var Client
     */
    private $openAiClient;

    /**
     * @var Redis
     */
    private $redis;

    /**
     * @param Client $openAiClient
     * @param Redis $redis
     */
    public function __construct(Client $openAiClient, Redis $redis)
    {
        $this->openAiClient = $openAiClient;
        $this->redis = $redis;
    }

    /**
     * Generate a personalized email based on lead context and template
     *
     * @param Lead $lead
     * @param Template $template
     * @param User $user
     * @return string
     */
    public function generateEmail(Lead $lead, Template $template, User $user): string
    {
        // Check if email is already generated and stored in Redis
        $emailKey = "email:{$lead->id}:{$template->id}";
        if ($this->redis->exists($emailKey)) {
            return $this->redis->get($emailKey);
        }

        // Get lead context and template data
        $leadData = $lead->toArray();
        $templateData = $template->toArray();

        // Use OpenAI API to generate personalized email content
        try {
            $response = $this->openAiClient->completions()->create([
                'model' => 'text-davinci-003',
                'prompt' => $this->generatePrompt($leadData, $templateData),
                'max_tokens' => 2048,
                'temperature' => 0.7,
                'top_p' => 1,
                'n' => 1,
                'stream' => false,
                'logprobs' => null,
                'echo' => true,
                'stop' => null,
                'presence_penalty' => 0,
                'frequency_penalty' => 0,
                'best_of' => 1,
                'n_stories' => 1,
                'logit_bias' => null,
                'user' => $user->id,
            ]);

            $emailContent = $response->choices[0]->text;

            // Store generated email in Redis
            $this->redis->set($emailKey, $emailContent);
            $this->redis->expire($emailKey, 3600); // expire in 1 hour

            return $emailContent;
        } catch (ConnectionException $e) {
            // Handle Redis connection error
            logger()->error('Redis connection error: ' . $e->getMessage());
            return '';
        } catch (\Exception $e) {
            // Handle OpenAI API error
            logger()->error('OpenAI API error: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Generate prompt for OpenAI API
     *
     * @param array $leadData
     * @param array $templateData
     * @return string
     */
    private function generatePrompt(array $leadData, array $templateData): string
    {
        $prompt = "Dear " . $leadData['name'] . ",\n\n";

        // Add lead context to prompt
        foreach ($leadData as $key => $value) {
            if (Str::contains($key, 'date')) {
                $prompt .= "On " . $value . ",\n";
            } elseif (Str::contains($key, 'amount')) {
                $prompt .= "You have a balance of $" . $value . ",\n";
            } else {
                $prompt .= $key . ": " . $value . "\n";
            }
        }

        // Add template data to prompt
        foreach ($templateData as $key => $value) {
            $prompt .= $key . ": " . $value . "\n";
        }

        return $prompt;
    }
}