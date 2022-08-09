<?php


namespace App\Services;


use App\Jobs\DetachDeviceJob;
use App\Jobs\ProcessDetachDevicesMail;
use App\Jobs\ProcessDetachedDeviceMail;
use App\Repositories\Auth\AuthInterface;
use App\Repositories\CustomerAuth\CustomerAuthInterface;
use App\Repositories\CustomerDevice\CustomerDeviceInterface;
use App\Repositories\OtpCode\OtpCodeInterface;
use App\Repositories\UserDevice\UserDeviceInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DeviceService
{
    protected $customerAuth, $responseService, $otpCode, $customerDevice;

    public function __construct(CustomerAuthInterface $customerAuth, ResponseService $responseService, CustomerDeviceInterface $customerDevice,
                                OtpCodeInterface $otpCode)
    {
        $this->customerAuth = $customerAuth;
        $this->responseService = $responseService;
        $this->otpCode = $otpCode;
        $this->customerDevice = $customerDevice;
    }

    public function requestDetach(object $request)
    {

        $token = getUniqueToken();

        $username = $request->username;

        $customer = $this->customerAuth->findByColumn(['username' => $username])->first();

        $this->otpCode->create($token, $customer->nuban, $customer->email, null, $request->headers->get("device-id"));

        dispatch(new ProcessDetachDevicesMail($customer->email, $token));

        return $this->responseService->getSuccessResource([
            'message' => "Please check your email we've sent you a verification code"
        ]);
    }

    public function detach(object $request)
    {

        $params = $request->all();

        $customer = $this->customerAuth->findByColumn(['username' => $params['username']])->first();

        try {

            DB::beginTransaction();
            //check if email and token tally
            $checkEmail = $this->otpCode->findByColumn(['email' => $customer->email, 'token' => $params['token']])->first();

            $verifyOtp = $this->otpCode->verifyOtpCode($checkEmail->token, $checkEmail->nuban, $checkEmail->email, $request->headers->get("device-id"));

            if (!is_array($verifyOtp)) return $verifyOtp;

            $params['device_id'] = $request->headers->get("Device-id");
            $params['device_name'] = $request->headers->get("Device-name");

            if (!$params['device_id'] || !$params['device_name']) {
                return $this->responseService->getErrorResource([
                    'message' => 'No device information'
                ]);
            }


            if (!Hash::check($params['password'], $customer->password)) {
                return $this->responseService->getErrorResource([
                    'message' => 'Invalid Email or Password' // To prevent attackers from detecting if the email is valid or not.
                ]);
            }

            $this->customerDevice->findByColumn(['customer_id' => $customer->id])->update(['active' => false]);

            //store new user device
            $params['customer_id'] = $customer->id;
            storeCustomerDevice($params);

            //delete email from verification table
            $this->otpCode->findByColumn(['email' => $customer->email])->delete();
            $this->otpCode->findByColumn(['email' => $customer->nuban])->delete();

            dispatch(new ProcessDetachedDeviceMail($customer, $params['device_name']));
            DB::commit();
            $params['platformToken'] = $request->headers->get("Platform-Token");
            $params['platform'] = $request->headers->get("Platform");
            return $this->customerAuth->apiLogin($params);

        } catch (Exception $e) {

            DB::rollBack();
            report($e);
            return $this->responseService->getErrorResource([
                'message' => 'OOPS!!! Something went wrong, please contact system admin '
            ]);
        }
    }

//    public function verifyEmail(object $request)
//    {
//        return $this->otpCode->verifyEmail($request->all());
//    }
}
