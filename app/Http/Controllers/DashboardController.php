<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Contact;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Horizon;
use Illuminate\Support\Facades\Redis;
use Inertia\Inertia;
use Tightenco\Ziggy\Ziggy;
use OpenAI\Client;
use Pusher\Pusher;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        // Authenticate the user
        $user = Auth::user();

        // Get the user's leads
        $leads = Lead::where('user_id', $user->id)->get();

        // Get the user's contacts
        $contacts = Contact::where('user_id', $user->id)->get();

        // Get the user's accounts
        $accounts = Account::where('user_id', $user->id)->get();

        // Get the AI insights
        $aiInsights = $this->getAiInsights();

        // Get the KPIs
        $kpis = $this->getKpis();

        // Get the charts
        $charts = $this->getCharts();

        // Return the dashboard view
        return Inertia::render('Dashboard', [
            'leads' => $leads,
            'contacts' => $contacts,
            'accounts' => $accounts,
            'aiInsights' => $aiInsights,
            'kpis' => $kpis,
            'charts' => $charts,
        ]);
    }

    /**
     * Get the AI insights.
     *
     * @return array
     */
    private function getAiInsights()
    {
        // Initialize the OpenAI client
        $client = app(\OpenAI\Client::class);

        // Get the lead scoring insights
        $leadScoringInsights = $client->leadScoring()->getInsights();

        // Get the sentiment analysis insights
        $sentimentAnalysisInsights = $client->sentimentAnalysis()->getInsights();

        // Get the churn prediction insights
        $churnPredictionInsights = $client->churnPrediction()->getInsights();

        // Return the AI insights
        return [
            'leadScoring' => $leadScoringInsights,
            'sentimentAnalysis' => $sentimentAnalysisInsights,
            'churnPrediction' => $churnPredictionInsights,
        ];
    }

    /**
     * Get the KPIs.
     *
     * @return array
     */
    private function getKpis()
    {
        // Get the lead count
        $leadCount = Lead::count();

        // Get the contact count
        $contactCount = Contact::count();

        // Get the account count
        $accountCount = Account::count();

        // Return the KPIs
        return [
            'leadCount' => $leadCount,
            'contactCount' => $contactCount,
            'accountCount' => $accountCount,
        ];
    }

    /**
     * Get the charts.
     *
     * @return array
     */
    private function getCharts()
    {
        // Get the lead chart data
        $leadChartData = Lead::chartData();

        // Get the contact chart data
        $contactChartData = Contact::chartData();

        // Get the account chart data
        $accountChartData = Account::chartData();

        // Return the charts
        return [
            'leadChart' => $leadChartData,
            'contactChart' => $contactChartData,
            'accountChart' => $accountChartData,
        ];
    }
}