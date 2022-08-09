<?php

namespace App\Services\BankOne\ThirdPartyApiService\Account\Traits;


use App\Exceptions\ApplicationProcessFailedException;
use Carbon\Carbon;

trait AccountValidation
{

    /**
     * @param $data
     * @param $amount
     * @throws ApplicationProcessFailedException
     */
    public function runAccountValidation($data, $amount): void
    {
        $today = Carbon::parse(auth()->user()->created_at)->isToday();
        $checkAmount = $amount > 19999;

        if ($today && $checkAmount) {
            throw new ApplicationProcessFailedException(
                'Cannot transfer this amount until after 24hours', 400
            );
        }

        if (!is_array($data) && !isset($data['IsSuccessful'])) {
            throw new ApplicationProcessFailedException(
                'Request failed', 400
            );
        }

        if ($data['LienStatus'] === 'Active') {
            throw new ApplicationProcessFailedException(
                'Lien placed on account', 400
            );
        }

        if ($amount > ($data['AvailableBalance'] / 100)) {
            throw new ApplicationProcessFailedException(
                'Account balance low', 400
            );
        }

        if ($data['FreezeStatus'] === 'Active') {
            throw new ApplicationProcessFailedException(
                'Account has been frozen', 400
            );
        }

        if ($data['Status'] === 'InActive') {
            throw new ApplicationProcessFailedException(
                'Account is inactive', 400
            );
        }

    }
}
