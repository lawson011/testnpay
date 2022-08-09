<?php

namespace App\Http\Requests;

use App\Repositories\OtpCode\OtpCodeInterface;
use App\Services\ResponseService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Validator;

class WebBasicFormStepOnoValidationRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required|alpha_dash|unique:customers,username',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
            'phone' => 'required|numeric|digits:11|unique:customers,phone',
            'referred_by' => 'nullable|exists:customers,referral_code',
        ];
    }

    public function withValidator(Validator $validator)
    {

        //Send otp code to customer phone number
        if ($validator->passes() && $this->phone) {
            //generate token
            if (App::environment(['local', 'staging'])) {
                $token = '0000';
            }else{
                $token = getUniqueToken();
            }

            $otpInterface = app(OtpCodeInterface::class);

            //save token
            //phone will be use as nuban
            $otpInterface->create($token, $this->phone, $this->username);

            //send sms
            $sms[] = [
                "AccountNumber" => "string",
                "To" => $this->phone,
                "Body" => "Otp-Code " . $token,
                "ReferenceNo" => $token + getUniqueToken(5),
            ];

            sendSms($sms);
        }

    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException((new ResponseService())->getErrorResource([
            "message" => $validator->getMessageBag()->first(),
            "field_errors" => $validator->errors()
        ]));
    }
}
