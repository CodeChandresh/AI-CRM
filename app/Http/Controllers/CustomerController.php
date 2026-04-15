<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;

/**
 * CustomerController handles customer management and churn prediction display
 */
class CustomerController extends Controller
{
    /**
     * Display a listing of customers with pagination
     */
    public function index(Request $request)
    {
        try {
            $customers = Customer::query()
                ->when($request->input('search'), function (Builder $query, $search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%")
                          ->orWhere('phone', 'like', "%{$search}%");
                })
                ->orderBy($request->input('sort_by', 'created_at'), $request->input('sort_direction', 'desc'))
                ->paginate($request->input('per_page', 15))
                ->withQueryString();

            return Inertia::render('Customers/Index', [
                'customers' => $customers,
                'filters' => $request->only(['search', 'sort_by', 'sort_direction', 'per_page'])
            ]);
        } catch (\Exception $e) {
            Log::error('Customer index error: ' . $e->getMessage());
            return back()->withErrors(__('Failed to load customers'));
        }
    }

    /**
     * Show the form for creating a new customer
     */
    public function create()
    {
        return Inertia::render('Customers/Create');
    }

    /**
     * Store a newly created customer in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique(Customer::class)],
            'phone' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:100'],
            'annual_revenue' => ['nullable', 'numeric'],
            'churn_risk' => ['nullable', 'boolean'],
        ]);

        try {
            Customer::create($validated);
            return redirect()->route('customers.index')->with('success', __('Customer created successfully'));
        } catch (\Exception $e) {
            Log::error('Customer creation error: ' . $e->getMessage());
            return back()->withErrors(__('Failed to create customer'))->withInput();
        }
    }

    /**
     * Display the specified customer with churn prediction
     */
    public function show(Customer $customer)
    {
        try {
            // Calculate churn prediction using AI model (mock implementation)
            $churnPrediction = $this->calculateChurnPrediction($customer);
            
            return Inertia::render('Customers/Show', [
                'customer' => $customer->loadMissing(['interactions', 'invoices']),
                'churnPrediction' => $churnPrediction
            ]);
        } catch (\Exception $e) {
            Log::error('Customer show error: ' . $e->getMessage());
            return back()->withErrors(__('Failed to load customer details'));
        }
    }

    /**
     * Show the form for editing the specified customer
     */
    public function edit(Customer $customer)
    {
        return Inertia::render('Customers/Edit', [
            'customer' => $customer->loadMissing('invoices')
        ]);
    }

    /**
     * Update the specified customer in storage
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique(Customer::class)->ignore($customer->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:100'],
            'annual_revenue' => ['nullable', 'numeric'],
            'churn_risk' => ['nullable', 'boolean'],
        ]);

        try {
            $customer->update($validated);
            return redirect()->route('customers.show', $customer)->with('success', __('Customer updated successfully'));
        } catch (\Exception $e) {
            Log::error('Customer update error: ' . $e->getMessage());
            return back()->withErrors(__('Failed to update customer'))->withInput();
        }
    }

    /**
     * Remove the specified customer from storage
     */
    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();
            return response()->json(['message' => __('Customer deleted successfully')]);
        } catch (\Exception $e) {
            Log::error('Customer deletion error: ' . $e->getMessage());
            return response()->json(['error' => __('Failed to delete customer')], 500);
        }
    }

    /**
     * Calculate AI-powered churn prediction for a customer
     */
    private function calculateChurnPrediction(Customer $customer): array
    {
        // In production, this would call an AI model or external API
        // Mock implementation based on simple rules
        $riskScore = 0;
        
        if ($customer->churn_risk) {
            $riskScore += 30;
        }
        
        if ($customer->invoices->count() < 3) {
            $riskScore += 20;
        }
        
        if ($customer->interactions->count() === 0) {
            $riskScore += 25;
        }
        
        $riskLevel = 'low';
        if ($riskScore > 60) {
            $riskLevel = 'high';
        } elseif ($riskScore > 30) {
            $riskLevel = 'medium';
        }

        return [
            'risk_level' => $riskLevel,
            'score' => min(100, $riskScore + rand(0, 10)),
            'reasons' => [],
            'last_updated' => now()->format('Y-m-d H:i:s')
        ];
    }
}