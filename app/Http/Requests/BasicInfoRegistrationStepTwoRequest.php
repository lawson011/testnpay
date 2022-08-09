<?php

namespace App\Http\Requests;

use App\Services\ResponseService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BasicInfoRegistrationStepTwoRequest extends FormRequest
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
            'bvn' => 'nullable|digits:11|unique:customer_bio_data,bvn',
            'first_name' => 'required',
            'last_name' => 'required',
            'dob' => 'required',
            'phone' => 'required|numeric|digits:11|unique:customers,phone',
            'gender' => 'required|in:Male,Female',
            'email' => 'required|email|unique:customers,email',
            'username' => 'required|alpha_dash|unique:customers,username',
            'password' => 'required|min:6',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required|exists:states,id',
            'occupation' => 'required',
            'salary_range' => 'required',
            'next_of_kin_name' => 'required',
            'next_of_kin_phone' => 'required',
            'next_of_kin_address' => 'required',
            'next_of_kin_state' => 'required|exists:states,id',
            'next_of_kin_city' => 'required',
            'referred_by' => 'nullable|exists:customers,referral_code'
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException((new ResponseService())->getErrorResource([
            "message" => $validator->getMessageBag()->first(),
            "field_errors" => $validator->errors()
        ]));
    }
}
