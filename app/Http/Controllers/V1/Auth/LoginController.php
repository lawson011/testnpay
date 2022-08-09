<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerLoginRequest;

use App\Http\Requests\RefreshTokenRequest;
use App\Http\Requests\WebLoginOtpCodeRequest;
use App\Http\Requests\WebLoginRequest;
use App\Http\Resources\UserResource;
use App\Jobs\webLoginOtpCodeJob;
use App\Models\Customers\CustomerDevice;
use App\Models\User;
use App\Repositories\CustomerAuth\CustomerAuthInterface;
use App\Repositories\OtpCode\OtpCodeInterface;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    protected $auth, $responseService, $otpCode;

    public function __construct(CustomerAuthInterface $auth, ResponseService $responseService, OtpCodeInterface $otpCode)
    {
        $this->auth = $auth;
        $this->responseService = $responseService;
        $this->otpCode = $otpCode;
    }

    public function index(CustomerLoginRequest $request)
    {
        $params = $request->all();
        $params['device_id'] = $request->headers->get("device-id");
        $params['platformToken'] = $request->headers->get("Platform-Token");
        $params['platform'] = $request->headers->get("Platform");

        return $this->auth->apiLogin($params);
    }

    public function webLoginOtpCode(WebLoginOtpCodeRequest $request)
    {

        $params = $request->all();

        $customer = $this->auth->findByColumn(['username' => $params['username']])->first();
        if (!Hash::check($params['password'], $customer->password)) {
            return $this->responseService->getErrorResource([
                'message' => 'Invalid Username or Password' // To prevent attackers from detecting if the email is valid or not.
            ]);
        }

        $token = getUniqueToken();

        $this->otpCode->create($token, $customer->nuban, $customer->email, '');

        $sms[] = [
            'AccountNumber' => $customer->nuban,
            'To' => $customer->phone,
            'AccountId' => $customer->cba_id,
            'Body' => "Otp-Code " . $token,
            'ReferenceNo' => $token + $customer->id
        ];

        dispatch(new webLoginOtpCodeJob($token, $customer->email, $customer->phone, $sms));

        return $this->responseService->getSuccessResource([
            'message' => 'Please check your email or phone for otp-code'
        ]);
    }

    public function webLogin(WebLoginRequest $request)
    {

        $params = $request->all();

        $customer = $this->auth->findByColumn(['username' => $params['username']])->first();

        //check if otp-code is valid
        $response = $this->otpCode->verifyOtpCode($params['otp_code'], $customer->nuban, $customer->email, null);

        if (!is_array($response)) return $response;

        $params['device_id'] = getUniqueToken();
        $params['platformToken'] = $request->headers->get("Platform-Token");
        $params['platform'] = $request->headers->get("Platform");

        $this->otpCode->findByColumn(['nuban' => $customer->nuban])->delete();
        $this->otpCode->findByColumn(['email' => $customer->email])->delete();

        $customerDetails = $this->auth->apiLogin($params);

        //check if customer has login with web
        $customerID = decrypt($customerDetails['data']['identifier']);
        $checkDevice = CustomerDevice::where([
            ['customer_id', '=', $customerID],
            ['device_name', '=', 'WEB']
        ])->first();

        if (!$checkDevice) {
            $customerDevice = new CustomerDevice();
            $customerDevice->customer_id = $customerID;
            $customerDevice->device_name = 'WEB';
            $customerDevice->device_id = $params['device_id'];
            $customerDevice->last_login = now();
            $customerDevice->active = true;
            $customerDevice->save();
        } else {
            $checkDevice->last_login = now();
            $checkDevice->save();
        }

        return $customerDetails;

    }

    public function refreshToken(RefreshTokenRequest $request)
    {
        $params = $request->all();
        $params['device_id'] = $request->headers->get("device-id");
        $params['platformToken'] = $request->headers->get("Platform-Token");
        $params['platform'] = $request->headers->get("Platform");
        return $this->auth->refreshToken($params);
    }

    public function logout()
    {
        return $this->auth->logout();
    }

    public function unauthenticatedResponse()
    {
        return $this->responseService->getErrorResource([
            'message' => 'Unauthenticated',
            'status_code' => '401'
        ]);
    }
}
