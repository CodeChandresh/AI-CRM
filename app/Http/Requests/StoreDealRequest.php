<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Deal;

class StoreDealRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric',
            'stage_id' => [
                'required',
                Rule::in(Deal::STAGES),
            ],
            'probability' => 'required|numeric|between:0,100',
            'expected_close_date' => 'required|date',
            'lead_id' => 'required|integer|exists:leads,id',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'stage_id.in' => 'Invalid deal stage',
        ];
    }

    /**
     * Get the validated data.
     *
     * @return array
     */
    public function validated()
    {
        $validated = parent::validated();

        // Validate stage transition rules
        if ($validated['stage_id'] !== Deal::STAGES[0] && $validated['stage_id'] !== Deal::STAGES[count(Deal::STAGES) - 1]) {
            $previousStage = Deal::STAGES[array_search($validated['stage_id'], Deal::STAGES) - 1];
            $nextStage = Deal::STAGES[array_search($validated['stage_id'], Deal::STAGES) + 1];

            if ($validated['stage_id'] === $previousStage) {
                $this->validate([
                    'expected_close_date' => 'required|date|after_or_equal:' . now()->format('Y-m-d'),
                ]);
            } elseif ($validated['stage_id'] === $nextStage) {
                $this->validate([
                    'expected_close_date' => 'required|date|before_or_equal:' . now()->format('Y-m-d'),
                ]);
            }
        }

        return $validated;
    }
}