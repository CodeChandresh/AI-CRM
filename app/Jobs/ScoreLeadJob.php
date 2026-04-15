<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Models\LeadScore;
use App\Services\LeadScorer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Queue job for async AI lead scoring
 */
class ScoreLeadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The lead instance
     *
     * @var Lead
     */
    public $lead;

    /**
     * Create a new job instance.
     *
     * @param Lead $lead
     */
    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(LeadScorer $scorer)
    {
        try {
            // Score the lead using the AI model
            $score = $scorer->score($this->lead);

            // Save the lead score
            $leadScore = LeadScore::create([
                'lead_id' => $this->lead->id,
                'score' => $score,
            ]);

            // Log the lead score
            Log::info("Lead #{$this->lead->id} scored {$score}");
        } catch (\Exception $e) {
            // Log any errors that occur during scoring
            Log::error("Error scoring lead #{$this->lead->id}: {$e->getMessage()}");
        }
    }
}