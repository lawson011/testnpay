<?php


namespace App\Services\BankOne\FixedAccountServices;

use App\Services\BankOne\BaseService;
use GuzzleHttp\Client;

class FixedAccountService
{
    protected $bankOneBaseService;

    const fixedAccountCreation = "/FixedDeposit/CreateFixedDepositAcct/2";
    const getFixedDepositAccountByLiquidatedAccount = "/FixedDeposit/GetFixedDepositAccountByLiquidationAccount/2";

    public function __construct(BaseService $bankOneBaseService)
    {
        $this->bankOneBaseService = $bankOneBaseService;
    }

    public function create(array $params){

        $url = env('BANK_ONE_BASE_URL').self::fixedAccountCreation;

        $client = new Client([
            'headers' => [ 'Content-Type' => 'application/json' ]
        ]);

        $response = $client->post($url.'?authtoken='.env('BANK_ONE_INSTITUTION_TOKEN'),
            ['body' => json_encode($params)]
        );

        return json_decode($response->getBody(),true);

    }

    public function getFixedDepositAccountByLiquidatedAccountNo($liquidatedAccountNumber){

        $url = env('BANK_ONE_BASE_URL').self::getFixedDepositAccountByLiquidatedAccount;

        $client = new Client([
            'headers' => [ 'Content-Type' => 'application/json' ]
        ]);

        $response = $client->get($url.'?authtoken='.env('BANK_ONE_INSTITUTION_TOKEN').'&accountNumber='.$liquidatedAccountNumber
        );

        return json_decode($response->getBody(),true);
    }

}
