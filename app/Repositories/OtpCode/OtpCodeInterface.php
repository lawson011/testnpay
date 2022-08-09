<?php

namespace App\Repositories\OtpCode;

interface OtpCodeInterface
{
    public function create($token, $nuban = null, $email = null, $details = null, $deviceId = null);

    public function findByColumn(array $params);

    public function findById(int $id);

    public function getAll();

    public function checkExpiry($time);

    public function verifyOtpCode($token, $account_number = null, $email = null, $deviceId = null);
}
