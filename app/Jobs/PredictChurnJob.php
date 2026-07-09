<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Models\Segment;
use App\Models\ChurnPrediction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\RedisTransaction;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Zip;
use Illuminate\Support\Facades\Http;
use OpenAIApi\OpenAI;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PredictChurnJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected $segmentId;
    protected $customerId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($segmentId, $customerId = null)
    {
        $this->segmentId = $segmentId;
        $this->customerId = $customerId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // If a specific customer ID is not provided, dispatch jobs for all customers in the segment
        if (is_null($this->customerId)) {
            $customers = Customer::where('segment_id', $this->segmentId)->get(['id']);

            foreach ($customers as $customer) {
                self::dispatch($this->segmentId, $customer->id);
            }
            return;
        }

        // Process a specific customer
        $customer = Customer::find($this->customerId);

        if (!$customer) {
            return;
        }

        // Get the customer segment
        $segment = Segment::find($this->segmentId);

        if (!$segment) {
            return;
        }

        // Get the customer data
        $customerData = $customer->getData();

        // Make a prediction using the OpenAI API
        $openai = new OpenAI('YOUR_API_KEY');
        $response = $openai->predict('text-classification', $customerData);

        // Get the prediction result
        $prediction = $response->data->predictions[0];

        // Create a new churn prediction
        $churnPrediction = new ChurnPrediction();
        $churnPrediction->customer_id = $customer->id;
        $churnPrediction->segment_id = $segment->id;
        $churnPrediction->prediction = $prediction->label;
        $churnPrediction->confidence = $prediction->score;
        $churnPrediction->save();
    }

    /**
     * The job failed to process.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function failed(\Exception $exception)
    {
        Log::error('Churn prediction job failed: ' . $exception->getMessage());
    }
}