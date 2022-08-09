<?php


namespace App\Services\BankOne\FixedAccountServices;

use App\Http\Resources\FixedDepositHistoryResource;
use App\Http\Resources\FixedDepositSettingResource;
use App\Repositories\CustomerAuth\CustomerAuthInterface;
use App\Repositories\FixedAccount\FixedAccountInterface;
use App\Repositories\FixedAccountSetting\FixedAccountSettingInterface;
use App\Services\BankOne\CustomerAccount\AccountServices;
use App\Services\ResponseService;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Log;
use Matrix\Exception;

class FixedAccountControllerService
{
    protected $customerAuth, $fixedAccountService, $fixedAccount, $fixedAccountSetting, $accountServices, $responseService;

    public function __construct(CustomerAuthInterface $customerAuth, FixedAccountSettingInterface $fixedAccountSetting,
                                FixedAccountService $fixedAccountService, FixedAccountInterface $fixedAccount,
                                ResponseService $responseService, AccountServices $accountServices)
    {
        $this->customerAuth = $customerAuth;
        $this->fixedAccountService = $fixedAccountService;
        $this->fixedAccount = $fixedAccount;
        $this->fixedAccountSetting = $fixedAccountSetting;
        $this->responseService = $responseService;
        $this->accountServices = $accountServices;
    }

    public function create(array $params){


        try {

        $customer = $this->customerAuth->authCustomer();

        //check customer balance
        $body['account_number'] = $customer->nuban;

        //check if amount is greater than account balance
        if ($params['amount'] > getUserAccountDetails($body)['AvailableBalance'] / 100) {
            return $this->responseService->getErrorResource([
                'message' => 'Insufficient balance'
            ]);
        }

        $fixedAcctSetting = $this->fixedAccountSetting->findById($params['tenure']);
        $bankOneParams = [
            'IsDiscountDeposit' => true,
            'Amount' => $params['amount'],
            'Tenure' => $fixedAcctSetting->tenure,
            'CustomerID' => $customer->cba_id,
            'ProductCode' => $fixedAcctSetting->product_code,
            'LiquidationAccount' => $customer->nuban,
            'ApplyInterestMonthly' => $params['interest_monthly'] == 1 ? true : false,
            'ApplyInterestOnRollOver' => false,
            'ShouldRollOver' => false
        ];

        //create fix deposit account in bankOne
        $store = $this->fixedAccountService->create($bankOneParams);

        if ($store['IsSuccessful'] == true){
            //save in fix deposit table
            $params['days'] = $fixedAcctSetting->tenure;
            $params['interest_rate'] = $fixedAcctSetting->interest_rate;
            $params['product_code'] = $fixedAcctSetting->product_code;
            $this->fixedAccount->create($params);
            return $this->responseService->getSuccessResource();
        } else {

            Log::critical('Investment failed ---', formatLogResponse($customer));
            Log::critical('Investment failed bankone response', $store);
            return $this->responseService->getErrorResource([
                'message' => 'Please try again'
            ]);
        }

        }catch (Exception $exception){
            report($exception);
            return $this->responseService->getErrorResource([
                'message' => 'Please try again'
            ]);
        }

    }

    public function settings(){

        $data = $this->fixedAccountSetting->findByColumn(['active'=>true])->latest()->get();

        return $this->responseService->getSuccessResource([
            'data' => FixedDepositSettingResource::collection($data)
        ]);
    }

    public function history(){

       $getLoan = $this->fixedAccountService->getFixedDepositAccountByLiquidatedAccountNo($this->customerAuth->authCustomer()->nuban);

        return $this->responseService->getSuccessResource([
            'data' => FixedDepositHistoryResource::collection($getLoan)
        ]);
    }

}
