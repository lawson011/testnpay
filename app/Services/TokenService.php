<?php


namespace App\Services;

use App\Services\ResponseService;
use App\Repositories\OtpCode\OtpCodeInterface;
use App\Repositories\CustomerAuth\CustomerAuthInterface;
use App\Services\PinServiceTrait;
use App\Jobs\NewTokenGenerated;
use App\Jobs\TransactionPin;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class TokenService
{
    protected $response;

    /**
     * TokenService constructor.
     * @param \App\Services\ResponseService $response
     */
    public function __construct(ResponseService $response)
    {
        $this->response = $response;
    }

    /**
     * @param $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function generate($user)
    {
        $token = getUniqueToken();
        $tokenRepo = app(OtpCodeInterface::class);
        $pin = $tokenRepo->create($token, $user->nuban, $user->email);

        if ($pin) {
            $this->dispatchNotifyTokenGenerated([
                'token' => $token,
                'email' => $user->email,
                'AccountNumber' => $user->nuban,
                'To' => $user->phone,
                "Body" => "Otp-Code " . $token,
                "ReferenceNo" => $token + getUniqueToken(5),
            ]);

            return $this->response->getSuccessResource([
                'message' => 'Token Sent please check mail'
            ]);
        }

        return $this->response->getErrorResource([
            'message' => 'Failed to create token'
        ]);

    }

    /**
     * @param $token
     * @param $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function validate($token, $user)
    {
        $optCode = app(OtpCodeInterface::class);
        $checkEmail = $optCode->findByColumn(['nuban' => $user->nuban, 'email' => $user->email])->first();

        $data = $this->tokenIsValid($checkEmail, $token);

        if (!empty($data['message'])) {
            return $this->response->getErrorResource([
                'message' => $data['message']
            ]);
        }

        DB::beginTransaction();
        $checkEmail->used = true;
        $checkEmail->save();
        DB::commit();

        return $this->response->getSuccessResource([
            'message' => 'Token Valid'
        ]);
    }

    /**
     * @param $checkEmail
     * @param $token
     * @return array|bool
     */
    public function tokenIsValid($checkEmail, $token)
    {
        if (!$checkEmail || $checkEmail->token !== $token) {
            return [
                'message' => 'Invalid opt-code'
            ];
        }


        if ($checkEmail->used === true) {
            return [
                'message' => 'opt-code already used, please request for a new opt-code'
            ];
        }

        if (!app(OtpCodeInterface::class)->checkExpiry($checkEmail->time)) {
            return [
                'message' => 'opt-code expired'
            ];
        }
    }

    /**
     * @param $array
     */
    public function dispatchNotifyTokenGenerated($array): void
    {
        dispatch(new NewTokenGenerated($array));
    }

}
