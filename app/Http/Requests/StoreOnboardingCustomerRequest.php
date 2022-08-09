<?php

namespace App\Http\Requests;

use App\Models\CustomerOnboardingCustomer;
use App\Services\ResponseService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreOnboardingCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'phone' => ['required','numeric','digits:11','unique:customers,phone'],
            'email' => ['nullable','email','unique:customers,email'],
            'save_beneficiary' => ['required','boolean'],
            'amount' => ['required','numeric'],
            'account_number' => ['required','numeric'],
            'narration' => ['nullable']
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            //Check if phone number or email exist in customer_onboarding_customer table
            if (CustomerOnboardingCustomer::where('phone',$this->phone)->exists()) {
                $validator->errors()->add('phone', 'Phone number already exist!');
            }
            if ($this->email && CustomerOnboardingCustomer::where('email',$this->email)->exists()) {
                $validator->errors()->add('email', 'Email already exist!');
            }


        });
    }

    /**
     * @param \Illuminate\Contracts\Validation\Validator $validator
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException((new ResponseService())->getErrorResource([
            "message" => $validator->getMessageBag()->first(),
            "field_errors" => $validator->errors()
        ]));
    }
}
