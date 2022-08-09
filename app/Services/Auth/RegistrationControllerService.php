<?php

namespace App\Services\Auth;

use App\Http\Controllers\Admin\Traits\CustomerAccountTrait;
use App\Jobs\ExistingCustomerRegistrationOtpCodeJob;
use App\Models\State;
use App\Repositories\CustomerAuth\CustomerAuthInterface;
use App\Repositories\CustomerBioData\CustomerBioDataInterface;
use App\Repositories\CustomerNextOfKin\CustomerNextOfKinInterface;
use App\Repositories\CustomerOnboardingCustomer\CustomerOnboardingCustomerInterface;
use App\Repositories\CustomerRegistrationSetting\CustomerRegistrationSettingInterface;
use App\Repositories\OtpCode\OtpCodeInterface;
use App\Repositories\VerifyEmail\VerifyEmailInterface;
use App\Services\BankOne\BankOneUtilServices;
use App\Services\BankOne\CustomerAccount\AccountServices;
use App\Services\Paystack\PaystackService;
use App\Services\ResponseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegistrationControllerService
{
    use CustomerAccountTrait;

    protected $paystackService, $customerAuth, $responseService, $customerBioData, $customerNextOfKin, $verifyemail,
        $bankOneAccountServices, $otpCode, $bankOneUtilServices, $customerRegistrationSetting;

    public function __construct(CustomerAuthInterface $customerAuth, PaystackService $paystackService,
                                ResponseService $responseService, CustomerBioDataInterface $customerBioData,
                                CustomerNextOfKinInterface $customerNextOfKin, VerifyEmailInterface $verifyemail,
                                AccountServices $bankOneAccountServices, OtpCodeInterface $otpCode,
                                BankOneUtilServices $bankOneUtilServices,
                                CustomerRegistrationSettingInterface $customerRegistrationSetting)
    {
        $this->paystackService = $paystackService;
        $this->customerAuth = $customerAuth;
        $this->responseService = $responseService;
        $this->customerBioData = $customerBioData;
        $this->customerNextOfKin = $customerNextOfKin;
        $this->verifyemail = $verifyemail;
        $this->bankOneAccountServices = $bankOneAccountServices;
        $this->otpCode = $otpCode;
        $this->bankOneUtilServices = $bankOneUtilServices;
        $this->customerRegistrationSetting = $customerRegistrationSetting;
    }

    /**
     * Verify bvn
     *
     * @param object $request
     *
     * @return JsonResponse
     */
    public function verifyBvn(object $request)
    {
        //Check if bvn number already exist in DB
        $checkBvnSession = bvnSessionGet($request->bvn);

        if ($checkBvnSession) {
            return $this->responseService->getSuccessResource(['data' => formatBvnDetails(json_decode($checkBvnSession->bvn_attributes))]);
        }

        $bvnParams = [
            'BVN' => $request->bvn,
            'token' => env('BANK_ONE_INSTITUTION_TOKEN')
        ];

        $bvn = $this->bankOneUtilServices->verifyBVN($bvnParams);

        if ($bvn['isBvnValid'] != true) {

            return $this->responseService->getErrorResource([
                "message" => 'Invalid BVN'
            ]);
        }

        $bvnDetails = [
            'first_name' => $bvn['bvnDetails']['FirstName'],
            'last_name' => $bvn['bvnDetails']['LastName'],
            'dob' => $bvn['bvnDetails']['DOB'],
            'mobile' => $bvn['bvnDetails']['phoneNumber'],
            'bvn' => $bvn['bvnDetails']['BVN'],
        ];

        // store BVN variable in session to be use for create request verification

        bvnSessionStore(['bvn_number' => $bvn['bvnDetails']['BVN'], 'bvn_attributes' => $bvnDetails]);

        return $this->responseService->getSuccessResource(['data' => formatBvnDetails($bvnDetails)]);
    }

    /**
     * Basic form step one validation
     *
     * @return JsonResponse
     */
    public function basicFormStepOneValidation()
    {
        return $this->responseService->getSuccessResource([
            'message' => "Please check your phone for OTP code"
        ]);
    }

    /**
     * Verify phone OTP
     *
     * @param object $request
     *
     * @return JsonResponse
     */
    public function verifyPhoneOtp(object $request)
    {
        $verifyOtp = $this->otpCode->verifyOtpCode($request->otp, $request->phone);

        if (!is_array($verifyOtp)) return $verifyOtp;

        return $this->responseService->getSuccessResource([
            'message' => 'Phone verification successful'
        ]);
    }

    /**
     * Basic form step two validation
     *
     * @param object $request
     *
     * @return array|JsonResponse
     */
    public function basicFormStepTwoValidation(object $request)
    {

        $checkOtp = $this->checkOtpIfUsedOrValid($request);

        if (!is_array($checkOtp)) return $checkOtp;

        return $this->responseService->getSuccessResource([
            'message' => 'Validation successful'
        ]);
    }

    /**
     * Check OTP if used or valid
     *
     * @param $request
     *
     * @return array|JsonResponse
     */
    private function checkOtpIfUsedOrValid($request)
    {

        if ($request->input('customer_onboarding_customer')) {
            $onBoardingCustomer = $this->customerOnboarding($request);
            if ($onBoardingCustomer){
                $confirmOtpCode = $this->otpCode->findByColumn([
                    ['nuban', '=', $onBoardingCustomer->nuban]
                ])->first();
            } else {
                $confirmOtpCode = false;
            }

        } else {
            $confirmOtpCode = $this->otpCode->findByColumn([
                ['nuban', '=', $request->phone]
            ])->first();
        }

        if (! $confirmOtpCode) {
            return $this->responseService->getErrorResource([
                'message' => 'Please verify your phone'
            ]);
        }

        if ($confirmOtpCode->used == false) {
            return $this->responseService->getErrorResource([
                'message' => 'Otp-Code not yet confirmed'
            ]);
        }

        return [
            'status' => true
        ];
    }

    /**
     * Store new customer and customer onboarding signup
     *
     * @param object $request
     *
     * @return array|JsonResponse
     */
    public function create(object $request)
    {
        //Check if phone number has been verify
        $checkOtp = $this->checkOtpIfUsedOrValid($request);

        if (!is_array($checkOtp)) return $checkOtp;

        try {

            $params = $request->all();

            //check if bvn is part of the input field and also check if it is save in session
            if (isset($params['bvn']) && bvnSessionGet($params['bvn'])) {

                $bvnSession = bvnSessionGet($params['bvn'])->bvn_attributes;

                $bvnDetails = json_decode($bvnSession);

                if (!$bvnDetails || $bvnDetails->bvn != $params['bvn'] || strtoupper($bvnDetails->first_name) != strtoupper($params['first_name'])
                    || strtoupper($bvnDetails->last_name) != strtoupper($params['last_name'])) {
                    return $this->responseService->getErrorResource([
                        'message' => 'Information does not correspond with BVN details'
                    ]);
                }

                $params['bvn_phone'] = $bvnDetails->mobile;

                $params['bvn_dob'] = $bvnDetails->dob;

            } else {
                $params['bvn'] = null;
            }

            //Get device information
            if ($request->headers->get("Platform") === 'WEB') {
                //for web platform generate a device id
                $params['device_id'] = getUniqueToken(10);
                $params['device_name'] = "WEB";
            } else {
                $params['device_id'] = $request->headers->get("device-id");
                $params['device_name'] = $request->headers->get("device-name");
            }

            if (!$params['device_id'] || !$params['device_name']) {
                return $this->responseService->getErrorResource([
                    'message' => 'No device information'
                ]);
            }

            if ($request->input('referred_by')){
                $customer = $this->customerAuth->findByColumn([
                    ['referral_code', '=', $request->input('referred_by')]
                ])->first();
                $params['referral_phone']  = $customer->phone;
                $params['referral_full_name'] = $customer->full_name;
            }

            DB::beginTransaction();

            if ( ! $request->input('customer_onboarding_customer') ) {

                //bankOne account creation endpoint
                $bankOne = $this->bankOneAccountCreation($params);

                if (! $bankOne['IsSuccessful']) {
                    logger()->warning($bankOne);
                    return $this->responseService->getErrorResource([
                        'message' => 'OOPS!!! Something went wrong, please try again'
                    ]);
                }

                $params['nuban'] = $bankOne['Message']['AccountNumber'];

                $params['cba_id'] = $bankOne['Message']['CustomerID'];

            }
            if ($request->input('customer_onboarding_customer')) {

                $onBoardingCustomer = $this->customerOnboarding($request);

                $params['nuban'] = $onBoardingCustomer->nuban;

                $params['cba_id'] = $onBoardingCustomer->cba_id;

                $params['first_name'] = $onBoardingCustomer->first_name;

                $params['last_name'] = $onBoardingCustomer->last_name;

                $params['phone'] = $onBoardingCustomer->phone;

                $params['referred_by'] = $onBoardingCustomer->customer->referral_code;

                if ($onBoardingCustomer->email){
                    $params['email'] = $onBoardingCustomer->email;
                }

                $onBoardingCustomer->activate = 1;
                $onBoardingCustomer->save();
            }

            $customer = $this->customerAuth->create($params);

            $params['customer_id'] = $customer->id;

            $this->customerBioData->create($params);

            $this->customerNextOfKin->create($params);

            storeCustomerDevice($params);

            if (isset($params['bvn']) && bvnSessionGet($params['bvn']))
                bvnSessionDelete($bvnDetails->bvn);

            $this->otpCode->findByColumn([
                ['nuban', '=', $params['phone']]
            ])->delete();

            $onBoardingCustomer = $this->customerOnboarding($request);
            if ($onBoardingCustomer) {
                $this->otpCode->findByColumn([
                    ['nuban', '=', $onBoardingCustomer->nuban]
                ])->delete();
                self::updateCustomerAccount($customer);
            }

            DB::commit();

            Log::info('New Customer Registration', formatLogResponse($customer));

            $params['platformToken'] = $request->headers->get("Platform-Token");
            $params['platform'] = $request->headers->get("Platform");

            return $user = $this->customerAuth->apiLogin($params);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed New User Registration', formatLogResponse($e));
            return $this->responseService->getErrorResource([
                'message' => 'OOPS!!! Something went wrong, please contact system admin '
            ]);
        }

    }

    /**
     * Get customer on-boarding that is yet to signup
     *
     * @param $request
     *
     * @return mixed
     */
    private function customerOnboarding($request){
        return app(CustomerOnboardingCustomerInterface::class)->findByColumn([
            ['phone', '=', $request->phone],
            ['activate', '=', false]
        ])->with(['customer'])->select(['id','customer_id','first_name','last_name','phone','email','nuban','amount','cba_id'])->first();
    }

    /**
     * Create customer account in CBA
     *
     * @param array $params
     *
     * @return mixed
     */
    private function bankOneAccountCreation(array $params)
    {

        $regSettings = $this->customerRegistrationSetting->findByColumn([
            ['active', '=', true]
        ])->first();

        $bankOneParams = [
            'TransactionTrackingRef' => "npay/" . $params['email'],
            'AccountOpeningTrackingRef' => "npay/" . $params['email'],
            'ProductCode' => $regSettings->product_code,
            'LastName' => strtoupper($params['last_name']),
            'OtherNames' => strtoupper($params['first_name']),
            'BVN' => $params['bvn'] ?? null,
            'FullName' => strtoupper($params['last_name']) . ' ' . strtoupper($params['first_name']),
            'PhoneNo' => $params['phone'],
            'Gender' => $params['gender'] === 'Male' ? 0 : 1,
            'DateOfBirth' => $params['dob'],
            'Address' => $params['address'],
            'ReferralPhoneNo' => $params['referral_phone'] ?? null,
            'ReferralName' => $params['referral_full_name'] ?? null,
            'NextOfKinPhoneNo' => $params['next_of_kin_phone'],
            'NextOfKinName' => strtoupper($params['next_of_kin_name']),
            'Email' => $params['email'],
//           'CustomerImage' => $params['image'],
            'HasSufficientInfoOnAccountInfo' => true,
            'AccountInformationSource' => 0,
            'NotificationPreference' => 3,
            'AccountOfficerCode' => $regSettings->account_officer_code
        ];

        return $this->bankOneAccountServices->createAccount($bankOneParams);
    }

    /**
     * Verify account number, for old customer trying to signup
     *
     * @param object $request
     *
     * @return JsonResponse
     */
    public function verifyAccountNumber(object $request)
    {
        try {
            $response = $this->bankOneAccountServices->getCustomerByAccountNumber($request->account_number);

                Log::info(json_encode($response));

            if ($response && $response['customerID']) {
                $token = getUniqueToken();
                $this->otpCode->create($token, $request->account_number, $response['Email'],
                    json_encode($response), $request->headers->get("device-id"));

                $sms[] = [
                    'AccountNumber' => $request->account_number,
                    'To' => $response['PhoneNumber'],
                    'AccountId' => $response['customerID'],
                    'Body' => "Otp-Code " . $token,
                    'ReferenceNo' => $token
                ];

                dispatch(new ExistingCustomerRegistrationOtpCodeJob($token, $response['Email'], $response['PhoneNumber'], $sms));
                return $this->responseService->getSuccessResource([
                    'data' => [
                        'customer_onboarding_customer' => $this->checkIfAccountNumberIsCustomerOnboardingCustomer($request)],
                    'message' => 'Please check your email or phone for otp-code'
                ]);
            }

            return $this->responseService->getErrorResource([
                'message' => 'Invalid account number'
            ]);

        } catch (Exception $exception) {
            Log::critical(json_encode($exception));
        }
    }

    /**
     * Check in account number is for customer on-boarding customer account number
     *
     * @param $request
     *
     * @return mixed
     */
    private function checkIfAccountNumberIsCustomerOnboardingCustomer($request)
    {
        return app(CustomerOnboardingCustomerInterface::class)->findByColumn([
            ['nuban', '=', $request->account_number],
            ['activate', '=', false]
        ])->select(['first_name','last_name','phone','email','nuban','amount'])->first();
    }

    /**
     * Verify OTP
     *
     * @param object $request
     *
     * @return mixed
     */
    public function verifyOtpcode(object $request)
    {
        $request->all();

        return $this->otpCode->verifyOtpCode($request->otp_code, $request->account_number, '', $request->headers->get("device-id"));
    }

    /**
     * Register an existing customer
     *
     * @param object $request
     *
     * @return JsonResponse
     */
    public function createCustomerUsingAccountNumber(object $request)
    {

        $params = $request->all();

        $confirmOtpCode = $this->otpCode->findByColumn([
            ['nuban', '=', $params['account_number']],
            ['token', '=', $params['otp_code']]
        ])->first();

        if (!$confirmOtpCode) {
            return $this->responseService->getErrorResource([
                'message' => 'Invalid request'
            ]);
        }

        if ($confirmOtpCode->used == false) {
            return $this->responseService->getErrorResource([
                'message' => 'Otp-Code not yet confirmed'
            ]);
        }

        $params['device_id'] = $request->headers->get("device-id");

        $params['device_name'] = $request->headers->get("device-name");

        if (!$params['device_id'] || !$params['device_name']) {
            return $this->responseService->getErrorResource([
                'message' => 'No device information'
            ]);
        }

        try {

            DB::beginTransaction();

            $bankOne = json_decode($confirmOtpCode->details, true);

            $params['nuban'] = $confirmOtpCode->nuban;

            $params['cba_id'] = $bankOne['customerID'];

            $params['first_name'] = $bankOne['OtherNames'];

            $params['last_name'] = $bankOne['LastName'];

            $params['phone'] = $bankOne['PhoneNumber'];

            $params['email'] = $bankOne['Email'] ?? $request->email;

            $params['gender'] = $bankOne['Gender'];

            $params['registration_method'] = 'Existing';

            $customer = $this->customerAuth->create($params);

            $params['customer_id'] = $customer->id;
            $params['dob'] = $bankOne['DateOfBirth'];
            $params['address'] = $bankOne['Address'];

            if ($bankOne['BankVerificationNumber']) {

                $bvnParams = [
                    'BVN' => $bankOne['BankVerificationNumber'],
                    'token' => env('BANK_ONE_INSTITUTION_TOKEN')
                ];

                $bvn = $this->bankOneUtilServices->verifyBVN($bvnParams);
                $params['bvn'] = $bvn['bvnDetails']['BVN'];
                $params['bvn_phone'] = $bvn['bvnDetails']['phoneNumber'];
                $params['bvn_dob'] = $bvn['bvnDetails']['DOB'];
            }

            $this->customerBioData->create($params);

            $params['next_of_kin_name'] = strtoupper($bankOne['WorkNextOfKin']);
            $params['next_of_kin_address'] = $bankOne['WorkNextOfKinAddress'];
            $params['next_of_kin_phone'] = $bankOne['WorkNextOfKinPhoneNo'];
            $params['next_of_kin_city'] = null;
            $params['next_of_kin_state'] = null;
            $this->customerNextOfKin->create($params);

            storeCustomerDevice($params);

            DB::commit();

            Log::info('New Customer Registration', formatLogResponse($customer));

            $params['platformToken'] = $request->headers->get("Platform-Token");
            $params['platform'] = $request->headers->get("Platform");

            return $this->customerAuth->apiLogin($params);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed New User Registration', formatLogResponse($e));
            return $this->responseService->getErrorResource([
                'message' => 'OOPS!!! Something went wrong, please contact system admin'
            ]);
        }
    }

    /**
     * Get state
     *
     * @return JsonResponse
     */
    public function getState()
    {
        return $this->responseService->getSuccessResource([
            'data' => State::select(['id', 'name'])->get()
        ]);
    }
}
