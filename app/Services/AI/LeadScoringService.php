<?php

namespace App\Services\AI;

use App\Models\Lead;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Activity;
use App\Models\Score;
use App\Models\ScoreCategory;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use OpenAI\Client;
use MongoDB\Client as Mongo;
use Predis\Client as Predis;

class LeadScoringService
{
    /**
     * The OpenAI client instance.
     *
     * @var \OpenAI\Client
     */
    protected $openai;

    /**
     * The Redis client instance.
     *
     * @var \Predis\Client
     */
    protected $redis;

    /**
     * The MongoDB client instance.
     *
     * @var \MongoDB\Client
     */
    protected $mongo;

    /**
     * Create a new LeadScoringService instance.
     *
     * @param  \OpenAI\Client  $openai
     * @param  \Predis\Client  $redis
     * @param  \MongoDB\Client  $mongo
     * @return void
     */
    public function __construct(Client $openai, Predis $redis, Mongo $mongo)
    {
        $this->openai = $openai;
        $this->redis = $redis;
        $this->mongo = $mongo;
    }

    /**
     * Calculate the lead score based on behavior, demographics, and engagement.
     *
     * @param  \App\Models\Lead  $lead
     * @return int
     */
    public function calculateScore(Lead $lead)
    {
        // Initialize the score
        $score = 0;

        // Check if the lead has any activities
        if ($lead->activities()->count() > 0) {
            // Calculate the activity score
            $activityScore = $this->calculateActivityScore($lead->activities);
            $score += $activityScore;
        }

        // Check if the lead has any demographics
        if ($lead->demographics()->count() > 0) {
            // Calculate the demographic score
            $demographicScore = $this->calculateDemographicScore($lead->demographics);
            $score += $demographicScore;
        }

        // Check if the lead has any engagement metrics
        if ($lead->engagement()->count() > 0) {
            // Calculate the engagement score
            $engagementScore = $this->calculateEngagementScore($lead->engagement);
            $score += $engagementScore;
        }

        // Store the score in the database
        $scoreCategory = ScoreCategory::where('name', 'lead_score')->first();
        $score = Score::create([
            'lead_id' => $lead->id,
            'score_category_id' => $scoreCategory->id,
            'score' => $score,
        ]);

        // Return the score
        return $score->score;
    }

    /**
     * Calculate the activity score based on the lead's activities.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $activities
     * @return int
     */
    protected function calculateActivityScore($activities)
    {
        // Initialize the score
        $score = 0;

        // Loop through each activity
        foreach ($activities as $activity) {
            // Check if the activity is a meeting
            if ($activity->type === 'meeting') {
                // Add 10 points for a meeting
                $score += 10;
            }

            // Check if the activity is an email
            elseif ($activity->type === 'email') {
                // Add 5 points for an email
                $score += 5;
            }
        }

        // Return the score
        return $score;
    }

    /**
     * Calculate the demographic score based on the lead's demographics.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $demographics
     * @return int
     */
    protected function calculateDemographicScore($demographics)
    {
        // Initialize the score
        $score = 0;

        // Loop through each demographic
        foreach ($demographics as $demographic) {
            // Check if the demographic is a company size
            if ($demographic->type === 'company_size') {
                // Add 20 points for a company size
                $score += 20;
            }

            // Check if the demographic is an industry
            elseif ($demographic->type === 'industry') {
                // Add 15 points for an industry
                $score += 15;
            }
        }

        // Return the score
        return $score;
    }

    /**
     * Calculate the engagement score based on the lead's engagement metrics.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $engagement
     * @return int
     */
    protected function calculateEngagementScore($engagement)
    {
        // Initialize the score
        $score = 0;

        // Loop through each engagement metric
        foreach ($engagement as $metric) {
            // Check if the metric is a response rate
            if ($metric->type === 'response_rate') {
                // Add 10 points for a response rate
                $score += 10;
            }

            // Check if the metric is an open rate
            elseif ($metric->type === 'open_rate') {
                // Add 5 points for an open rate
                $score += 5;
            }
        }

        // Return the score
        return $score;
    }
}