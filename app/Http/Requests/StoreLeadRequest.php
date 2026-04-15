<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Lead;

class StoreLeadRequest extends FormRequest
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
            'email' => 'required|email|max:255|unique:leads',
            'phone' => 'required|string|max:20',
            'company' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'lead_source' => 'required|string|max:255',
            'description' => 'required|string',
            'score' => 'required|numeric',
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.unique' => 'Email already exists in the system.',
        ];
    }

    /**
     * Get the validation error messages.
     *
     * @return array
     */
    public function validationErrorMessages()
    {
        return [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a string.',
            'name.max' => 'Name must not exceed 255 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.max' => 'Email must not exceed 255 characters.',
            'email.unique' => 'Email already exists in the system.',
            'phone.required' => 'Phone is required.',
            'phone.string' => 'Phone must be a string.',
            'phone.max' => 'Phone must not exceed 20 characters.',
            'company.required' => 'Company is required.',
            'company.string' => 'Company must be a string.',
            'company.max' => 'Company must not exceed 255 characters.',
            'industry.required' => 'Industry is required.',
            'industry.string' => 'Industry must be a string.',
            'industry.max' => 'Industry must not exceed 255 characters.',
            'lead_source.required' => 'Lead source is required.',
            'lead_source.string' => 'Lead source must be a string.',
            'lead_source.max' => 'Lead source must not exceed 255 characters.',
            'description.required' => 'Description is required.',
            'description.string' => 'Description must be a string.',
            'score.required' => 'Score is required.',
            'score.numeric' => 'Score must be a numeric value.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        $messages = $validator->errors()->all();

        if ($this->ajax()) {
            return response()->json(['errors' => $messages], 422);
        }

        $this->getValidatorInstance()->validateResolved();

        $this->messages = $messages;

        $this->errors = $validator->errors()->all();
    }
}