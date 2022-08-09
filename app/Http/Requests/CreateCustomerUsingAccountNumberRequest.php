<?php

namespace App\Http\Requests;

use App\Services\ResponseService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateCustomerUsingAccountNumberRequest extends FormRequest
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
            'email' => 'required|email',
            'username' => 'required|alpha_dash|unique:customers,username',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
            'account_number' => 'required|unique:customers,nuban|exists:otp_codes,nuban',
            'otp_code' => 'required|exists:otp_codes,token',
            'photo' => 'required|file|image:jpeg,png|max:1192'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException((new ResponseService())->getErrorResource([
            "message" => $validator->getMessageBag()->first(),
            "field_errors" => $validator->errors()
        ]));
    }
}
