<?php

namespace App\Http\Controllers;

use App\Models\SalesForecast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesForecastController extends Controller
{
    /**
     * Get the sales forecast data for the authenticated user.
     * In a real application, this would calculate based on historical Deal data
     * and possibly interface with an AI service like OpenAI.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getForecast(Request $request)
    {
        $userId = Auth::id() ?? 1; // Fallback for testing if not properly authenticated

        // Check if we have an existing forecast for current month/year
        $currentMonth = date('n');
        $currentYear = date('Y');

        $forecast = SalesForecast::where('user_id', $userId)
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->first();

        if (!$forecast) {
            // Generate a mock forecast
            $forecast = SalesForecast::create([
                'user_id' => $userId,
                'month' => $currentMonth,
                'year' => $currentYear,
                'predicted_revenue' => rand(50000, 150000), // Mock prediction
                'confidence_score' => rand(75, 98) / 100, // Mock confidence
            ]);
        }

        // Return a mock dataset of the last 6 months + next 6 months for charting
        $historicalData = [];
        $forecastData = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = date('M Y', strtotime("-$i months"));
            $historicalData[] = [
                'month' => $month,
                'revenue' => rand(40000, 120000),
            ];
        }

        for ($i = 1; $i <= 6; $i++) {
            $month = date('M Y', strtotime("+$i months"));
            $forecastData[] = [
                'month' => $month,
                'predicted_revenue' => rand(60000, 160000),
            ];
        }

        return response()->json([
            'current_forecast' => $forecast,
            'historical_data' => $historicalData,
            'future_forecast' => $forecastData,
            'status' => 'success'
        ]);
    }
}
