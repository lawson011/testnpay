<?php


namespace App\Services\Auth;

use App\Jobs\ForgotPasswordJob;
use App\Repositories\Auth\AuthInterface;
use App\Repositories\CustomerAuth\CustomerAuthInterface;
use App\Repositories\OtpCode\OtpCodeInterface;
use App\Services\ResponseService;
use Exception;
use Illuminate\Support\Facades\DB;

class ResetPasswordService
{
    protected $otpCode, $responseService, $customerAuth;

    public function __construct(OtpCodeInterface $otpCode, ResponseService $responseService, CustomerAuthInterface $customerAuth)
    {
        $this->otpCode = $otpCode;
        $this->responseService = $responseService;
        $this->customerAuth = $customerAuth;
    }

    public function requestResetForgotPassword(object $request)
    {
        $token = getUniqueToken();

        $username = $request->username;

        $customer = $this->customerAuth->findByColumn(['username' => $username])->first();

        $this->otpCode->create($token, $customer->nuban, $customer->email, null, $request->headers->get("device-id"));

        dispatch(new ForgotPasswordJob($customer->email, $token));

        return $this->responseService->getSuccessResource([
            'message' => "Please check your email we've sent you a verification code"
        ]);
    }

    public function resetPassword(object $request)
    {

        try {

            DB::beginTransaction();

            $params = $request->all();

            $customer = $this->customerAuth->findByColumn(['username' => $params['username']])->first();

            //check if email and token tally
            $checkEmail = $this->otpCode->findByColumn(['email' => $customer->email, 'token' => $params['token']])->first();

            $verifyOtp = $this->otpCode->verifyOtpCode($checkEmail->token, $checkEmail->nuban, $checkEmail->email, $request->headers->get("device-id"));

            if (!is_array($verifyOtp)) return $verifyOtp;

            //update password
            $customer->password = bcrypt($params['password']);
            $customer->save();

            //delete email from verification table
            $this->otpCode->findByColumn(['email' => $customer->email])->delete();
            $this->otpCode->findByColumn(['nuban' => $customer->nuban])->delete();
            DB::commit();

            $params['customer_id'] = $customer->id;
            $params['device_id'] = $request->headers->get("device-id");
            $params['device_name'] = $request->headers->get("device-name");
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

}
