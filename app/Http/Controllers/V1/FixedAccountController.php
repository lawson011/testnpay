<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\FixedDepositRequest;
use App\Repositories\FixedAccountSetting\FixedAccountSettingInterface;
use App\Services\BankOne\FixedAccountServices\FixedAccountControllerService;


class FixedAccountController extends Controller
{
    protected $accountControllerService,$fixedAccountSetting, $responseService;

    public function __construct(FixedAccountControllerService $accountControllerService,
                                FixedAccountSettingInterface $fixedAccountSetting)
    {
        $this->accountControllerService = $accountControllerService;
        $this->fixedAccountSetting = $fixedAccountSetting;
    }

    public function create(FixedDepositRequest $request){
        return $this->accountControllerService->create($request->all());
    }

    public function settings():object {
        return $this->accountControllerService->settings();
    }

    public function history():object {
        return $this->accountControllerService->history();
    }
}
