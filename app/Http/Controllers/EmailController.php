<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\Lead;
use App\Models\EmailCampaign;
use App\Models\EmailLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Jobs\SendEmailJob;
use App\Jobs\SendEmailCampaignJob;
use App\Notifications\SendEmailNotification;
use OpenAIApi\Client;
use Pusher\Pusher;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $emailTemplates = EmailTemplate::all();
        return inertia('Email/Index', [
            'emailTemplates' => $emailTemplates,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('Email/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'subject' => 'required',
            'body' => 'required',
            'template_id' => 'required|exists:email_templates,id',
        ]);

        $emailTemplate = EmailTemplate::find($validatedData['template_id']);
        $lead = Lead::find($request->input('lead_id'));

        if (!$lead) {
            return back()->withErrors(['lead_id' => 'Lead not found']);
        }

        $email = $emailTemplate->draftEmail($lead);
        $email->save();

        return back()->with('success', 'Email drafted successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $email = Email::find($id);
        return inertia('Email/Show', [
            'email' => $email,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $email = Email::find($id);
        return inertia('Email/Edit', [
            'email' => $email,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $email = Email::find($id);
        $email->update($request->all());
        return back()->with('success', 'Email updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $email = Email::find($id);
        $email->delete();
        return back()->with('success', 'Email deleted successfully');
    }

    /**
     * Send an email to a lead.
     *
     * @param  int  $leadId
     * @param  int  $emailId
     * @return \Illuminate\Http\Response
     */
    public function sendEmail($leadId, $emailId)
    {
        $lead = Lead::find($leadId);
        $email = Email::find($emailId);

        if (!$lead || !$email) {
            return back()->withErrors(['lead_id' => 'Lead or email not found']);
        }

        $client = new Client(env('OPENAI_API_KEY'));
        $response = $client->createEmail($email->subject, $email->body);

        if ($response->getStatusCode() !== 200) {
            return back()->withErrors(['error' => 'Failed to send email']);
        }

        $emailLog = new EmailLog();
        $emailLog->lead_id = $leadId;
        $emailLog->email_id = $emailId;
        $emailLog->status = 'sent';
        $emailLog->save();

        return back()->with('success', 'Email sent successfully');
    }

    /**
     * Send an email campaign to multiple leads.
     *
     * @param  int  $campaignId
     * @return \Illuminate\Http\Response
     */
    public function sendEmailCampaign($campaignId)
    {
        $campaign = EmailCampaign::find($campaignId);

        if (!$campaign) {
            return back()->withErrors(['campaign_id' => 'Campaign not found']);
        }

        $leads = $campaign->leads;

        foreach ($leads as $lead) {
            $email = Email::find($campaign->email_id);
            $this->sendEmail($lead->id, $email->id);
        }

        return back()->with('success', 'Email campaign sent successfully');
    }

    /**
     * Draft an email using a template.
     *
     * @param  int  $templateId
     * @param  int  $leadId
     * @return \Illuminate\Http\Response
     */
    public function draftEmail($templateId, $leadId)
    {
        $template = EmailTemplate::find($templateId);
        $lead = Lead::find($leadId);

        if (!$template || !$lead) {
            return back()->withErrors(['template_id' => 'Template or lead not found']);
        }

        $email = $template->draftEmail($lead);
        $email->save();

        return back()->with('success', 'Email drafted successfully');
    }
}