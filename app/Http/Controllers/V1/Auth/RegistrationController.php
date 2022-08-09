<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Requests\BasicInfoRegistrationStepOneRequest;
use App\Http\Requests\BasicInfoRegistrationStepTwoRequest;
use App\Http\Requests\BvnRequest;
use App\Http\Requests\CreateCustomerUsingAccountNumberRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\VerifyAccountNumber;
use App\Http\Requests\VerifyOtpCodeRequest;
use App\Http\Requests\VerifyPhoneOtpRequest;
use App\Http\Requests\WebBasicFormStepOnoValidationRequest;
use App\Services\Auth\RegistrationControllerService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class RegistrationController extends Controller
{
    protected $registrationControllerService;

    public function __construct(RegistrationControllerService $registrationControllerService)
    {
        $this->registrationControllerService = $registrationControllerService;
    }

    public function verifyBvn(BvnRequest $request){
      return $this->registrationControllerService->verifyBvn($request);
    }

    public function basicFormStepOneValidation(BasicInfoRegistrationStepOneRequest $request){
        return $this->registrationControllerService->basicFormStepOneValidation();
    }

    public function verifyPhoneOtp(VerifyPhoneOtpRequest $request){
      return  $this->registrationControllerService->verifyPhoneOtp($request);
    }

    public function basicFormStepTwoValidation(BasicInfoRegistrationStepTwoRequest $request){
        return $this->registrationControllerService->basicFormStepTwoValidation($request);
    }

    public function testPhoto(Request $request){
        return $this->registrationControllerService->testPhoto($request);
    }

    public function webBasicFormStepTwoValidation(WebBasicFormStepOnoValidationRequest $request){
        return $this->registrationControllerService->basicFormStepOneValidation();
    }

    public function create(RegistrationRequest $request){
        return $this->registrationControllerService->create($request);
    }

    public function verifyAccountNumber(VerifyAccountNumber $request){
        return $this->registrationControllerService->verifyAccountNumber($request);
    }

    public function verifyOtpCode(VerifyOtpCodeRequest $request){
        return $this->registrationControllerService->verifyOtpCode($request);
    }

    public function createCustomerUsingAccountNumber(CreateCustomerUsingAccountNumberRequest $request){
        return $this->registrationControllerService->createCustomerUsingAccountNumber($request);
    }

    public function getState(){
        return  $this->registrationControllerService->getState();
    }

}
