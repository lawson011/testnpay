<?php

namespace App\Repositories\OtpCode;

use App\Models\OtpCode;
use App\Services\ResponseService;
use Carbon\Carbon;

class OtpCodeRepository implements OtpCodeInterface
{

    protected $responseService, $model;

    public function __construct(ResponseService $responseService, OtpCode $model)
    {
        $this->responseService = $responseService;
        $this->model = $model;
    }

    public function create($token, $nuban = null, $email = null, $details = null, $deviceId = null)
    {
        if ($nuban) {
            $checkOtpCode = $this->update('nuban', $nuban, $token, $email, $nuban, $details, $deviceId);
        }

        if ($email) {
            $checkOtpCode = $this->update('email', $email, $token, $email, $nuban, $details, $deviceId);
        }

        if (! $checkOtpCode) {
            $model = $this->model;
            $this->setModelProperties($model, $token, $email, $nuban, $details, $deviceId);
            $model->save();
            $checkOtpCode = $model;
        }
        return $checkOtpCode;
    }

    private function update($column, $value, $token, $email, $nuban, $details, $deviceId)
    {
        $checkOtpCode = $this->model::where($column, $value)->first();
        if ($checkOtpCode) {
            $this->setModelProperties($checkOtpCode, $token, $email, $nuban, $details, $deviceId);
            $checkOtpCode->save();
        }
        return $checkOtpCode;
    }

    private function setModelProperties($model, $token, $email, $nuban, $details, $deviceId)
    {
        $model->time = now();
        $model->email = $email;
        $model->nuban = $nuban;
        $model->token = $token;
        $model->used = false;
        $model->details = $details;
        $model->device_id = $deviceId;
    }

    public function findById(int $id){
        return $this->model::find($id);
    }

    public function getAll()
    {
        return $this->model::latest();
    }

    public function verifyOtpCode($token, $account_number = null, $email = null, $deviceId = null)
    {

        //check if email and token tally
        $checkEmail = $this->findByColumn([
            ['nuban', '=', $account_number],
            ['token', '=', $token],
            ['device_id', '=', $deviceId]
        ])->first();

        if (!$checkEmail) {
            $checkEmail = $this->findByColumn([
                ['email', '=', $email],
                ['token', '=', $token],
                ['device_id', '=', $deviceId]
            ])->first();
        }

        if (!$checkEmail || $checkEmail->token != $token)
            return $this->responseService->getErrorResource([
                'message' => 'Invalid Otp-code'
            ]);

        if ($checkEmail->used == true) {
            return $this->responseService->getErrorResource([
                'message' => 'Otp-code already used, please request for a new Otp-code ' . $checkEmail->nuban
            ]);
        }

        //check if token has not expire
        $isExpired = $this->checkExpiry($checkEmail->time);

        if (!$isExpired) {
            return $this->responseService->getErrorResource([
                'message' => 'Otp-code expired'
            ]);
        }

        //update verified
        $checkEmail->used = true;
        $checkEmail->save();

        //An exception to return true if no details so further logic can be handle at were it was called.
        if (!$checkEmail->details)
            return [
                'status' => true
            ];

        $params = json_decode($checkEmail->details, true);

        return $this->responseService->getSuccessResource([
            'message' => 'Account verification successful',
            'data' => [
                'first_name' => $params['OtherNames'],
                'last_name' => $params['LastName'],
                'email' => $params['Email']
            ]
        ]);

    }

    public function findByColumn(array $params)
    {
        return $this->model::where($params);
    }

    public function checkExpiry($time)
    {

        $currentTimePlus10Minute = Carbon::now();

        //expiring time is 10min
        $expiringTime = 10;

        //If different in minute is lesser than or equal to 10
        return $currentTimePlus10Minute->diffInMinutes($time) <= $expiringTime;

    }

}
