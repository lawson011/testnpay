<?php


namespace App\Services\BankOne\CustomerAccount;

use Exception;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class AccountServices
{
        //"Account/CreateCustomerAccount/2"; to upload image but it timeout
    //CreateAccountQuick without image
    public const quickAccountCreation = "/Account/CreateAccountQuick/2";

    public const getByAccountNumber = "/Customer/GetByAccountNumber/2";

    public const getCustomerAccounts = "/Customer/GetCustomerInfoByCustomerID/2";

    public const updateCustomerAccounts = "/Customer/UpdateCustomer/2";

    public function createAccount(array $params)
    {
        $url = env('BANK_ONE_BASE_URL').self::quickAccountCreation;

        $client = new Client([
            'headers' => [ 'Content-Type' => 'application/json' ]
        ]);

        $response = $client->post($url.'?authtoken='.env('BANK_ONE_INSTITUTION_TOKEN').'&version=2',
            ['body' => json_encode($params)]
        );

        return json_decode($response->getBody(),true);
    }

    public function getCustomerByAccountNumber($accountNumber){

        try {
            $url = env('BANK_ONE_BASE_URL') . self::getByAccountNumber;

            $client = new Client([
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $response = $client->get($url . '?authtoken=' . env('BANK_ONE_INSTITUTION_TOKEN') . '&accountNumber=' . $accountNumber
            );

            return json_decode($response->getBody(), true);
        }catch (Exception $exception){
            report($exception);
            return false;
        }
    }

    public function customerAccounts($customerId)
    {
        try {
            $url = env('BANK_ONE_BASE_URL') . self::getCustomerAccounts;

            $client = new Client([
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $response = $client->get($url . '?authtoken=' . env('BANK_ONE_INSTITUTION_TOKEN') .
                '&customerID=' . $customerId .'&mfbCode='.env('BANK_ONE_INSTITUTION_CODE')
            );

            return json_decode($response->getBody(), true);
        }catch (Exception $exception){
            report($exception);
            return false;
        }
    }

    /**
     * Update customer information on CBA
     *
     * @param array $params
     *
     * @return bool|mixed
     */
    public function updateCustomerAccount(array $params)
    {
        try {
            $url = env('BANK_ONE_BASE_URL') . self::updateCustomerAccounts;

            $client = new Client([
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $response = $client->post($url.'?authtoken='.env('BANK_ONE_INSTITUTION_TOKEN').'&version=2',
                ['body' => json_encode($params)]
            );

            return json_decode($response->getBody(), true);
        }catch (Exception $exception){
            report($exception);
            return false;
        }
    }
}
