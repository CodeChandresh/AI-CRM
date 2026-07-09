<?php

// File: app/Http/Controllers/AIController.php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Contact;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use OpenAIApi\Client;
use Pusher\Pusher;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\SendEmailJob;
use App\Jobs\AnalyzeSentimentJob;
use App\Jobs\ScoreLeadJob;
use App\Jobs\PredictChurnJob;

class AIController extends Controller
{
    /**
     * Score a lead using AI model.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function score(Request $request)
    {
        $this->validate($request, [
            'lead_id' => 'required|integer',
        ]);

        $lead = Lead::find($request->input('lead_id'));

        if (!$lead) {
            return response()->json(['error' => 'Lead not found'], 404);
        }

        $score = ScoreLeadJob::dispatch($lead);

        return response()->json(['message' => 'Lead scored successfully', 'score' => $score]);
    }

    /**
     * Draft an email using AI model.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function draft(Request $request)
    {
        $this->validate($request, [
            'lead_id' => 'required|integer',
            'template_id' => 'required|integer',
        ]);

        $lead = Lead::find((int) $request->input('lead_id'));
        $templateId = (int) $request->input('template_id');
        $template = Storage::disk('public')->getDriver()->getAdapter()->getPathPrefix() . '/templates/' . $templateId . '.txt';

        if (!$lead || !$template) {
            return response()->json(['error' => 'Lead or template not found'], 404);
        }

        $draft = Bus::dispatch(new SendEmailJob($lead, $template));

        return response()->json(['message' => 'Email drafted successfully', 'draft' => $draft]);
    }

    /**
     * Analyze sentiment of a lead using AI model.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function analyze(Request $request)
    {
        $this->validate($request, [
            'lead_id' => 'required|integer',
        ]);

        $lead = Lead::find($request->input('lead_id'));

        if (!$lead) {
            return response()->json(['error' => 'Lead not found'], 404);
        }

        $analysis = AnalyzeSentimentJob::dispatch($lead);

        return response()->json(['message' => 'Sentiment analyzed successfully', 'analysis' => $analysis]);
    }

    /**
     * Predict churn of a lead using AI model.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function predict(Request $request)
    {
        $this->validate($request, [
            'lead_id' => 'required|integer',
        ]);

        $lead = Lead::find($request->input('lead_id'));

        if (!$lead) {
            return response()->json(['error' => 'Lead not found'], 404);
        }

        $prediction = PredictChurnJob::dispatch($lead);

        return response()->json(['message' => 'Churn predicted successfully', 'prediction' => $prediction]);
    }
}