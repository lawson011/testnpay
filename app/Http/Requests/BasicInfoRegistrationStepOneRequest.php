<?php

namespace App\Http\Requests;

use App\Repositories\OtpCode\OtpCodeInterface;
use App\Services\ResponseService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Validator;

class BasicInfoRegistrationStepOneRequest extends FormRequest
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
            'username' => "required|alpha_dash|unique:customers,username",
            'password' => 'required|min:6',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required|exists:states,id',
            'referred_by' => 'nullable|exists:customers,referral_code',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        //Send otp code to customer phone number
            if ($validator->passes() && $this->phone) {
                //generate token
                if (App::environment(['local', 'staging'])) {
                    $token = '000000';
                }else{
                    $token = getUniqueToken();
                }

                $otpInterface = app(OtpCodeInterface::class);

                //save token
                //phone will be use as nuban
                $otpInterface->create($token, $this->phone);

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
