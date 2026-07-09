<?php

namespace App\Jobs;

use App\Models\Contact;
use App\Models\EmailTemplate;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use OpenAIApi\Client;
use Predis\Connection\ConnectionException;
use Predis\Connection\ConnectionInterface;

class GenerateEmailDraftJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The contact instance.
     *
     * @var \App\Models\Contact
     */
    public $contact;

    /**
     * The email template instance.
     *
     * @var \App\Models\EmailTemplate
     */
    public $emailTemplate;

    /**
     * The lead instance.
     *
     * @var \App\Models\Lead
     */
    public $lead;

    /**
     * The user instance.
     *
     * @var \App\Models\User
     */
    public $user;

    /**
     * Create a new job instance.
     *
     * @param  \App\Models\Contact  $contact
     * @param  \App\Models\EmailTemplate  $emailTemplate
     * @param  \App\Models\Lead  $lead
     * @param  \App\Models\User  $user
     * @return void
     */
    public function __construct(Contact $contact, EmailTemplate $emailTemplate, Lead $lead, User $user)
    {
        $this->contact = $contact;
        $this->emailTemplate = $emailTemplate;
        $this->lead = $lead;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // Initialize the OpenAI client
            $client = new Client(env('OPENAI_API_KEY'));

            // Get the lead's conversation history
            $conversationHistory = $this->lead->conversationHistory;

            // Analyze the conversation history using OpenAI
            $analysis = $client->analyzeConversation($conversationHistory);

            // Get the sentiment analysis result
            $sentiment = $analysis->sentiment;

            // Determine the email tone based on the sentiment analysis result
            $emailTone = $sentiment->score > 0.5 ? 'positive' : 'negative';

            // Get the email template with the matching tone
            $emailTemplate = EmailTemplate::where('tone', $emailTone)->first();

            // Generate the email draft using the email template and lead data
            $emailDraft = $this->generateEmailDraft($emailTemplate, $this->lead);

            // Save the email draft to Redis
            Redis::set('email_draft', $emailDraft);

            // Save the email draft to the database
            $this->lead->email_draft = $emailDraft;
            $this->lead->save();

            // Send a notification to the user
            $this->user->notify(new EmailDraftGenerated($this->lead));
        } catch (ConnectionException $e) {
            // Handle Redis connection exception
            Log::error('Redis connection exception: ' . $e->getMessage());
        } catch (\Exception $e) {
            // Handle any other exceptions
            Log::error('Error generating email draft: ' . $e->getMessage());
        }
    }

    /**
     * Generate the email draft using the email template and lead data.
     *
     * @param  \App\Models\EmailTemplate  $emailTemplate
     * @param  \App\Models\Lead  $lead
     * @return string
     */
    protected function generateEmailDraft(EmailTemplate $emailTemplate, Lead $lead)
    {
        // Replace placeholders in the email template with lead data
        $emailDraft = $emailTemplate->body;
        $emailDraft = str_replace('{{ name }}', $lead->name, $emailDraft);
        $emailDraft = str_replace('{{ email }}', $lead->email, $emailDraft);

        return $emailDraft;
    }
}